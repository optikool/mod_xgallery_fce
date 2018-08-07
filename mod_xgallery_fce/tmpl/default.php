<?php
/*
 * @package Joomla 3.0
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component XGallery Component
 * @copyright Copyright (C) Dana Harris optikool.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 
defined('_JEXEC') or die ('Restricted access.');
$items_count = count($lists);
$currRow = 1;
$itemCount = 1;
$fceImgPerRow = $params->get('collections_per_row', 4);
$span = $params->get('bootstrap_size', 0);
$span_rounded = round(12 / intval($fceImgPerRow));

if($span == 0) {
	$span = '';
} else {
	$span = "span{$span}";
}
?>

<?php 
if($items_count > 0) {	
	foreach($lists as $item) {
	$link = JRoute::_("index.php?option=com_xgallery&view=single&catid={$item->catid}:{$item->catalias}&id={$item->id}:{$item->alias}&Itemid={$item->itemId}");
				
	if($currRow == 1) {
	?>
	<div class="row-fluid <?php echo $span;?> <?php echo $params->get('moduleclass_sfx'); ?>">
	<?php
	}
?>
	<div class="image-item text-center span<?php echo $span_rounded; ?>">
		<?php if($params->get('show_thumb')) { ?>
		<a href="<?php echo $link ;?>">
			<img class="thumbnail img-responsive" src="<?php echo JURI::root(true); ?>/components/com_xgallery/helpers/img.php?file=<?php echo urlencode($item->thumb); ?>&amp;tn=0" alt="<?php echo htmlspecialchars($item->title); ?>" />
		</a>
		<?php } ?>
		<?php if($params->get('show_name')) {?>
		<h4>
			<?php if($params->get('show_thumb')) {
				echo htmlspecialchars($item->title);
			} else { ?>
				<a href="<?php echo $link ;?>">
				<?php echo htmlspecialchars($item->title); ?>
				</a>
			<?php } ?>
		</h4>
		<?php } ?>
		<?php if($params->get('show_date')) {?>
		<div class="image-date">
			<strong><?php echo JTEXT::_('MOD_XGALLERY_FCE_DATE'); ?></strong> <?php echo JHTML::Date($item->creation_date, 'm-d-Y'); ?>
		</div>
		<?php } ?>
		<?php if($params->get('show_hits')) {?>
		<div class="image-hits">
			<strong><?php echo JTEXT::_('MOD_XGALLERY_FCE_HITS'); ?></strong> <?php echo $item->hits; ?>
		</div>
		<?php } ?>
		<?php if($params->get('show_submitter')) {?>
		<div class="image-submitter">
			<strong><?php echo JTEXT::_('MOD_XGALLERY_FCE_SUBMITTER'); ?></strong> <?php echo $item->submitter; ?>
		</div>
		<?php } ?>
		<?php if($params->get('show_tags')) {?>
		<div class="image-submitter">
			<?php
			$item->tags = new JHelperTags;
			$item->tags->getItemTags('com_xgallery.collection' , $item->id);
			$item->tagLayout = new JLayoutFile('joomla.content.tags');
			?>
			<strong><?php echo JTEXT::_('MOD_XGALLERY_FCE_TAGS'); ?>:</strong> <?php echo $item->tagLayout->render($item->tags->itemTags); ?>
		</div>
		<?php } ?>
		<?php if($params->get('show_quicktake')) {?>
		<div class="image-introtext">
			<?php echo $item->introtext; ?>
		</div>
		<?php } ?>
	</div>
	<?php 
		
		if($currRow < $fceImgPerRow && $itemCount != $items_count) {
			$currRow++;
		} else {
			echo '<div class="clearfix"></div>';
			echo '</div>';
			$currRow = 1;
		}
		$itemCount++;
	}
} ?>
