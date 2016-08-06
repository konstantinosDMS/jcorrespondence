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
jimport('joomla.application.component.modellist');

class JCorrespondenceModelUsers extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
                            'id','c.id',
                            'title','ug.title',
                            'name','c.name'
			);
		}
                
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');               
            
                $pk = JRequest::getInt('postid');
		$app->setUserState($this->getName().'.model.id', (int)$pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_jcorrespondence');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.userid', 'asc');
	}
	
	protected function getStoreId($id = '')
	{
		return parent::getStoreId($id);
	}
	
	protected function getListQuery($parentId=0)
	{
		// Create a new query object.
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
                $userId=$user->get('id');
                
		// Select the required fields from the table.
		$query->select('c.name as name,ug.title as title,c.id as id');
		$query->from($db->quoteName('#__users').' AS c');
         
                // Join over the users for the checked out user.
		$query->join('LEFT', '#__user_usergroup_map AS ugm ON c.id = ugm.user_id');

                 // Join over the users for the checked out user.
		$query->join('LEFT', '#__usergroups AS ug ON ugm.group_id=ug.id');
                
                $query->where('c.id!='.$userId);
                
		$query->where('(ugm.group_id=7 || ugm.group_id=8)');
                         
                //echo $query.'<br />';
		return $query;
	} 
        
        public function insert($userId=0,$postId=0,$box=0,$status=0){
            
            $date	= JFactory::getDate();
             
            // Initialize the query object
            $query = $this->getDbo()->getQuery(true);
            $query->insert('#__correspondence_users');
            $query->set('userid='.(int)$userId);
            $query->set('postid='.(int)$postId);
            $query->set('created='.$this->getDbo()->quote($date->toSql()));
            $query->set('box=\''.(int)$box.'\'');
            $query->set('status='.(int)$status);
            
            $this->getDbo()->setQuery($query);
  
            if ($this->getDbo()->query()){
                return true;
            }
            else{
                $this->setError(JText::_('COM_JCORRSPONDENCE_INSERT_USERS'));
                return false;
            }
        
        }
        
        public function update( $postId ){
            
            $query = $this->getDbo()->getQuery(true);
            $query->select('*');
            $query->from('#__correspondence_header');
            $query->where('postid='.(int)$postId);

            //var_dump($query);                       
            $this->getDbo()->setQuery($query);
            $array = $this->getDbo()->loadAssocList();                        
            $query->clear();
               
            if ($array[0]['postid']==$postId){
                $query->update('#__correspondence_header');
                $query->set('status=1');
                $query->set('url=\'index.php?option=com_jcorrespondence&view=correspondence&layout=edit&postid='.$postId.'\'');
                $query->where('postid='.(int)$postId);
                $this->getDbo()->setQuery($query);
               
                if (!$this->getDbo()->query()) {
                    $this->setError(JText::_('COM_JCORRSPONDENCE_UPDATE_HEADER'));
                    return false;
                }
            }
           
            return true;        
        }
        
        public function updateChildren( $postId=0 ){
            
            $query = $this->getDbo()->getQuery(true);
            $query->select('*');
            $query->from('#__correspondence_header');
            $query->where('postid='.(int)$postId);

            //var_dump($query);                       
            $this->getDbo()->setQuery($query);
            $array = $this->getDbo()->loadAssocList();                        
            $query->clear();
               
            if ($array[0]['postid']==$postId){
                $query->update('#__correspondence_header');
                $query->set('children=1');
                $query->where('postid='.(int)$postId);
                $this->getDbo()->setQuery($query);
               
                if (!$this->getDbo()->query()) {
                    $this->setError(JText::_('COM_JCORRSPONDENCE_UPDATE_HEADER'));
                    return false;
                }
            }
           
            return true;        
        }
        
        public function clearArray($cid=array(),$postId=0){
             // Initialize the query object
            $tmp = array();
            
            $query = $this->getDbo()->getQuery(true);
            $query->select('userid as uid');
            $query->from('#__correspondence_users');
            $query->where('postid='.(int)$postId);
       
            //var_dump($query);                       
            $this->getDbo()->setQuery($query);
            $array = $this->getDbo()->loadAssocList();
            $query->clear();
           
            if (!empty($array)){
                for ($i=0;$i<count($array);$i++){
                    $array1[] = $array[$i]["uid"];
                }

                for ($i=0;$i<count($cid);$i++){               
                    if (in_array($cid[$i],$array1)){
                        $cid[$i]=null;
                    }  
                }

                for ($i=0;$i<count($cid);$i++){
                    if ($cid[$i]!=null) $tmp[] = $cid[$i];
                }
                return $tmp; 
            }
            return $cid;
    }
    
    public function getUnRead(){
        $postId = JRequest::getInt('postid');       
        
        $query = $this->getDbo()->getQuery(true);
        $query->select('users.id as id, users.name as name , ug.title as title');
        $query->from('#__users as users');
        
        $query->join('LEFT','#__correspondence_users AS cu ON cu.userid=users.id');
        $query->join('LEFT','#__user_usergroup_map AS ugm ON ugm.user_id=cu.userid');
        $query->join('LEFT','#__usergroups AS ug ON ug.id=ugm.group_id');
        $query->where('box=1');
        $query->where('status=0');
        $query->where('postid='.(int)$postId);
        $query->where('(ugm.group_id=7 || ugm.group_id=8)');
                        
        $this->getDbo()->setQuery($query);
        $array = $this->getDbo()->loadAssocList();
        $query->clear();
        
        return $array;
    }
    
    public function deleteCorrespondence(){
        $query = $this->getDbo()->getQuery(true);
        $query->delete();
        $query->from('#__correspondence_header');
        $query->where('status = 0');
        
        $this->getDbo()->setQuery($query);
         
        if (!$this->getDbo()->query()){
                     $this->setError(JText::_('COM_JCORRSPONDENCE_UPDATE_HEADER'));
                     return false;
        } 
       
        $query->clear();
                  
        return true;
    }  
    
    public function checkLastParentId($postId=0){
        $query = $this->getDbo()->getQuery(true);
        $query->select('parent');
        $query->from('#__correspondence_header');

        $query->where('postid='.(int)$postId);
                        
        $this->getDbo()->setQuery($query);
        $parentId = $this->getDbo()->loadResult();
        $query->clear();
        
        return $parentId;
    }
    
    public function getUsers(){
        $query = $this->getDbo()->getQuery(true);
        $query->select('count(*) as count');
        $query->from('#__users as users');
        
        $query->join('LEFT','#__correspondence_users AS cu ON cu.userid=users.id');
        $query->join('LEFT','#__user_usergroup_map AS ugm ON ugm.user_id=cu.userid');
        $query->where('(ugm.group_id=7 || ugm.group_id=8)');
                        
        $this->getDbo()->setQuery($query);
        $number = $this->getDbo()->loadResult();
        $query->clear();
        
        return $number;
    }
}