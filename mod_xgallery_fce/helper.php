<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component XGallery Component
 * @copyright Copyright (C) Dana Harris optikool.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 
defined('_JEXEC') or die('Restricted access');

class modXGalleryFCEHelper {
	private $_num_collections;
	private $_collections_per_row;
	private $_category_id;
	private $_catids;
	private $_sort_method;
	private $_query;
	private $_data;
	private $_div_width;
	private $_moduleclass_sfx;
	private $_show_thumb;
	private $_show_name;
	private $_show_quicktake;
	private $_show_date;
	private $_show_hits;
	private $_show_submitter;
	private $_catslug;
	private $_collslug;
	private $_enable_css;
			
	public function __construct(&$params) {		
		$this->_num_collections 	= $params->get('num_collections');
		$this->_category_id 		= (int)$params->get('cat_id');
		$this->_collection_id 		= $params->get('coll_id');
		$this->_sort_method 		= $params->get('sort_method');
		$this->_collections_per_row = $params->get('collections_per_row');
		$this->_moduleclass_sfx 	= $params->get('moduleclass_sfx');
		$this->_show_thumb 			= $params->get('show_thumb');
		$this->_show_name 			= $params->get('show_name');
		$this->_show_quicktake 		= $params->get('show_quicktake');
		$this->_show_date 			= $params->get('show_date');
		$this->_show_hits 			= $params->get('show_hits');
		$this->_show_submitter		= $params->get('show_submitter');
		$this->_enable_css			= $params->get('show_css');
		$this->_div_width 			= "";
	}
	
	public static function getList(&$params) {
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$cat_ids = implode(',', $params->get('cat_id'));
		$itemids = GalleryHelper::getItemIds();
		$tmpRows = array();
		$cTime = time();
		$sort_method = $params->get('sort_method');
		$cLimit = $params->get('num_collections');
		$items = array();
				
		// Create a new query object.
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		// Select required fields from the categories.
		$query->select('a.*');
		$query->from($db->quoteName('#__xgallery').' AS a');
		
		// Join over the categories.
		$query->select('c.alias AS catalias, c.title AS cattitle');
		$query->join('LEFT', $db->quoteName('#__categories') . ' AS c ON c.id = a.catid');
		
		$query->where('a.access IN ('.$groups.')');
		$query->where('a.catid IN ('.$cat_ids.')');
				
		$query->where('a.published = 1');
		
		switch($sort_method) {
			case 'popular':
				$sort_method = "hits DESC";
				break;
			case 'oldest':
				$sort_method = "creation_date ASC";
				break;
			case 'random':
				$sort_method= "RAND()";
				break;
			case 'newest':
			default:
				$sort_method= "creation_date DESC";
				break;
		}
			
		$query->order($sort_method);
		
		$db->setQuery($query, 0, $cLimit);
		$rows = $db->loadObjectList();
		if(count($rows) < $cLimit) {
			$cLimit = count($rows);
		}
		
		// Convert the params field into an object, saving original in _params
		for ($i = 0; $i < $cLimit; $i++) {
			$items[$i] = $rows[$i];
			$cid = $items[$i]->catid;			
			$user = JFactory::getUser($items[$i]->user_id);			
			$items[$i]->submitter = $user->username;
			if(isset($itemids[$cid]['itemId']) && $itemids[$cid]['itemId'] != '') {
				$items[$i]->itemId = $itemids[$cid]['itemId'];
			} else {
				$items[$i]->itemId = $itemids[0]['itemId'];
			}			
		}
		
		unset($rows);
		$rows = $items;
		
		return $rows;
	}

	public static function getTags($params) {
		$this->_item->tags = new JHelperTags;
        $this->_item->tags->getItemTags('com_xgallery.collection' , $this->_item->id);
	}

	public static function addStyleSheet($params) {
		$layout = $params->get('layout', 'default');
		$layouts = split(':', $layout);	
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/modules/mod_xgallery_fce/css/'.$layouts[1].'-style.css');
	}
		
	public static function getDivWidth($params) {
		$div_width = intval(100 / intval($params->get('collections_per_row')));
		$browser = GalleryHelper::getBrowserType();
		if($browser['browser'] == 'IE') {
			$div_width = $div_width - 1;
		}
		return $div_width;
	}
}	
?>
