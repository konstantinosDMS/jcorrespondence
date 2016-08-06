<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: search.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class JCorrespondenceModelSearch extends JModelList
{

	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'posted', 'a.posted',
                                'name','uc.name',
                                'title','c.title',
                                'status','b.status',
                                'catid','a.catid'
			);
		}

		parent::__construct($config);
	}



	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_jcorrespondence');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.title', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}


	protected function getListQuery()
	{
		// Create a new query object.
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
                $userId=$user->get('id');
   
		// Select the required fields from the table.
		$query->select('DISTINCT a.postid as postid, a.title as title, a.created as posted');
		$query->from($db->quoteName('#__correspondence_header').' AS a');
                
                //Join over ergo3_correspondence_users
		
		$query->join('INNER',$db->quoteName('#__correspondence_users').' AS b on b.postid=a.postid');
                
		// Join over the categories.
		$query->select(' c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

                $query->where('a.status=1');
   
                // Filter by search in title
		$search = $this->getState('filter.search');
                
                if (!empty($search)) {
                    $search = $db->Quote('%'.$db->escape($search, true).'%');
                    $query->where('(a.title LIKE '.$search.') OR MATCH(a.description) AGAINST ('.$search.' IN BOOLEAN MODE)');                
	        }

                $query->order($db->escape('a.postid asc'));
                //echo $query.'<br />';
		return $query;   
	}
}
