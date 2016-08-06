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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$doc = JFactory::getDocument();
$doc->addstylesheet(JURI::root().'/media/com_jcorrespondence/css/jcorrespondence.css');

$user	= JFactory::getUser();
$userId=$user->get('id');

 
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'correspondence.cancel' || document.formvalidator.isValid(document.id('correspondence-form'))) {	
			Joomla.submitform(task, document.getElementById('correspondence-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jcorrespondence&layout=edit&postid='.(int)$this->item->postid); ?>" method="post" name="adminForm" id="correspondence-form" class="form-validate">
	<div class="control-group">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_JCORRESPONDENCE_NEW_CORRESPONDENCE') : JText::sprintf('COM_JCORRESPONDENCE_EDIT_CORRESPONDENCE', $this->item->postid); ?></legend>
				
                                <?php echo $this->form->getLabel('postid'); ?>
				<?php echo $this->form->getInput('postid'); ?>
                            
                                <?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?>
                            
                                <li><?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?></li>
                                
                                <?php echo $this->form->getLabel('description'); ?>
				<?php echo $this->form->getInput('description'); ?>                      
		</fieldset>
	</div>
	<div class="control-group">
		<input type="hidden" name="task" value="" />                
                <?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
</form>
