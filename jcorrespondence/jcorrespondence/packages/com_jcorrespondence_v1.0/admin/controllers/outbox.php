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

jimport('joomla.application.component.controlleradmin');

/**
 * Inbox list controller class.
 *
 * @package	Joomla.Administrator
 * @subpackage	com_jcorrespondence
 * @since	3.6.0
 */
class JCorrespondenceControllerOutbox extends JControllerAdmin{
    public function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect(JRoute::_('index.php?option=com_jcorrespondence&view=outbox&layout=default', false));
    }
}
