<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: domain.php 30 2016-07-25 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
**/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class JCorrespondenceModelCorrespondence extends JModelAdmin
{
	protected $text_prefix = 'COM_JCORRESPONDENCE';

	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return ;
			}
			$user = JFactory::getUser();

			if ($record->catid) {
				return $user->authorise('core.delete', 'com_jcorrespondence.category.'.(int) $record->catid);
			}
			else {
				return parent::canDelete($record);
			}
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_jcorrespondence.category.'.(int) $record->catid);
		}
		else {
			return parent::canEditState($record);
		}
	}

	public function getTable($type = 'Correspondence', $prefix = 'JCorrespondenceTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_jcorrespondence.correspondence', 'correspondence', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('correspondence.id')) {
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		} else {
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_jcorrespondence.edit.domain.data', array());

		if (empty($data)) {
			$data = $this->getItem();
			// Prime some default values.
			if ($this->getState('correspondence.id') == 0) {
                           
				$app = JFactory::getApplication();
				$data->set('catid', JRequest::getInt('catid', $app->getUserState('com_jcorrespondence.correspondence.filter.category_id')));
			}
		}
		return $data;
	}

	public function getItem($pk = null)
	{
	        $item = parent::getItem($pk); 		
                //var_dump($item);
		return $item;
	}

	protected function prepareTable(&$table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = '.(int) $table->catid;
		return $condition;
	}        

        protected function populateState(){
                parent::populateState();
                
 		$table = $this->getTable();
		$key = $table->getKeyName();

		// Get the pk of the record from the request.
		$pk = JFactory::getApplication()->input->getInt($key);
                $this->setState($this->getName() . '.id', $pk);
                
		$app = JFactory::getApplication();
                $app->setUserState('correspondence.id',$pk);               

		// Load the parameters.
		$value = JComponentHelper::getParams($this->option);
		$this->setState('params', $value);
	}
}
