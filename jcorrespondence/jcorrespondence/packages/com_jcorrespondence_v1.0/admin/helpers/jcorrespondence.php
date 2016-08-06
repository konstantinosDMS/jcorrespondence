<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: jcorrespondence.php 30 2016-07-25 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// No direct access
defined('_JEXEC') or die;


class JCorrespondenceHelper
{

	public static function addSubmenu($vName = 'inbox')
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_JCORRESPONDENCE_SUBMENU_CORRESPONDENCES'),
			'index.php?option=com_jcorrespondence&view=inbox',
			$vName == 'inbox'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_JCORRESPONDENCE_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_jcorrespondence',
			$vName == 'categories'
		);
	}


	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId)) {
			$assetName = 'com_jcorrespondence';
			$level = 'component';
		} else {
			$assetName = 'com_jcorrespondence.category.'.(int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_jcorrespondence', $level);

		foreach ($actions as $action) {
			$result->set($action->name,$user->authorise($action->name, $assetName));
		}
		return $result;
	}
}