<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: controller.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;


class JCorrespondenceController extends JControllerLegacy
{
	    
        protected $default_view = 'Inbox';
      
        
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/jcorrespondence.php';

       
		$view		= JRequest::getCmd('view', 'inbox');
		$layout 	= JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('postid');
                $model1         = $this->getModel('Correspondence','JCorrespondenceModel');
                $model2         = $this->getModel('Users','JCorrespondenceModel');
                $numberOfUsers = (int)$model2->getUsers();
                
                
                        
                $app = JFactory::getApplication();
                
                $postId = $app->getUserState('correspondence.id') ;           
                $flag = $app->getUserState('correspondence.flag');
              
                if ($view != 'users'){
		        // Load the submenu.
	        	JCorrespondenceHelper::addSubmenu(JRequest::getCmd('view', 'inbox'));
                }
                
		// Check for edit form.
		if ($view == 'correspondence' && $layout == 'edit' && !$this->checkEditId('com_jcorrespondence.edit.correspondence', $id)) {
			
                        // Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_jcorrespondence&view=inbox&layout=default', false));

			return false;
		}
                
     
                
                if ($view == 'correspondence' && $layout == 'edit' && $flag){
                    $app->setUserState('correspondence.flag',false);
                    $model1->checkin((int)$id);
                    $this->releaseEditId('com_jcorrespondence.edit.correspondence' , (int)JRequest::getInt('postid'));
                    $this->setRedirect(
			JRoute::_('index.php?option=com_jcorrespondence&view=users&layout=default', false)
                    );
                }

                // Check for edit form.
		if ($view == 'reply' && $layout == 'edit' && !$this->checkEditId('com_jcorrespondence.edit.reply', $id)) {
		
                        // Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_jcorrespondence&view=inbox&layout=default', false));

			return false;
		}
              
                if ($view == 'reply' && $layout == 'edit' && $flag){              
                    $app->setUserState('correspondence.flag',false);
                    $this->setRedirect(
			JRoute::_('index.php?option=com_jcorrespondence&view=users&layout=default', false)
                    );
                }
               
              
                if ( $view == 'users'  &&  (int)$postId == 0 && $layout!='unread')  {	
                        // Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_jcorrespondence&view=inbox&layout=default', false));

			return false;
		}
                else if ($view == 'users' && $layout!='unread' && $numberOfUsers==1){
                        // Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::_('COM_JCORRESPONDENCE_ERR_TABLES_NAME'));
                        
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_jcorrespondence&view=inbox&layout=default', false));

			return false;
                }
                
                $model2->deleteCorrespondence();
                
    		parent::display();

		return $this;
	}
}
