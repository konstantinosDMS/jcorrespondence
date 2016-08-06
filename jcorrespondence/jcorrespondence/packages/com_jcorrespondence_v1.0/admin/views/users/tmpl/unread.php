<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: domain.php 30 2016-07-25 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
**/

// no direct access
defined('_JEXEC') or die;
$doc = JFactory::getDocument();

$doc->addstylesheet(JURI::root().'/media/com_jcorrespondence/css/jcorrespondence.css');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{           
                Joomla.submitform(task, document.getElementById('adminForm'));        
	}       
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jcorrespondence&view=users&postid='.(int)JRequest::getInt('postid')); ?>" method="post" name="adminForm" id="adminForm">

	<div class="clr"> </div>

	<table class="adminlist">
		<?php echo $this->loadTemplate('headUnRead');?>
		<?php echo $this->loadTemplate('foot');?>
		<?php echo $this->loadTemplate('bodyUnRead');?>              
	</table>

	<div>
		<input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
