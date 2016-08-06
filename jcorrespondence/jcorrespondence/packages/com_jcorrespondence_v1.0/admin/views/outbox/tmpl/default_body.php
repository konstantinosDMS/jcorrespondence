<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: default_body.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restrocted Access');

echo '<tbody>';

//display the whole list
$start = 0;
$row = 0; 

$session = JFactory::getSession();
$session->tree=array();

for ($i=0;$i<count($this->moreItems);$i++){
    $postIdArray[] = (int) $this->moreItems[$i]->postid;
}

for ($i=0;$i<count($this->moreItems);$i++){
    $parentIdArray[] = (int) $this->moreItems[$i]->parent;
}

if (!empty($parentIdArray)) $session->tree = $parentIdArray;

$tree = new treenode($start, '', '', '', '',null,'','','','' , 1, true, -1, $this);
$row = $tree->display($row);
$row++;

if (count($session->tree)>0) $parentIdArray = $session->tree ;

for ($i=0;$i<count(@$parentIdArray);$i++){
   if ($parentIdArray[$i]=='') continue;
   $tree = new treenode($parentIdArray[$i],  '', '', '', '',null,'','','','',1, true, -1, $this);
   $row = $tree->display($row);
   $parentIdArray = $session->tree ;
}  

echo '</tbody>';
?>


