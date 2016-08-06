<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: treenode.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('JPATH_BASE') or die;

/**
 * This is our custom registration plugin class for the JCorrespondence component. It updates the users status field.
 *
 * @package     Joomla.Plugins
 * @subpackage  User.MyRegistration
 * @since       3.6.0
 */

class plgContentUpdateCorrespondenceStatus extends JPlugin
{		
	/**
	 * Method to handle the "onContentPrepareForm" event.
	 * 	 *
	 * @return  bool
	 * 
	 * @since   1.5.0
	 */
	public function  onContentPrepareForm($form, $data)
	{            
                $option	= JRequest::getCmd('option');              
                $view = JRequest::getCmd('view');
                $layout = JRequest::getCmd('layout');
                $postId = JRequest::getInt('postid');
                $user	= JFactory::getUser();
                $userId=$user->get('id');
                        
                // Get the dbo
	        $db = JFactory::getDbo(); 
                
                 // Initialize the query object
                $query = $db->getQuery(true);
                
                if ($option == 'com_jcorrespondence')
	        {
                        if ( ( ($view=='correspondence')|| ($view=='reply')) && ($layout=='edit') ){                     
                        $query->update('#__correspondence_users');
                        $query->set('status = 1');
                        $query->where('postid = '.(int)$postId);
                        $query->where('userid= '.(int)$userId);
                        $query->where("box='0'");
                                             
                        $db->setQuery($query);
                        
                        //echo $db->getAffectedRows();
                        
                        $db->execute();
                        if ($db->getErrorNum()) {
                            echo $db->getErrorMsg();
                            return false;
                        }
                        $query->clear();
                    }
                }               
		return true;
	}	
}
