<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: domain.php 30 2016-07-25 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
**/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


 class JCorrespondenceControllerUsers extends JControllerForm{
  
     public function select(){
         $cid = JRequest::getVar('cid', array(), 'post', 'array');
         $app = JFactory::getApplication();
         $postId = $app->getUserState('correspondence.id');
         $myModel = $this->getModel();         
              
         $tmpCid = $myModel -> clearArray($cid,$postId);
      
         for ($i=0;$i<count($tmpCid);$i++){ 
           $userId = $tmpCid[$i];            
           $user = JFactory::getUser((int)$userId);
                  
           if ( is_object($user) ) {      
               if ($myModel -> insert($userId,$postId,0,0) ){    
                    continue;
               }
           else {
 			$this->setError(JText::sprintf('J_CORRESPONDENCE_ERROR', $myModel->getError()));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=inbox&layout=default'));
			break;
                }     
            }
         }
   
        if (!empty($myModel->clearArray(array(JFactory::getUser()->get('id')),$postId,1)[0])){
          if (!$myModel->insert($myModel->clearArray(array(JFactory::getUser()->get('id')),$postId,1)[0],$postId,1,0)){
             $this->setError(JText::sprintf('J_CORRESPONDENCE_ERROR', $myModel->getError()));
             $this->setMessage($this->getError(), 'error');
             $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=inbox&layout=default' ));
          }
        }
 
        if (!$myModel->update($postId)){       
                $this->setError(JText::sprintf('J_CORRESPONDENCE_ERROR', $myModel->getError()));
	        $this->setMessage($this->getError(), 'error');				
        }
        
        $parentID =(int) $myModel->checkLastParentId($postId); 
        
        if ($parentID > 0){
             $this->getModel('Reply','JCorrespondenceModel')->checkin($parentID);
             if (!$myModel->updateChildren((int)$parentID)){
                    $this->setError(JText::sprintf('J_CORRESPONDENCE_ERROR', $myModel->getError()));
                    $this->setMessage($this->getError(), 'error');				
            }      
        }
        
        $this->setRedirect(
                JRoute::_('index.php?option=' . $this->option . '&view=inbox&layout=default' , false));
        
        $app->setUserState('correspondence.id',0);
    }
         
         
        public function UnRead(){
            $numberItems = 0;
            $postId = JRequest::getInt('postid');
            $myModel = JModelList::getInstance('Outbox','JcorrespondenceModel');
            $numberItems = $myModel->get_myItems($postId);
            $numberItems = ((int)($numberItems[0]->count));
          
            if ($numberItems===0) {
               
                // Somehow the person just went to the form and tried to save it. We don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=inbox&layout=default'
					. $this->getRedirectToListAppend(), false
				)
			);
			return false;
            }
          
            $this->setRedirect(
                 JRoute::_('index.php?option=' . $this->option . '&view=users&layout=unread&postid='.(int)$postId , false)
            );
}
        
        public function cancel($key = null)
	{
            $this->view_list = 'Inbox'; 
            
            $myModel = $this->getModel();
            $myModel->deleteCorrespondence();
        
            $this->setRedirect(
                    JRoute::_(
                       'index.php?option=' . $this->option . '&view=' . $this->view_list.'&layout=default'
                       . $this->getRedirectToListAppend(), false
                    )
            );
            
            return true;
        }

       
 }