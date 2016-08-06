<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: domain.php 30 2016-07-25 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
**/
defined('_JEXEC') or die('Restricted Access');
?>

<thead>
			<tr>
				<th width="5%" class="nowrap center">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="5%" class="nowrap center">
					<?php echo JText::_('PostId:'); ?>
				</th>
                                <th width="5%" class="nowrap center">
					<?php echo JText::_('expand(+)/collapse(-):'); ?>
				</th>
				<th width="10%" class="nowrap" >
					<?php echo JText::_('Post Title:'); ?>
				</th>
                                <th width="10%" class="nowrap center">
					<?php echo JText::_('Category Title:'); ?>
				</th>
				<th width="10%" class="nowrap center">
					<?php echo JText::_('Posted At:'); ?>
				</th>
				<th width="15%" class="nowrap center"> 
					<?php echo JText::_('Poster Name:'); ?>
				</th>
				<th width="10%" class="nowrap center">
					<?php echo JText::_('Status:'); ?>
				</th>                                
			</tr>
</thead>
