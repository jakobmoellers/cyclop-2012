<!--
/**
 *  Messages service
 *
 * Availables commands:
 *
 * GET_ERRORS_MESSAGES
 * GET_BANNED_USERS
 * GET_PASSWORD_UNCHANGED
 * GET_SQLITE_SETTINGS
 * GET_INSTALL_DIR_EXIST  // temp. removed
 * GET_EXAMPLES_DIR_EXIST
 * GET_IP2COUNTRY
 * GET_USERS_LOGIN
 * GET_USERS_LOGOUT
 */

function sumo_message(cmd, n)
{
	var id = <?=intval($_GET['id'])?>;  // user id
	var m  = parseInt(n);

	sumo_ajax_get("messages", 
				  "services.php?module=messages&"
				 +"service=getmessages&"
				 +"m="+m+"&"
				 +"cmd="+cmd+"&"
				 +"id=<?=intval($_GET['id'])?>", true);
}

<?

$loggedin = intval($_GET['loggedin']);
$group    = base64_decode($_GET['group']);
$group    = explode(";", $group);

if(in_array("sumo", $group) && !$loggedin)
{
	echo "
// Startup for administrators
setTimeout('sumo_message(\"GET_ERRORS_MESSAGES\",0)',    9000);
setTimeout('sumo_message(\"GET_SQLITE_ERROR\",2)',      13000);
setTimeout('sumo_message(\"GET_BANNED_USERS\",3)',      16000);
setTimeout('sumo_message(\"GET_IP2COUNTRY\",4)',        25000);
setTimeout('sumo_message(\"GET_INSTALL_DIR_EXIST\",11)', 27000);
setTimeout('sumo_message(\"GET_EXAMPLES_DIR_EXIST\",5)',  35000);
";
}

if(in_array("sumo", $group))
{
	echo "
// Loop for administrators
setInterval('sumo_message(\"GET_ERRORS_MESSAGES\",8)', 600000);
setInterval('sumo_message(\"GET_BANNED_USERS\",9)',    800000);
";
}

if(!$loggedin)
{
	echo "
// Startup for others
setTimeout('sumo_message(\"GET_PASSWORD_UNCHANGED\",1)', 10000);
";
}

?>

// Loop for others
setInterval('sumo_message(\"GET_USERS_LOGIN\", 6)',  	      60000);
setInterval('sumo_message(\"GET_USERS_LOGOUT\", 7)',  	      65000);
setInterval('sumo_message(\"GET_PASSWORD_UNCHANGED\", 10)', 3600000);


/**
 *  Show message
 */
function sumo_show_message(name, message, level, autoclose, form, b1, b2, b3)
{
<?
	define('SUMO_PATH', dirname(__FILE__));
	
	$tpl_h = @file_get_contents(SUMO_PATH."/../modules/messages/templates/message_h.tpl");
	$tpl_m = @file_get_contents(SUMO_PATH."/../modules/messages/templates/message_m.tpl");
	$tpl_l = @file_get_contents(SUMO_PATH."/../modules/messages/templates/message_l.tpl");
?>
	var tpl_h = "<? echo base64_encode($tpl_h); ?>";
	var tpl_m = "<? echo base64_encode($tpl_m); ?>";
	var tpl_l = "<? echo base64_encode($tpl_l); ?>";
	var s1 = s2 = s3 = '';
	
	if(name == null || name == '') name = "msg"+sumo_get_rand_number();
	
	switch(level)
	{
		case 'h': var tpl = tpl_h; var delay = 6500; break;
		case 'm': var tpl = tpl_m; var delay = 6200; break;
		case 'l': var tpl = tpl_l; var delay = 5000; break;
		default:  var tpl = tpl_l; var delay = 5000; break;
	}
	
	if(autoclose == 0) delay = 20000;
	if(form == null) form = '';
	if(b1 == null) b1 = '';
	if(b2 == null) b2 = '';
	if(b3 == null) b3 = '';
	if(b1 != '')   s1 = '&nbsp;';
	if(b2 != '')   s2 = '&nbsp;';
	if(b3 != '')   s3 = '&nbsp;';
	
	tpl = sumo_base64.decode(tpl);
	tpl = tpl.replace(/{{GET:WindowElement}}/g, name);
	tpl = tpl.replace(/{{GET:PageTheme}}/g, sumo_theme);
	tpl = tpl.replace(/{{MESSAGE}}/g, message);
	tpl = tpl.replace(/{{MESSAGE:F}}/g, sumo_base64.decode(form));
	tpl = tpl.replace(/{{BUTTON:1}}/g, sumo_base64.decode(b1)+s2);
	tpl = tpl.replace(/{{BUTTON:2}}/g, s1+sumo_base64.decode(b2)+s3);
	tpl = tpl.replace(/{{BUTTON:3}}/g, s2+sumo_base64.decode(b3));
	
	sumo_add_window(name);
	
	document.getElementById(name).innerHTML = tpl;
	
	sumo_center_window(name);
	
	dd.elements[name].maximizeZ();
	setTimeout('dd.elements[\"'+name+'\"].maximizeZ();', 1000);		
		
	if(autoclose == 1)
	{
		setTimeout('opacity("'+name+'", 100, 0, 600);', delay);
		setTimeout('sumo_remove_window("'+name+'")', (delay+100));
	}
}

//-->