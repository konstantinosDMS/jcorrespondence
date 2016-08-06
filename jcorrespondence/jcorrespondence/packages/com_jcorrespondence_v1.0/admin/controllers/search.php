<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: search.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

 class JCorrespondenceControllerSearch extends JControllerLegacy{
    public function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect(JRoute::_('index.php?option=com_jcorrespondence&view=Search', false));
    }
    
    public function cancel(){
        $this->setRedirect(JRoute::_('index.php?option=com_jcorrespondence&view=inbox&layout=default', false));
    }
 }

