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


class JCorrespondenceViewUsers extends JViewLegacy
{
    
	protected $items;
	protected $pagination;
	protected $state;
        protected $users;
        protected $layout;
        /**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');                
		$this->items		= (array)$this->get('Items');
                $this->pagination	= $this->get('Pagination');
                $this->users            =  $this->get('UnRead');
                $this->layout           = JRequest::getCmd('layout');
                  
                if ($this->layout == 'unread' && empty($this->users)){
                    $controller = JControllerLegacy::getInstance('Users','JCorrespondenceController');
                    $controller->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', 0));
                    $controller->setMessage($controller->getError(), 'error');
		    $controller->setRedirect(
                        JRoute::_(
                            'index.php?option=com_jcorrespondence&view=inbox&layout=default',false
                        )
		    );
			return false;
                }
                
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
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/jcorrespondence.php';

		$state	= $this->get('State');
		$canDo	= JCorrespondenceHelper::getActions($state->get('filter.category_id'));
		
                JToolBarHelper::title(JText::_('COM_JCORRESPONDENCE_MANAGER_USERS') ,'user');
                
                if ($canDo->get('core.edit')) {
                    if ($this->getLayout()!='unread') JToolBarHelper::custom('users.select','users','users','Select Users',false);
                }
                
              	if (empty($this->item->id)) {
			JToolBarHelper::cancel('users.cancel');
		}
		else {
			JToolBarHelper::cancel('users.cancel', 'JTOOLBAR_CLOSE');
		}
                
                JToolBarHelper::divider();
		
                JToolBarHelper::help('JHELP_COMPONENTS_JCORRESPONDENCE_LINKS_EDIT',true);
	}
}
