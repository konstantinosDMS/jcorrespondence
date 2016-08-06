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
JLoader::register('JCorrespondenceModelUsers',JPATH_COMPONENT.'/models/users.php');
/**
 * View class for a list of inbox.
 *
 * @package	Joomla.Administrator
 * @subpackage	com_jcorrespondence
 * @since	3.6.0
 */
class JCorrespondenceViewOutbox extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
        
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->pagination	= $this->get('Pagination');
                 $this->moreItems        = $this->get('MoreItems');
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
	 * @since 3.6.0
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/jcorrespondence.php';

		$state	= $this->get('State');
		$canDo	= JCorrespondenceHelper::getActions($state->get('filter.category_id'));
		$user	= JFactory::getUser();
                $model = JModelList::getInstance('JCorrespondenceModelUsers');        
                $numberUsers= (int)$model->getUsers();
                        
		JToolBarHelper::title(JText::_('COM_JCORRESPONDENCE_MANAGER_OUTBOX_CORRESPONDENCES'), 'envelope outbox');
		
                if ((count($user->getAuthorisedCategories('com_jcorrespondence', 'core.create')) > 0) && ($numberUsers>1)) {
			JToolBarHelper::addNew('correspondence.add');
		}
                
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('correspondence.edit');
                        JToolBarHelper::divider();
                        JToolBarHelper::custom('inbox.display','inbox','inbox','Inbox',false);
                        JToolBarHelper::custom('outbox.display','outbox','outbox','Outbox',false);           
                        JToolBarHelper::custom('reply.edit','answearable','Reply','Reply',false);
                        JToolBarHelper::custom('search.display','search','search_correspondence','Search',false);
                        JToolBarHelper::divider();
                }
                
		JToolBarHelper::help('JHELP_COMPONENTS_JCORRESPONDENCE_LINKS_EDIT',true);
	}
}
        
