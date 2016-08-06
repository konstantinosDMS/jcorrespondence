<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: view.html.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;


JLoader::register('treenode',JPATH_COMPONENT.'/myclasses/treenode.php');

class JCorrespondenceViewSearch extends JViewLegacy
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
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

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
		$user	= JFactory::getUser();
                
                JToolBarHelper::title(JText::_('COM_JCORRESPONDENCE_MANAGER_SEARCH_CORRESPONDENCES'), 'jcorrespondence');
              		
             	if ($canDo->get('core.edit')) {
			JToolBarHelper::cancel('search.cancel');
                }
                
		JToolBarHelper::help('JHELP_COMPONENTS_JCORRESPONDENCE_LINKS_EDIT',true);
	}
}
