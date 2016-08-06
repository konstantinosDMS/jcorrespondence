<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: default.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');

$doc = JFactory::getDocument();
$doc->addstylesheet(JURI::root().'/media/com_jcorrespondence/css/jcorrespondence.css');

$user		= JFactory::getUser();
$userId		= $user->get('id');

?>

<form action="<?php echo JRoute::_('index.php?option=com_jcorrespondence&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JCORRESPONDENCE_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>

	</fieldset>
    <div style="margin-top:5%">
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>		
				<th width="5%">
					<?php echo JText::_('PostId:'); ?>
                                </th>
				<th width="10%" class="title">
					<?php echo JText::_('Post Title:'); ?>
				</th>
                                <th width="10%">
					<?php echo JText::_('Category Title:'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('Posted At:'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
	
		?>
			<tr class="row<?php echo $i % 2; ?>">
				
                                <td class="center">
					<?php echo (int) $item->postid; ?>
				</td>                              
				<td class="center">
 
    <?php
            echo '<a href="'. JRoute::_('index.php?option=com_jcorrespondence&task=reply.edit&postid='.(int) $item->postid) . '\"</a>';
            echo $this->escape($item->title);
            echo '</a>';
    ?>                      
				</td>			
				<td class="center">
					<?php echo $this->escape($item->category_title); ?>
				</td>		
			        <td class="center">
					<?php echo $this->escape($item->posted); ?>
				</td>	                       
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
    </div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
