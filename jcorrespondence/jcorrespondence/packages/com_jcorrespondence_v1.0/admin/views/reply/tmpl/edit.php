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

$app = JFactory::getApplication(); 
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'reply.cancel' || document.formvalidator.isValid(document.id('reply-form'))) {	
			Joomla.submitform(task, document.getElementById('reply-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jcorrespondence&view=reply&layout=edit&postid='.(int)$this->item->postid); ?>" method="post" name="adminForm" id="reply-form" class="form-validate">
	<div class="control-group">
                <fieldset class="adminform">
			<legend><?php echo 'Message';  ?></legend>
			<ul class="adminformlist">
                            
                            <?php echo $this->form->getLabel('parent'); ?>
                            <?php 
                               $this->form->setValue('parent','',(int)$this->item->postid);
                               echo $this->form->getInput('parent');
                            ?>
                            <li><label for="title">Title:</label><input type="text" name="" value="<?php echo $this->item->title; ?>" disabled="true" /></li>
                            <li><label for="category">Category:</label><?php echo $this->form->getInput('catid1'); ?></li> 
                            <li><label for="message">Message:</label><textarea disabled="true" name="" rows="10"><?php echo $this->item->description; ?></textarea></li>   
                            
                        </ul>
                </fieldset> 
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_JCORRESPONDENCE_NEW_REPLY') : JText::sprintf('COM_JCORRESPONDENCE_EDIT_REPLY', $this->item->postid); ?></legend>
				
                                <?php echo $this->form->getLabel('postid'); ?>
				<?php 
                                    $this->form->setValue('postid','',0); 
                                    echo $this->form->getInput('postid'); 
                                ?>
                            
                                <?php echo $this->form->getLabel('title'); ?>
				<?php $this->form->setValue('title','',''); 
                                      echo $this->form->getInput('title'); ?>
                            
                                <li><?php echo $this->form->getLabel('catid'); ?>
				    <?php echo $this->form->getInput('catid'); ?></li>
                                
                                <?php echo $this->form->getLabel('description'); ?>
				<?php $this->form->setValue('description','',''); 
                                 echo $this->form->getInput('description'); ?>                      
		</fieldset>
	</div>
	<div class="control-group">
		<input type="hidden" name="task" value="" />                
                <?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
</form>
