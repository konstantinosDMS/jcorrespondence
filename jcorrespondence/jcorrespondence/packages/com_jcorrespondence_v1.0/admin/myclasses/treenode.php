<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: treenode.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

class treenode
{ 
  
  private $m_postid;
  private $m_title;
  private $m_poster;
  private $m_posted;
  private $m_children;
  private $m_childlist;
  private $m_depth;
  private $m_catid;
  private $m_checked_out;
  private $m_status;
  private $m_editor;
  private $m_checked_out_time;
  private $m_cat_link;
  private $m_category_title;
  private $m_myModel;
  private $m_items;
  private $m_view;
  private $m_flag;
  private $m_expand = false;

  public function __construct($postid, $title, $poster, $posted, $editor, 
                              $catid, $checked_out, $checked_out_time, $status, 
                              $category_title, $children, $expand, $depth, $view)
  { 
          
    $this->m_postid = $postid;
    $this->m_title = $title;
    $this->m_poster = $poster;
    $this->m_posted = $posted;
    $this->m_children =$children;
    $this->m_childlist = array();
    $this->m_depth = $depth;  
    $this->m_view = $view;
    $this->m_editor = $editor;
    $this->m_catid = $catid;
    $this->m_checked_out = $checked_out;
    $this->m_checked_out_time = $checked_out_time;
    $this->m_category_title = $category_title;
    $this->m_status = $status;
    $this->flag = false;
    $this->session = JFactory::getSession();
          
    $modelName = 'JCorrespondenceModel'.$this->m_view->getName();
    $this->m_myModel = JModelList::getInstance($modelName);
    
    $tmpflag = $this->m_myModel->getFlag($this->m_postid);
           
    for ($i=0;$i<count($tmpflag);$i++){
        for ($j=0;$j<count(@$tmpflag[$i]);$i++){
            if ((int)$tmpflag[$i][$j]==(int)$this->m_postid) $this->m_flag=true; 
            else $this->m_flag=false;
        }
    }
    
    if ( @$this->session->tree){
        for ($i=0;$i<count($this->session->tree);$i++){
                  if ((int)$this->m_postid == (int)$this->session->tree[$i])  $this->session->tree[$i]='';
        }
    }
    
    if ($expand && $children){
        $count = 0; 
        $this->m_items =  $this->m_myModel->getItems($this->m_postid);
       
        foreach ($this->m_items as $i => $item) {
            
            if ($this->session->get($item->postid)==true) $m_expand =true; else $m_expand = false;
            
         
                
            $this->m_childlist[$count]= new treenode($item->postid, $item->title,
                                            $item->created_by, $item->created, $item->editor,
                                            $item->catid, $item->checked_out,$item->checked_out_time,
                                            $item->status,$item->category_title,$item->children, $m_expand,
                                            $depth+1, $this->m_view);
            
            $count++;
        }
    }
  }

  function display($row)
  {
      $user		= JFactory::getUser();
      $userId		= $user->get('id');
      
      if($this->m_depth>-1)  
      {

      $this->m_cat_link= JRoute::_('index.php?option=com_categories&extension=com_jcorrespondence&task=edit&type=other&cid[]='. $this->m_catid);
      $canEdit	= $user->authorise('core.edit',	'com_jcorrespondence.category.'.$this->m_catid);
      $canCheckin= $user->authorise('core.manage','com_checkin') || $this->m_checked_out==$user->get('id') || $this->m_checked_out==0;
 
      echo '<tr class="row'. $row % 2 .'">';
      echo '<td class="center" >';
      echo JHtml::_('grid.id', $row, $this->m_postid);
      
      echo '</td>';
      echo '<td class="center">';
      if ($this->m_status==0) echo '<b>'.(int)$this->m_postid.'</b>';  else echo (int)$this->m_postid;
      echo '</td>';
      echo '<td class="center">';      
     
      if ($this->m_children && sizeof($this->m_childlist) && $this->m_flag)  
      {  
        echo "<a href = 'index.php?option=com_jcorrespondence&view=". $this->m_view->getName() ."&collapseId=".(int)$this->m_postid."'
             ><img src ='". JURI::root()."/media/com_jcorrespondence/images/minus.gif' valign = 'bottom' 
             height = 15 width = 15 alt = 'Collapse Thread' border = 0 /></a>";
      }
      else if($this->m_children && $this->m_flag )
      {       
        echo "<a href = 'index.php?option=com_jcorrespondence&view=". $this->m_view->getName() ."&expandId=".
             (int)$this->m_postid."'><img src ='". JURI::root()."/media/com_jcorrespondence/images/plus.gif'  
             height = 15 width = 15 alt = 'Expand Thread' border = 0></a>";
      }
     
      echo '</td>'; 
      echo '<td class="nowrap">';
      
      if ($this->m_checked_out){ 
         JHtml::_('jgrid.checkedout', $row, $this->m_editor, $this->m_checked_out_time, '', $canCheckin);
      } 
     
      for($i = 0; $i<$this->m_depth; $i++)
      {
        echo "<img src='".JURI::root()."/media/com_jcorrespondence/images/spacer.gif' height = 15            
                         width = 15 alt = \'\' valign = \'bottom\' />";
      }
      
        if ($canEdit) {
            echo '<a href="'. JRoute::_('index.php?option=com_jcorrespondence&task=reply.edit&postid='.(int) $this->m_postid) .'">';            
            if (($this->m_status==0)&&($this->m_view->getName()=='inbox')) echo '<b>'.$this->m_view->escape($this->m_title).'</b></a>'; 
            else echo $this->m_view->escape($this->m_title).'</a>';
        }
        else {
            if (($this->m_status==0)&&($this->m_view->getName()=='inbox')) echo '<b>'.$this->m_view->escape($this->m_title).'</b>'; 
            else echo $this->m_view->escape($this->m_title);
        }
        
	echo '</td>';
        echo '<td class="nowrap center">';
	if ($this->m_category_title==''){
           if (($this->m_status==0)&&($this->m_view->getName()=='inbox')) echo '<b>None</b>'; else echo 'None';
        }
        else {
            if (($this->m_status==0)&&($this->m_view->getName()=='inbox')) echo '<b>'.$this->m_view->escape($this->m_category_title).'</b>'; 
            else echo $this->m_view->escape($this->m_category_title);
        }
	echo '</td><td class="center">';
        if (($this->m_status==0)&&($this->m_view->getName()=='inbox')) echo '<b>'.$this->m_view->escape($this->m_posted).'</b>'; 
        else echo $this->m_view->escape($this->m_posted);
        echo '</td><td class="center">'; 
        if (($this->m_status==0)&&($this->m_view->getName()=='inbox')) echo '<b>'.$this->m_view->escape($this->m_editor).'</b>'; 
        else echo $this->m_view->escape($this->m_editor);
        echo '</td><td class="center">'; 
        if ($this->m_view->getName()=='inbox'){
            if ($this->m_status==1) echo 'Read'; else echo '<b>'.'UnRead'.'</b>';
        }
        else if ($this->m_view->getName()=='outbox'){
              
            $this->numberItems = $this->m_myModel->get_myItems($this->m_postid);
            $this->numberItems = (((int)($this->numberItems[0]->count))+0);
               
            if ($this->numberItems==0) {
               
                echo 'All Users have read this message'; 
            }
            else {
                
                if ($this->numberItems>1) $text = 'users'; else $text= 'user';
                echo '<b>'.'<a href="'. JRoute::_('index.php?option=com_jcorrespondence&task=users.unread&postid='.
                (int) $this->m_postid) .'" title="See who have not read this message">UnRead'.
                        ' <b>( ' . $this->numberItems .' '. $text .                    
                    ' have not read this message yet )</b> ' . '</a>'.'</b>';
            }
        }
       
        echo '</td></tr>';
     $row++;
     
    }
  
    $num_children = sizeof($this->m_childlist);
    
    for($i = 0; $i<$num_children; $i++)
    {
       $row = $this->m_childlist[$i]->display($row);
    }
    
    return $row;
  }
}
?>


