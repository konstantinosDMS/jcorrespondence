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
				<th width="3%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="5%">
					<?php echo JText::_('UserId:'); ?>
                                </th>          
				<th width="10%" class="title">
					<?php echo JText::_('User Name:'); ?>
				</th>
                        	<th width="10%">
					<?php echo JText::_('Role:'); ?>
				</th>                                
			</tr>
</thead>
