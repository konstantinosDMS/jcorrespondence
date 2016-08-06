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
for ($i=0;$i<count($this->users);$i++){     
?>
        <tr class="row<?php echo $i % 2; ?>">
            <td class="center">
               <?php echo $this->escape($this->users[$i]['id']); ?>
            </td>
            <td class="center">
                <?php echo $this->escape($this->users[$i]['name']); ?>
            </td>
            <td class="center">
                <?php echo $this->escape($this->users[$i]['title']); ?>
            </td>
	</tr>
<?php
    }
echo '</tbody>';
?>


