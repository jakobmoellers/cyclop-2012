<?php
/**
 * SUMO MODULE: Groups | Edit Group
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_group_info($_GET['id']);

$tpl['GET:EditForm']     = sumo_get_form_req('', 'modify', 'id='.$tab['id']);
//$tpl['GET:Relationship'] = "<img src='services.php?module=groups&service=relationship&cmd=GET_USERS&id=".$tab['id']."'>";
$tpl['PUT:GroupName']    = "<input type='hidden' name='id' value='".$tab['id']."' />"
                            ."<input type='text' size='35' name='groupname' value='".$tab['usergroup']."' />"
                            ."<input type='hidden' name='oldgroup' value='".$tab['usergroup']."' />";
$tpl['PUT:GroupDesc']    = "<input type='text' size='35' name='groupdesc' value='".$tab['description']."' />";
$tpl['LINK:AddGroup'] 	 = sumo_get_action_icon("groups", "add", "groups.content", "?module=groups&action=new&decoration=false");  
$tpl['LINK:EditGroup']   = sumo_get_action_icon("groups", "edit");  

$msg = sumo_get_simple_rand_string(4, "123456789");

$tpl['LINK:Remove']      = "<div class='sub-module-icon' "
			      ."onmouseover='this.style.outline=\"1px solid #999999\";this.style.background=\"#FFFFFF\"' "
                              ."onmouseout='this.style.outline=\"\";this.style.background=\"\"'>"
                              ."<a href=\"javascript:sumo_show_message('msg$msg', '".htmlspecialchars(sumo_get_message('AreYouSureDelete', array($tab['usergroup'], htmlspecialchars($tab['description'], ENT_QUOTES))))."', 
                                                                         'h', 0, 
									 '".base64_encode(sumo_get_form_req('', 'delete', 'id='.$tab['id']))."',
                                        				 '".base64_encode('')."',
									 '".base64_encode("<input type='button' value='".$language['Cancel']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."',
									 '".base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."'
								 );\">"
				."<img src='themes/".$SUMO['page']['theme']."/images/modules/groups/delete.png' vspace='4'><br>"
				.$language['Remove']
				."</a>"
				."</div>";
?>