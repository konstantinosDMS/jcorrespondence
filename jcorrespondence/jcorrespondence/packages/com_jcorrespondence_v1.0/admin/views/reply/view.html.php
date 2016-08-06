<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: domain.php 30 2016-07-25 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
**/

defined('_JEXEC') or die;

/**
 * View to edit a Correspondence.
 *
 * @package	Joomla.Administrator
 * @subpackage	com_jcorrespondence
 * @since	3.6.0
**/
class JCorrespondenceViewReply extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	        = $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	3.6.0
	**/
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
                JToolBarHelper::title(JText::_('COM_JCORRESPONDENCE_MANAGER_JCORRESPONDENCE'), 'envelope inbox');
         	JToolBarHelper::apply('reply.apply');
		JToolBarHelper::cancel('reply.cancel');
		JToolBarHelper::help('JHELP_COMPONENTS_JCORRESPONDENCE_LINKS_EDIT',true);
	}
}
