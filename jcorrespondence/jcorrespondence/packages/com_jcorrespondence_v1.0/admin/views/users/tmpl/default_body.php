<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: domain.php 30 2016-07-25 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
**/
defined('_JEXEC') or die('Restrocted Access');

echo '<tbody>';

foreach ($this->items as $i => $item) :
   	
?>
	<tr class="row<?php echo $i % 2; ?>">
            <td class="center">
                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
            </td>
            <td class="center">
               <?php echo $this->escape($item->id); ?>
            </td>
            <td class="center">
                <?php echo $this->escape($item->name); ?>
            </td>
            <td class="center">
                <?php echo $this->escape($item->title); ?>
            </td>
	</tr>
        
<?php endforeach; ?>
        
<?php echo '</tbody>'; ?>

