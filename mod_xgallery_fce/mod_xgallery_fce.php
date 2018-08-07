<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component XMovie Component
 * @copyright Copyright (C) Dana Harris optikool.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if(!defined('COM_XGALLERY_BASE_PATH_COLLECTION')) {
	$cfgParams =  JComponentHelper::getParams('com_xgallery');
	$comMediaImagePath = JComponentHelper::getParams('com_media')->get('image_path', 'images');
	$galleryPathIsExternal = $cfgParams->get('image_external');
	$galleryBasePathThumbnail = JPATH_ROOT;
	
	if($galleryPathIsExternal) {
		$galleryBasePathCollection = $cfgParams->get('image_external_path');
	} else {
		$galleryBasePathCollection = $galleryBasePathThumbnail . DS . $comMediaImagePath;
	}
		
	//$galleryBasePathCollectionFull = $galleryBasePathCollection . DS . $galleryCollectionFolder;
	//define('COM_XGALLERY_BASE_PATH_COLLECTION', $galleryBasePathCollectionFull);
}

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

require_once (JPATH_SITE.DS.'components'.DS.'com_xgallery'.DS.'router.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_xgallery'.DS.'helpers'.DS.'gallery.php');

GalleryHelper::setCookieParams();

$document = JFactory::getDocument();

$lists = modXGalleryFCEHelper::getList($params);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

if($params->get('show_css')) {
	modXGalleryFCEHelper::addStyleSheet();
}

require JModuleHelper::getLayoutPath('mod_xgallery_fce', $params->get('layout', 'default'));
