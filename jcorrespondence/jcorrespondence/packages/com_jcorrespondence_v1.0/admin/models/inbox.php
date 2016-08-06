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

/**
 * Methods supporting a list of jcorrespondence records.
 *
 * @package	Joomla.Administrator
 * @subpackage	com_jcorrespondence
 * @since	3.6.0
 */
class JcorrespondenceModelInbox extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	3.6.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'parent','a.parent',
                               	'title', 'a.title',
				'alias', 'a.alias',
			        'postid', 'a.postid','b.postid',				
				'group_id','a.group_id',
                                'published','a.published',
                                'access','a.access',
                                'params','a.params',
                                'language','a.language',
                                'created','a.creeated','b.created',
                                'created_by','a.created_by',
                                'created_by_alias','a.created_by_alias',
                                'modified','a.modified',
                                'modified_by','a.modified_by',
                                'publish_up','a.publish_up',
                                'publish_down','a.publish_down',
                                'checked_out','a.checked_out',
                                'checked_out_time','a.checked_out_time',
                                'catid','a.catid',
                                'url','a.url',
                                'status','a.status','b.status',
                                'decription','a.description',
                                'userid','b.userid',
                                'box','b.box',    
			);
		}
                $this->session = JFactory::getSession();
		parent::__construct($config);
	}


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since  3.6.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

                $expandId = $this->getUserStateFromRequest($this->context.'expand_id', 'expandId', '');
		               
                if ($expandId) {
                 $this->session->set( (int)$expandId, true );
                 JRequest::setVar('expandId', ''); 
                }
                
                $collapseId = $this->getUserStateFromRequest($this->context.'collapse_id', 'collapseId', '');
                
                if ($collapseId){
                   $this->session->set( (int)$collapseId,false );
                   JRequest::setVar('collapseId','');                   
                }
                
                // Load the parameters.
		$params = JComponentHelper::getParams('com_jcorrespondence');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.created', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	3.6.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	3.6.0
	 */
	        
        protected function getListQuery($postId=0)
	{
		// Create a new query object.
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
                $userId=$user->get('id');
                
		// Select the required fields from the table.
              
                $query->select('a.parent,a.title,a.alias,a.postid,a.group_id,a.published,a.access,a.params,'
                        .'a.language,a.created,a.created_by,a.created_by_alias,a.children,'
                        .'a.modified,a.modified_by,a.publish_up,a.publish_down,a.checked_out,'
                        .'a.checked_out_time,a.catid,a.url,a.description');
                
		$query->from($db->quoteName('#__correspondence_header').' AS a');
         
                // Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.created_by');

		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

                // Join over the correspondence_users
		$query->select('b.status as status');
                
		$query->join('INNER',$db->quoteName('#__correspondence_users').' AS b on b.postid=a.postid');
		
                $query->where('b.box=\'0\''); // 1->inbox, 0->outbox
                
                $query->where('b.userid='.(int)$userId);
                
                $query->where('a.parent='.(int)$postId);

                $query->where('a.status=1'); // 1->sended to users, 0-> not sended to users
                
                $query->order($db->escape('a.postid asc'));

		return $query;             
	}
        
        /**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems($postId=0)
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$query = $this->_getListQuery($postId);

		try
		{
			$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}
        
          /**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getMyItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$query = $this->_getMyListQuery();

		try
		{
			$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}
        
        /**
	 * Method to cache the last query constructed.
	 *
	 * This method ensures that the query is constructed only once for a given state of the model.
	 *
	 * @return  JDatabaseQuery  A JDatabaseQuery object
	 *
	 * @since   12.2
	 */
	protected function _getListQuery($postId=0)
	{
		// Capture the last store id used.
		static $lastStoreId;

		// Compute the current store id.
		$currentStoreId = $this->getStoreId();

		// If the last store id is different from the current, refresh the query.
		if ($lastStoreId != $currentStoreId || empty($this->query))
		{
			$lastStoreId = $currentStoreId;
			$this->query = $this->getListQuery($postId);
		}

		return $this->query;
	}
        
                /**
	 * Method to cache the last query constructed.
	 *
	 * This method ensures that the query is constructed only once for a given state of the model.
	 *
	 * @return  JDatabaseQuery  A JDatabaseQuery object
	 *
	 * @since   12.2
	 */
	protected function _getMyListQuery()
	{
		// Capture the last store id used.
		static $lastStoreId;

		// Compute the current store id.
		$currentStoreId = $this->getStoreId();

		// If the last store id is different from the current, refresh the query.
		if ($lastStoreId != $currentStoreId || empty($this->query))
		{
			$lastStoreId = $currentStoreId;
			$this->query = $this->getMyListQuery();
		}

		return $this->query;
	}
        
        protected function getMyListQuery()
	{
		// Create a new query object.
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
                $userId=$user->get('id');
                
		// Select the required fields from the table.
		$query->select('COUNT(*) AS count');
                
		$query->from($db->quoteName('#__correspondence_users').' AS b');
         
                $query->where('b.box=1');
                
                $query->where('b.userid='.(int)$userId);
                
                $query->where('b.status=0'); // 0 -> read ,  1 -> unread

		return $query;            
	}
        
        public function getFlag($postId=0){
                
                // Create a new query object.
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
                $userId=$user->get('id');
                
                $query->select('head.parent');
                $query->from('#__correspondence_header AS head');
                $query->where('head.parent='.(int)$postId);
                $query->where('head.postid IN (SELECT users.postid
					FROM #__correspondence_users as users 
					WHERE users.userid='.(int)$userId.' AND users.box=\'0\')');
                $this->getDbo()->setQuery($query);
                $array = $this->getDbo()->loadRowList();  
                $query->clear();
                return $array;
        }
        
        public function getMoreItems(){
                
            // Load the list items.
	    $query = $this->_get_MoreListQuery();
            if ($query!=false) $items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));
            else return false;

	    // Check for a database error.
	    if ($this->_db->getErrorNum())
	    {
		$this->setError($this->_db->getErrorMsg());
		return false;
	    }

	    // Add the items to the internal cache.
	    return $items;
        }

        protected function _get_MoreListQuery()
        {
                $this->query = $this->get_More_ListQuery();
                return $this->query;
        }

        protected function get_More_ListQuery()
        {

                    // Create a new query object.
                    $db	= $this->getDbo();
                    $query	= $db->getQuery(true);
                    $user	= JFactory::getUser();
                    $userId=$user->get('id');

                    // Select the required fields from the table.
                    $query->select('head.*');
                    $query->from('#__correspondence_header AS head');

                    // Join over the users for the checked out user.
                    $query->select('c.title as category_title');
                    $query->join('LEFT', '#__categories AS c ON c.id=head.catid');

                    // Join over the correspondence users.
                    //$query->select('users.status as status');
                    $query->join('LEFT', '#__correspondence_users AS users ON users.postid = head.postid');
                     // Join over the users.
                    $query->select('u.name AS editor');
                    $query->join('LEFT', '#__users AS u ON u.id=head.created_by');

                    $query->where('users.box=\'0\'');
                    $query->where('head.status=1');                
                    $query->where('users.userid='.(int)$userId);
                    $query->order('head.children desc');
                    //echo $query;
                    return $query;		
        }
}
