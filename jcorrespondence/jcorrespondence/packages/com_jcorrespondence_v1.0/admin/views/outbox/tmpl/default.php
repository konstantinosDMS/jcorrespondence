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
$doc = JFactory::getDocument();

JLoader::register('treenode',JPATH_COMPONENT.'/myclasses/treenode.php');

$doc->addstylesheet(JURI::root().'/media/com_jcorrespondence/css/jcorrespondence.css');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{            
		if (task == 'reply.edit' ){
                  if ($$("input[type=checkbox]:checked").length<1) {
                      alert('<?php echo $this->escape(JText::_('JGLOBAL_CHECKBOXES_SELECTION'));?>'); 
                      exit();
                  }  
                } 
                Joomla.submitform(task, document.getElementById('adminForm')); 
	}       
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jcorrespondence&view=inbox'); ?>" method="post" name="adminForm" id="adminForm">

	<div class="clr"> </div>

	<table class="table table-striped" id="userList">
		<?php echo $this->loadTemplate('head');?>
		<?php echo $this->loadTemplate('foot');?>
		<?php echo $this->loadTemplate('body');?>
                
	</table>

	<div>
              
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
