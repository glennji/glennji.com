<?php

/**
 * ProcessWire Pages
 *
 * Manages Page instances, providing find, load, save and delete capabilities,
 * some of which are delegated to other classes but this provides the interface to them.
 *
 * This is the most used object in the ProcessWire API. 
 *
 * @TODO Move everything into delegate classes, leaving this as just the interface to them.
 * 
 * ProcessWire 2.x 
 * Copyright (C) 2010 by Ryan Cramer 
 * Licensed under GNU/GPL v2, see LICENSE.TXT
 * 
 * http://www.processwire.com
 * http://www.ryancramer.com
 *
 */

class Pages extends Wire {

	/**
	 * Instance of PageFinder for finding pages
	 *
	 */
	protected $pageFinder; 

	/**
	 * Instance of Templates
	 *
	 */
	protected $templates; 

	/**
	 * Instance of PagesSortfields
	 *
	 */
	protected $sortfields;

	/**
	 * Pages that have been cached, indexed by ID
	 *
	 */
	protected $pageIdCache = array();

	/**
	 * Cached selector strings and the PageArray that was found.
	 *
	 */
	protected $pageSelectorCache = array();

	/**
	 * Controls the outputFormatting state for pages that are loaded
	 *
	 */
	protected $outputFormatting = false; 

	/**
	 * Create the Pages object
	 *
	 */
	public function __construct() {

		$this->templates = $this->fuel('templates'); 
		$this->pageFinder = new PageFinder($this->fuel('fieldgroups')); 
		$this->sortfields = new PagesSortfields();
	}


	/**
	 * Given a Selector string, return the Page objects that match in a PageArray. 
	 *
	 * @param string $selectorString
	 * @param array $options 
		- findOne: apply optimizations for finding a single page and include pages with 'hidden' status
	 * @return PageArray
	 *
	 */
	public function ___find($selectorString, $options = array()) {

		// TODO selector strings with runtime fields, like url=/about/contact/, possibly as plugins to PageFinder

		if(!strlen($selectorString)) return new PageArray();

		if($selectorString[0] == '/') {
			// if selector begins with a slash, then we'll assume it's referring to a path
			$selectorString = "path=$selectorString";

		} else if(strpos($selectorString, ",") === false && strpos($selectorString, "|") === false) {
			// there is just one param. Lets see if we can find a shortcut. 
			if(ctype_digit("$selectorString") || strpos($selectorString, "id=") === 0) {
				// if selector is just a number, or a string like "id=123" then we're going to do a shortcut
				$s = str_replace("id=", '', $selectorString); 
				if(ctype_digit("$s")) {
					$page = $this->getById(array((int) $s)); 
					$pageArray = new PageArray();
					return $page ? $pageArray->add($page) : $pageArray; 
				}
			}

		} 

		// check if this find has already been executed, and return the cached results if so
		// if(null !== ($pages = $this->getSelectorCache($selectorString, $options))) return clone $pages; 

		// if a specific parent wasn't requested, then we assume they don't want results with status >= Page::statusUnsearchable
		// if(strpos($selectorString, 'parent_id') === false) $selectorString .= ", status<" . Page::statusUnsearchable; 

		$selectors = new Selectors($selectorString); 
		$pages = $this->pageFinder->find($selectors, $options); 

		// note that we save this pagination state here and set it at the end of this method
		// because it's possible that more find operations could be executed as the pages are loaded
		$total = $this->pageFinder->getTotal();
		$limit = $this->pageFinder->getLimit();
		$start = $this->pageFinder->getStart();

		// parent_id is null unless a single parent was specified in the selectors
		$parent_id = $this->pageFinder->getParentID();

		$idsSorted = array(); 
		$idsByTemplate = array();

		// organize the pages by template ID
		foreach($pages as $page) {
			$tpl_id = $page['templates_id']; 
			if(!isset($idsByTemplate[$tpl_id])) $idsByTemplate[$tpl_id] = array();
			$idsByTemplate[$tpl_id][] = $page['id'];
			$idsSorted[] = $page['id'];
		}

		if(count($idsByTemplate) > 1) {
			// perform a load for each template, which results in unsorted pages
			$unsortedPages = new PageArray();
			foreach($idsByTemplate as $tpl_id => $ids) {
				$unsortedPages->import($this->getById($ids, $this->templates->get($tpl_id), $parent_id)); 
			}

			// put pages back in the order that the selectorEngine returned them in, while double checking that the selector matches
			$pages = new PageArray();
			foreach($idsSorted as $id) {
				foreach($unsortedPages as $page) { 
					if($page->id == $id) {
						$pages->add($page); 
						break;
					}
				}
			}
		} else {
			// there is only one template used, so no resorting is necessary	
			$pages = new PageArray();
			reset($idsByTemplate); 
			$pages->import($this->getById($idsSorted, $this->templates->get(key($idsByTemplate)), $parent_id)); 
		}

		$pages->setTotal($total); 
		$pages->setLimit($limit); 
		$pages->setStart($start); 
		$pages->setSelectors($selectors); 
		$pages->setTrackChanges(true);
		$this->selectorCache($selectorString, $options, $pages); 

		return $pages; 
		//return $pages->filter($selectors); 
	}

	/**
	 * Like find() but returns only the first match as a Page object (not PageArray)
	 *
	 * @param string $selectorString
	 * @return Page|null
	 *
	 */
	public function findOne($selectorString, $options = array()) {
		if($page = $this->getCache($selectorString)) return $page; 
		$options['findOne'] = true; 
		$page = $this->find($selectorString, $options)->first();
		if(!$page) $page = new NullPage();
		return $page; 
	}

	/**
	 * Returns only the first match as a Page object (not PageArray).
	 *
	 * Otherwise works the same as find(). Excludes any pages the user doesn't have access to view. 
	 * See the get__() method if you want to include those pages or add: "status<max, check_access=0" to your selector.
	 * 
	 * This is an alias of the findOne() method for syntactic convenience and consistency.
	 * Using get() is preferred.
	 *
	 * @param string $selectorString
	 * @return Page|NullPage Always returns a Page object, but will return NullPage (with id=0) when no match found
	 */
	public function get($selectorString) {
		return $this->findOne($selectorString); 
	}

	/**
	 * Given an array or CSV string of Page IDs, return a PageArray 
	 *
	 * @param array|WireArray|string $ids Array of IDs or CSV string of IDs
	 * @param Template $template Specify a template to make the load faster, because it won't have to attempt to join all possible fields... just those used by the template. 
	 * @param int $parent_id Specify a parent to make the load faster, as it reduces the possibility for full table scans
	 * @return PageArray
	 *
	 */
	public function getById($ids, Template $template = null, $parent_id = null) {

		static $instanceID = 0;

		$pages = new PageArray();
		if(is_string($ids)) $ids = explode(",", $ids); 
		if(!WireArray::iterable($ids) || !count($ids)) return $pages; 
		if(is_object($ids)) $ids = $ids->getArray();
		$loaded = array();

		foreach($ids as $key => $id) {
			$id = (int) $id; 
			$ids[$key] = $id; 

			if($page = $this->getCache($id)) {
				$loaded[$id] = $page; 
				unset($ids[$key]); 
			
			} else if(isset(Page::$loadingStack[$id])) {
				// if the page is already in the process of being loaded, point to it rather than attempting to load again.
				// the point of this is to avoid a possible infinite loop with autojoin fields referencing each other.
				$loaded[$id] = Page::$loadingStack[$id];
				// cache the pre-loaded version so that other pages referencing it point to this instance rather than loading again
				$this->cache($loaded[$id]); 
				unset($ids[$key]); 

			} else {
				$loaded[$id] = ''; // reserve the spot, in this order
			}
		}

		$idCnt = count($ids); 
		if(!$idCnt) return $pages->import($loaded); 
		$idsByTemplate = array();

		if(is_null($template)) {
			$sql = "SELECT id, templates_id FROM pages WHERE ";
			if($idCnt == 1) $sql .= "id=" . (int) reset($ids); 
				else $sql .= "id IN(" . implode(",", $ids) . ")";
			$result = $this->db->query($sql); 
			if($result && $result->num_rows) while($row = $result->fetch_row()) {
				list($id, $templates_id) = $row; 
				if(!isset($idsByTemplate[$templates_id])) $idsByTemplate[$templates_id] = array();
				$idsByTemplate[$templates_id][] = $id; 
			}
			$result->free();
		} else {
			$idsByTemplate = array($template->id => $ids); 
		}

		foreach($idsByTemplate as $templates_id => $ids) { 

			if(!$template || $template->id != $templates_id) $template = $this->fuel('templates')->get($templates_id);
			$fields = $template->fieldgroup; 
			$query = new DatabaseQuerySelect();

			$query->select(	
				"false AS isLoaded, pages.templates_id AS templates_id, pages.*, pages_sortfields.sortfield, " . 
				"(SELECT COUNT(*) FROM pages AS children WHERE children.parent_id=pages.id) AS numChildren"
				); 

			$query->leftjoin("pages_sortfields ON pages_sortfields.pages_id=pages.id"); 
			$query->groupby("pages.id"); 
		
			foreach($fields as $field) {
				if(!($field->flags & Field::flagAutojoin)) continue; 
				$table = $field->table; 
				if(!$field->type->getLoadQueryAutojoin($field, $query)) continue; // autojoin not allowed
				$query->leftjoin("$table ON $table.pages_id=pages.id"); 
			}

			if(!is_null($parent_id)) $query->where("pages.parent_id=" . (int) $parent_id); 

			$query->where("pages.templates_id={$template->id}"); 
			$query->where("pages.id IN(" . implode(',', $ids) . ") "); 
			$query->from("pages"); 

			if(!$result = $query->execute()) throw new WireException($this->db->error); 

			$class = ($template->pageClass && class_exists($template->pageClass)) ? $template->pageClass : 'Page';

			while($page = $result->fetch_object($class, array($template))) {
				$page->instanceID = ++$instanceID; 
				$page->setIsLoaded(true); 
				$page->setIsNew(false); 
				$page->setTrackChanges(true); 
				$page->setOutputFormatting($this->outputFormatting); 
				$loaded[$page->id] = $page; 
				$this->cache($page); 
			}

			$template = null;
			$result->free();
		}

		return $pages->import($loaded); 
	}

	/**
	 * Count and return how many pages will match the given selector string
	 *
	 * @param string $selectorString
	 * @return int
	 * @todo optimize this so that it only counts, and doesn't have to load any pages in the process. 
	 *
	 */
	public function count($selectorString, $options = array()) {
		// PW doesn't count when limit=1, which is why we limit=2
		return $this->find("$selectorString, limit=2", $options)->getTotal();
	}

	/**
	 * Is the given page in a state where it can be saved?
	 *
	 * @param Page $page
	 * @param string $reason Text containing the reason why it can't be saved (assuming it's not saveable)
	 * @return bool True if saveable, False if not
	 *
	 */
	public function isSaveable(Page $page, &$reason) {

		$saveable = false; 

		if($page instanceof NullPage) $reason = "Pages of type NullPage are not saveable";
			else if((!$page->parent || $page->parent instanceof NullPage) && $page->id !== 1) $reason = "It has no parent assigned"; 
			else if(!$page->template) $reason = "It has no template assigned"; 
			else if(!strlen(trim($page->name))) $reason = "It has an empty 'name' field"; 
			else if($page->outputFormatting) $reason = "outputFormatting is on - Call \$page->setOutputFormatting(false) to turn it off"; 
			else if($page->is(Page::statusCorrupted)) $reason = "It was corrupted when you modified a field with outputFormatting - See Page::setOutputFormatting(false)"; 
			else if($page->id == 1 && !$page->template->useRoles) $reason = "Selected homepage template cannot be used because it does not define access.";
			else if($page->id == 1 && !$page->template->hasRole('guest')) $reason = "Selected homepage template cannot be used because it does not have the required 'guest' role in it's access settings.";
			else $saveable = true; 

		// check for a parent change
		if($saveable && $page->parentPrevious && $page->parentPrevious->id != $page->parent->id) {
			// page was moved
			if($page->template->noMove) {
				// make sure the page's template allows moves
				$saveable = false;
				$reason = "Pages using template '{$page->template}' are not moveable (template::noMove)";

			} else if($page->parent->template->childrenTemplatesID < 0) {
				$saveable = false;
				$reason = "Chosen parent '{$page->parent->path}' uses template that does not allow children.";

			} else if($page->parent->template->childrenTemplatesID > 0 && $page->template->id != $page->parent->template->childrenTemplatesID) {
				// make sure the new parent's template allows pages with this template
				$saveable = false;
				$reason = "Can't move '{$page->name}' because Template '{$page->parent->template}' used by '{$page->parent->path}' doesn't allow children with this template.";

			} else if(count($page->parent->children("name={$page->name},status<" . Page::statusMax))) { 
				$saveable = false;
				$reason = "Chosen parent '{$page->parent->path}' already has a page named '{$page->name}'"; 
			}
		}

		return $saveable; 
	}

	/**
	 * Save a page object and it's fields to database. 
	 *
	 * If the page is new, it will be inserted. If existing, it will be updated. 
	 *
	 * This is the same as calling $page->save()
	 *
	 * If you want to just save a particular field in a Page, use $page->save($fieldName) instead. 
	 *
	 * @param Page $page
	 * @return bool True on success
	 *
	 */
	public function ___save(Page $page) {

		$reason = '';
		$isNew = $page->isNew();
		if(!$this->isSaveable($page, $reason)) throw new WireException("Can't save page {$page->id}: {$page->path}: $reason"); 

		if($page->parentPrevious) {
			if($page->isTrash() && !$page->parentPrevious->isTrash()) $this->trash($page, false); 
				else if($page->parentPrevious->isTrash() && !$page->parent->isTrash()) $this->restore($page, false); 
		}

		$user = $this->fuel('user'); 
		$userID = $user ? $user->id : $this->config->superUserPageID; 

		$sql = 	"pages SET " . 
			"parent_id=" . ((int) $page->parent_id) . ", " . 
			"templates_id=" . ((int) $page->template->id) . ", " . 
			"name='" . $this->db->escape_string($page->name) . "', " . 
			"modified_users_id=" . ((int) $userID) . ", " . 
			"status=" . ((int) $page->status) . ", " . 
			"sort=" . ((int) $page->sort) . "," . 
			"modified=NOW()"; 

		if($isNew) {
			if($page->id) $sql .= ", id=" . (int) $page->id; 
			$result = $this->db->query("INSERT INTO $sql, created=NOW(), created_users_id=" . (int) $userID); 
			if($result) $page->id = $this->db->insert_id; 

		} else {
			$result = $this->db->query("UPDATE $sql WHERE id=" . (int) $page->id); 
		}

		if(!$result) return false;

		if(!$page->isChanged()) return true; // if page hasn't changed, don't continue further

		$page->filesManager->save();

		// save each individual Fieldtype data in the fields_* tables
		foreach($page->fieldgroup as $field) {
			$field->type->savePageField($page, $field);
		}

		$this->sortfields->save($page); 
		$page->resetTrackChanges();
		if($isNew) $page->setIsNew(false); 

		if($page->templatePrevious && $page->templatePrevious->id != $page->template->id) {
			// the template was changed, so we may have data in the DB that is no longer applicable
			// find unused data and delete it
			foreach($page->templatePrevious->fieldgroup as $field) {
				if($page->template->fieldgroup->has($field)) continue; 
				$field->type->deletePageField($page, $field); 
				if($this->config->debug) $this->message("Deleted field '$field' on page {$page->url}"); 
			}
		}

		$this->uncacheAll();

		// determine whether the pages_access table needs to be updated so that pages->find()
		// operations can be access controlled. 

		if($isNew || $page->parentPrevious || $page->templatePrevious) new PagesAccess($page);

		// lastly determine whether the pages_parents table needs to be updated for the find() cache
		// and call upon $this->saveParents where appropriate. 

		if(($isNew && $page->parent->id) || ($page->parentPrevious && !$page->parent->numChildren)) {
			$page = $page->parent; // new page or one that's moved, lets focus on it's parent
			$isNew = true; // use isNew even if page was moved, because it's the first page in it's new parent
		}

		if($page->numChildren || $isNew) {
			// check if entries aren't already present perhaps due to outside manipulation or an older version
			$n = 0;
			if(!$isNew) {
				$result = $this->db->query("SELECT COUNT(*) FROM pages_parents WHERE parents_id={$page->id}"); 
				list($n) = $result->fetch_array();
				$result->free();
			}
			// if entries aren't present, if the parent has changed, or if it's been forced in the API, proceed
			if($n == 0 || $page->parentPrevious || $page->forceSaveParents === true) {
				$this->saveParents($page->id, $page->numChildren + ($isNew ? 1 : 0)); 
			}
		}

		return $result; 
	}

	/**
	 * Save just a field from the given page as used by Page::save($field)
	 *
	 * This function is public, but the preferred manner to call it is with $page->save($field)
	 *
	 * @param Page $page
	 * @param string|Field $fieldName
	 * @return bool True on success
	 *
	 */
	public function ___saveField(Page $page, $field) {

		$reason = '';
		if($page->isNew()) throw new WireException("Can't save field from a new page - please save the entire page first"); 
		if(!$this->isSaveable($page, $reason)) throw new WireException("Can't save field from page {$page->id}: {$page->path}: $reason"); 
		if($field && (is_string($field) || is_int($field))) $field = $this->fuel('fields')->get($field);
		if(!$field instanceof Field) throw new WireException("Unknown field supplied to saveField for page {$page->id}");
		if(!$page->fields->has($field)) throw new WireException("Page {$page->id} does not have field {$field->name}"); 

		$value = $page->get($field->name); 
		if($value instanceof Pagefiles || $value instanceof Pagefile) $page->filesManager()->save();
		$page->trackChange($field->name); 	
		return $field->type->savePageField($page, $field); 
	}


	/**
	 * Save references to the Page's parents in pages_parents table, as well as any other pages affected by a parent change
	 *
	 * Any pages_id passed into here are assumed to have children
	 *
	 * @param int $pages_id ID of page to save parents from
	 * @param int $numChildren Number of children this Page has
	 *
	 */
	protected function saveParents($pages_id, $numChildren) {

		$pages_id = (int) $pages_id; 
		if(!$pages_id) return false; 

		$this->db->query("DELETE FROM pages_parents WHERE pages_id=$pages_id"); 

		if(!$numChildren) return true; 

		$insertSql = ''; 
		$id = $pages_id; 
		$cnt = 0;

		do {
			$result = $this->db->query("SELECT parent_id FROM pages WHERE id=$id"); 
			list($id) = $result->fetch_array();
			if(!$id) break;
			$insertSql .= "($pages_id, $id),";
			$cnt++; 

		} while(1); 

		if($insertSql) {
			$this->db->query("INSERT INTO pages_parents (pages_id, parents_id) VALUES" . rtrim($insertSql, ",")); 
		}

		// find all children of $pages_id that themselves have children
		$result = $this->db->query(
			"SELECT pages.id, COUNT(children.id) AS numChildren " . 
			"FROM pages " . 
			"JOIN pages AS children ON children.parent_id=pages.id " . 
			"WHERE pages.parent_id=$pages_id " . 
			"GROUP BY pages.id "
			); 

		while($row = $result->fetch_array()) {
			$this->saveParents($row['id'], $row['numChildren']); 	
		}
		$result->free();

		return true; 	
	}

	/**
	 * Sets a new Page status and saves the page, optionally recursive with the children, grandchildren, and so on.
	 *
	 * While this can be performed with other methods, this is here just to make it fast for internal/non-api use. 
	 * See the trash and restore methods for an example. 
	 *
	 * While the method is public, this method is not intended for general API use and you can ignore it. 
	 *
	 * @param int $pageID 
	 * @param int $status Status per flags in Page::status* constants
	 * @param bool $recursive Should the status descend into the page's children, and grandchildren, etc?
	 *
	 */
	protected function savePageStatus($pageID, $status, $recursive = false) {
		$pageID = (int) $pageID; 
		$status = (int) $status; 
		$this->db->query("UPDATE pages SET status=$status WHERE id=$pageID"); 
		if($recursive) { 
			$result = $this->db->query("SELECT id FROM pages WHERE parent_id=$pageID"); 
			while($row = $result->fetch_array()) {
				$this->savePageStatus($row['id'], $status, true); 
			}
			$result->free();
		}
	}

	/**
	 * Is the given page deleteable?
	 *
	 * Note: this does not account for user permission checking. It only checks if the page is in a state to be saveable via the API. 
	 *
	 * @param Page $page
	 * @return bool True if deleteable, False if not
	 *
	 */
	public function isDeleteable(Page $page) {

		$deleteable = true; 
		if(!$page->id || $page->status & Page::statusSystemID || $page->status & Page::statusSystem) $deleteable = false; 
			else if($page instanceof NullPage) $deleteable = false;

		return $deleteable;
	}

	/**
	 * Move a page to the trash
	 *
	 * If you have already set the parent to somewhere in the trash, then this method won't attempt to set it again. 
	 *
	 * @param Page $page
	 * @param bool $save Set to false if you will perform the save() call, as is the case when called from the Pages::save() method.
	 * @return bool
	 *
	 */
	public function ___trash(Page $page, $save = true) {
		if(!$this->isDeleteable($page)) throw new WireException("This page may not be placed in the trash"); 
		if(!$trash = $this->get($this->config->trashPageID)) {
			throw new WireException("Unable to load trash page defined by config::trashPageID"); 
		}
		$page->addStatus(Page::statusTrash); 
		if(!$page->parent->isTrash()) $page->parent = $trash;
		if(!preg_match('/^' . $page->id . '_.+/', $page->name)) {
			// make the name unique when in trash, to avoid namespace collision
			$page->name = $page->id . "_" . $page->name; 
		}
		if($save) $this->save($page); 
		$this->savePageStatus($page->id, $page->status, true); 
		return true; 
	}

	/**
	 * Restore a page from the trash back to a non-trash state
	 *
	 * Note that this method assumes already have set a new parent, but have not yet saved
	 *
	 * @param Page $page
	 * @param bool $save Set to false if you only want to prep the page for restore (i.e. being saved elsewhere)
	 * @return bool
	 *
	 */
	protected function ___restore(Page $page, $save = true) {
		if(preg_match('/^(' . $page->id . ')_(.+)$/', $page->name, $matches)) {
			$name = $matches[2]; 
			if(!count($page->parent->children("name=$name"))) 
				$page->name = $name;  // remove namespace collision info if no collision
		}
		$page->removeStatus(Page::statusTrash); 
		if($save) $page->save();
		$this->savePageStatus($page->id, $page->status, true); 
		return true; 
	}

	/**
	 * Permanently delete a page and it's fields. 
	 *
	 * Unlike trash(), pages deleted here are not restorable. 
	 *
	 * If you attempt to delete a page with children, and don't specifically set the $recursive param to True, then 
	 * this method will throw an exception. If a recursive delete fails for any reason, an exception will be thrown.
	 *
	 * @param Page $page
	 * @param bool $recursive If set to true, then this will attempt to delete all children too. 
	 * @return bool
	 *
	 */
	public function ___delete(Page $page, $recursive = false) {

		if(!$this->isDeleteable($page)) throw new WireException("This page may not be deleted"); 

		if($page->numChildren) {
			if(!$recursive) throw new WireException("Can't delete Page $page because it has one or more children."); 
			foreach($page->children("status<" . Page::statusMax) as $child) {
				if(!$this->delete($child, true)) throw new WireException("Error doing recursive page delete, stopped by page $child"); 
			}
		}
	
		foreach($page->fieldgroup as $field) {
			if(!$field->type->deletePageField($page, $field)) {
				$this->error("Unable to delete field '$field' from page '$page'"); 
			}
		}

		$page->filesManager->emptyAllPaths();
		// $page->getCacheFile()->remove();

		$access = new PagesAccess();	
		$access->deletePage($page); 

		$this->db->query("DELETE FROM pages_parents WHERE pages_id=" . (int) $page->id); 
		$this->db->query("DELETE FROM pages WHERE id=" . ((int) $page->id) . " LIMIT 1"); 

		// $this->getFuel('pagesRoles')->deleteRolesFromPage($page); // TODO convert to hook
		$this->sortfields->delete($page); 
		$this->uncacheAll();
		$page->setTrackChanges(false); 
		$page->status = Page::statusDeleted; // no need for bitwise addition here, as this page is no longer relevant

		return true; 
	}


	/**
	 * Given a Page ID, return it if it's cached, or NULL of it's not. 
	 *
	 * If no ID is provided, then this will return an array copy of the full cache.
	 *
	 * You may also pass in the string "id=123", where 123 is the page_id
	 *
	 * @param int|string|null $id 
	 * @return Page|array|null
	 *
	 */
	public function getCache($id = null) {
		if(!$id) return $this->pageIdCache; 
		if(!ctype_digit("$id")) $id = str_replace('id=', '', $id); 
		$id = (int) $id; 
		if(!isset($this->pageIdCache[$id])) return null; 
		$page = $this->pageIdCache[$id];
		$page->setOutputFormatting($this->outputFormatting); 
		return $page; 
	}

	/**
	 * Cache the given page. 
	 *
	 * @param Page $page
	 *
	 */
	public function cache(Page $page) {
		if($page->id) $this->pageIdCache[$page->id] = $page; 
	}

	/**
	 * Remove the given page from the cache. 
	 *
	 * Note: does not remove pages from selectorCache. Call uncacheAll to do that. 
	 *
	 * @param Page $page
	 *
	 */
	public function uncache(Page $page) {
		$page->uncache();
		unset($this->pageIdCache[$page->id]); 
	}

	/**
	 * Remove all pages from the cache. 
	 *
	 */
	public function uncacheAll() {

		unset($this->pageFinder); 
		$this->pageFinder = new PageFinder($this->fuel('fieldgroups')); 

		unset($this->sortfields); 
		$this->sortfields = new PagesSortfields();

		foreach($this->pageIdCache as $id => $page) {
			if(!$page->numChildren) $this->uncache($page); 
		}
		$this->pageIdCache = array();
		$this->pageSelectorCache = array();
	}

	/**
	 * Cache the given selector string and options with the given PageArray
	 *
	 */
	protected function selectorCache($selector, array $options, PageArray $pages) {
		return; // STILL TESTING
		$selector = $this->getSelectorCache($selector, $options, true); 		
		$this->pageSelectorCache[$selector] = $pages; 
	}

	/**
	 * Retrieve any cached page IDs for the given selector and options OR false if none found.
	 *
	 * You may specify a third param as TRUE, which will cause this to just return the selector string (with hashed options)
	 *
	 * @param string $selector
	 * @param array $options
	 * @param bool $returnSelector default false
	 * @return array|null|string
	 *
	 */
	protected function getSelectorCache($selector, $options, $returnSelector = false) {
		if(count($options)) {
			$optionsHash = '';
			ksort($options);		
			foreach($options as $key => $value) $optionsHash .= "[$key:$value]";
			$selector .= "," . $optionsHash;
		}
		if($returnSelector) return $selector; 
		if(isset($this->pageSelectorCache[$selector])) return $this->pageSelectorCache[$selector]; 
		return null; 
	}

	/**
	 * For internal Page instance access, return the Pages sortfields property
	 *
	 * @return PagesSortFields
	 *
	 */
	public function sortfields() {
		return $this->sortfields; 
	}

	/**	
 	 * Return a fuel or other property set to the Pages instance
	 *
	 */
	public function __get($key) {
		return parent::__get($key); 
	}

	/**
	 * Set whether loaded pages have their outputFormatting turn on or off
	 *
	 * By default, it is turned on. 
	 *
	 */
	public function setOutputFormatting($outputFormatting = true) {
		$this->outputFormatting = $outputFormatting ? true : false; 
	}

}


