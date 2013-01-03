<?php
/**
 * SUMO MODULE: Users | Edit User Image
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
		
$tab = sumo_get_user_info($_GET['id'], 'id', FALSE);
	
if(sumo_verify_permissions(4, $tab['group'])) 
{                           
     if($SUMO['user']['id'] == $_GET['id'] || $SUMO['user']['user'] == 'sumo') 
     {                                                      
        $validate[0] = '';
                
        // If id not exist				
		if(!$tab['id']) 
            $tpl['MESSAGE:H'] = sumo_get_message('W00001C', $_GET['id']);
        else 
        {                                                
           if(isset($_FILES['user_image']['name'])) 
           {
				if($_FILES['user_image']['name']) 
                {
                	if(!sumo_update_user_image($_GET['id'], 30720))  
                    	$validate = array(FALSE, $language['ImageNotUpdated']);
                    else
                        $validate = array(TRUE, $language['ImageUpdated']);                     
                } 
            }           
        }       
        
        if($validate[0]) $tpl['MESSAGE:M'] = $validate[1];
        
        $tpl['GET:UpdateForm']	= "<form action='?module=users&action=editimg&id=".$tab['id']."' "
                                 ."name='UpdateUserImg' method='POST' enctype='multipart/form-data'>";
        $tpl['IMG:User']   		= "<img src='services.php?module=users&service=image&cmd=GET_USER&id=".$tab['id']."' alt='".$tab['username']."' class='user'>";
        $tpl['PUT:UserImage']   = "<input type='hidden' name='MAX_FILE_SIZE' value='30720'>"
                                 ."<input type='file' size='20' class='file' name='user_image' >";
        $tpl['GET:DeleteForm']	= "<form action='?module=users&action=deleteimg&id=".$tab['id']."' name='DeleteUserImg' method='POST'>\n"
                                 ."<input type='submit' class='button' value='".$language['Delete']."'>\n"
                                 ."</form>";        
		
        // Note: not using sumo_show_window() function 
        // because for this event a window is external
        $tpl_file = SUMO_PATH_MODULE.'/templates/editimg.tpl';
                                    
        if(sumo_verify_file($tpl_file)) $content = implode('', file($tpl_file));
                                          
        echo sumo_process_template($content, $tpl);
        
        exit;
     }

}
else 
{
	$action_error = true;
	
	$tpl['MESSAGE:H'] = $language['AccessDenied'];
}

?>