<?php
/**
 * SUMO CORE FUNCTIONS LIBRARY
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */


/**
 * Get global application configuration
 *
 * Get and return an array with all fields of specified config name taken from
 * a system table defined in "SUMO_TABLE_CONFIGURATION" constant.
 * If config name isn't specified the function return standard configurations values.
 * 
 * Note: All system tables and configuration names are defined in
 * <i>&lt;sumo_path&gt;/etc/config.*.php</i>.
 *
 * @global     resource $SUMO
 * @return     array Return an array with global configuration parameters
 * @filesource
 */
function sumo_get_config($name='server', $cache=TRUE, $time=30)
{
    GLOBAL $SUMO;

    $query = "SELECT data FROM ".SUMO_TABLE_CONFIGS."
	      	  WHERE name='".$name."'";

    if($cache)
	$rs = $SUMO['DB']->CacheExecute($time, $query);
    else
    	$rs = $SUMO['DB']->Execute($query);

    $data = $rs->FetchRow();

    return sumo_xml_toarray($data[0]);
}

/**
 * Get relative path to linking web objects correctly
 * 
 * @return unknown
 */
function sumo_get_web_path()
{
    $path = '';
    $path_len = strlen($_SERVER['PHP_SELF']);
    $realpath = realpath($_SERVER['DOCUMENT_ROOT']);
	
    for($p=1; $p<=$path_len; $p++)
    {
        if(substr($_SERVER['PHP_SELF'], $p, 1) == '/') $path .= '../';
    }

    // Fix for IIS Server
    if($realpath === FALSE) 
    {
	$info = pathinfo(SUMO_PATH);
	$realpath = $info['dirname'];
    }
	
    $path = $path.str_replace($realpath, '', SUMO_PATH).'/';		
    $path = str_replace($_SERVER['DOCUMENT_ROOT'], '/', $path);		
    $path = str_replace('\\', '/', $path);
    $path = str_replace('//', '/', $path);
	
    // Fix by Mark Moran
    if($path == '/') $path = '';
    
    return $path;
}

/**
 * Return locale settings
 *
 * http://www.loc.gov/standards/iso639-2/php/code_list.php
 */
function sumo_get_locale($lang='')
{
	GLOBAL $SUMO;

	$lang = $lang ? $lang : $SUMO['user']['language'];

	if($SUMO['server']['os'] == 'Windows')
	{
	    $locale = array(
			    'it' => 'ita',
			    'en' => 'eng',
			    'es' => 'spa',
			    'fr' => 'fra'
		    );
	}
	else
	{
	    $locale = array(
			    'it' => 'it_IT.'.SUMO_CHARSET,
			    'en' => 'en_EN.'.SUMO_CHARSET,
			    'es' => 'es_ES.'.SUMO_CHARSET,
			    'fr' => 'fr_FR.'.SUMO_CHARSET
			    );
	}

	return $locale[$lang];
}

/**
 * Get access point informations
 *
 * Get access point informations from specified ID or URL, return an array with all parameters
 * of requested access point. You can set $cache parameter as "TRUE" to caching data.
 *
 * @global	resource $SUMO
 * @param	bool $value To cache or not data
 * @param	bool $cache To cache or not data
 *
 * @return	array return an array with all parameters of access point
 * @filesource
 */
function sumo_get_accesspoint_info($value=false, $field='', $cache=true)
{
	GLOBAL $SUMO;

	if(!$value && !$field) 
	{
		$query = "SELECT * FROM ".SUMO_TABLE_ACCESSPOINTS."
			    WHERE path = '".$_SERVER['PHP_SELF']."'
				AND node = (
					    SELECT id FROM ".SUMO_TABLE_NODES."
					    WHERE host = '".$SUMO['server']['name']."'
					)";
	}
	else
	{	
		switch($field)
		{
		    case 'name': 
		    case 'path': $value = "'".$value."'"; break;
		    default:     $value = intval($value); break;				
		}
		
		switch($field)
		{				
			case 'path':
				if(!$value) $value = $_SERVER['PHP_SELF'];
				
				$query = "SELECT * FROM ".SUMO_TABLE_ACCESSPOINTS."
					    WHERE path = ".$value."
						AND node = (
						  	SELECT id FROM ".SUMO_TABLE_NODES."
						  	WHERE host = '".$SUMO['server']['name']."'
						    )";
				break;
			
			default:
				$query = "SELECT * FROM ".SUMO_TABLE_ACCESSPOINTS."
					  WHERE ".$field." = ".$value;
				break;
		}
	}

	if($cache)
	    $rs = $SUMO['DB']->CacheExecute(30, $query);
	else
	    $rs = $SUMO['DB']->Execute($query);

	$page = $rs->FetchRow();

	// Current Http protocol (NOT FROM DB!)
	$page['protocol'] = isset($_SERVER['HTTPS']) ? 'https' : 'http';
	$page['web_path'] = sumo_get_web_path();

	if(!isset($page['path']))
	{
	    $page['id']		  = 0;
	    $page['node']	  = 0;
	    $page['pwd_encrypt']  = 0;
	    $page['filtering'] 	  = 1;
	    $page['change_pwd']   = 0;
	    $page['registration'] = 0;
	    $page['path'] 	  = $_SERVER['PHP_SELF'];
	    $page['name'] 	  = $SUMO['config']['accesspoints']['def_name']  ? $SUMO['config']['accesspoints']['def_name']  : 'en:Login;it:Login;fr:Login;es:Login';
	    $page['theme']	  = $SUMO['config']['accesspoints']['def_theme'] ? $SUMO['config']['accesspoints']['def_theme'] : 'sumo';
	    $page['group']	  = $SUMO['config']['accesspoints']['def_group'] ? $SUMO['config']['accesspoints']['def_group'] : 'sumo';
	    $page['reg_group']	  = $page['usergroup'];

	    if(!sumo_verify_is_console($page['path']))
	    {
	    	sumo_write_log("E00124X", $page['path'], 3, 1, 'errors');
	    }
	}
	else
	{
	    $page['group'] = explode(";", $page['usergroup']);
	}

	$page['usergroup'] = $page['group']; // alias
	$page['url'] 	   = $page['protocol'].'://'.$_SERVER['HTTP_HOST'].$page['path'];

	return $page;
}

/**
 * Get accesspoint names
 *
 * @author Alberto Basso
 */
function sumo_get_accesspoint_name($names='', $lang='', $html=FALSE)
{
	GLOBAL $SUMO;

	$names 	   = explode(";", $names);
	$languages = sumo_get_available_languages();

	for($l=0; $l<count($languages); $l++)
	{
		$name = explode(":", $names[$l]);

		if($html)
			$aname .= "<img src='themes/".$SUMO['page']['theme']."/images/flags/".$name[0].".png'>&nbsp;".$name[1]."<br>";
		else
			$aname[$name[0]] = $name[1];
	}

	if($lang)
	{
		if($html)
			$apname = "<img src='themes/".$SUMO['page']['theme']."/images/flags/".$lang.".png'>&nbsp;".$aname[$lang];
		else
			$apname = $aname[$lang];
	}
	else
	{
		$apname = $aname;
	}

	return $apname ? $apname : '---';
}

/**
 * Update session data: url location and session timeout
 * (update url only if user change access point!)
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_update_session_data()
{
	GLOBAL $SUMO;

	if(!isset($_SESSION['user']['location'])) $_SESSION['user']['location'] = '';
	
	$expire = $SUMO['server']['time'] + $SUMO['config']['sessions']['timeout'];
			
	if($_SESSION['user']['location'] != $SUMO['page']['url'])
	{	
	    $query = "UPDATE ".SUMO_TABLE_SESSIONS."
		  	  SET ip='".$SUMO['client']['ip']."',
			      url='".$SUMO['page']['url']."',
			      expire=".$expire.",
			      activity=activity+1
			  WHERE node='".$SUMO['server']['name']."'
			    AND session_id='".$SUMO['client']['session_id']."'";
		
	    $_SESSION['user']['location'] = $SUMO['page']['url'];
	}
	else
	{
	    $query = "UPDATE ".SUMO_TABLE_SESSIONS."
		  	  SET expire=".$expire.",
			      activity=activity+1
			  WHERE node='".$SUMO['server']['name']."'
			    AND session_id='".$SUMO['client']['session_id']."'";
	}
		
	$SUMO['DB']->Execute($query);
	
	// Cookie to use frame for login
	setcookie('loggedin', 1, $expire);
	// for user on JavaScript
	setcookie('user', $SUMO['user']['user'], $expire);
}

/**
 * Regenerate session id if using database
 *
 * NOTE: Work only if session is stored on database and
 * if sessions replica in disabled
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_session_regenerate_id()
{
	GLOBAL $SUMO;
	
	if(SUMO_SESSIONS_DATABASE)
	    adodb_session_regenerate_id();
	else 
	    session_regenerate_id();

	$query = "UPDATE ".SUMO_TABLE_SESSIONS."
		    SET session_id='".session_id()."'
		    WHERE session_id='".$SUMO['client']['session_id']."'";

	$SUMO['DB']->Execute($query);
		
	sumo_create_session_id(false);
}

/**
 * Stats for accesspoints
 *
 * @global	resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_update_accesspoints_stats($type='access', $id_page=FALSE)
{
	GLOBAL $SUMO;

	$id_page = $id_page ? intval($id_page) : $SUMO['page']['id'];
	$query   = FALSE;

	switch($type)
	{
		// count access
		case 'access':
			$query = "UPDATE ".SUMO_TABLE_ACCESSPOINTS_STATS."
				    SET access=access+1,
				        last_login=".$SUMO['server']['time']."
				    WHERE id_page=".$id_page;
			break;

		case 'activity':
			$query = "UPDATE ".SUMO_TABLE_ACCESSPOINTS_STATS."
				    SET activity=activity+1,
					updated=".$SUMO['server']['time']."
				    WHERE id_page=".$id_page;
			break;
	}
		
	if($query) $SUMO['DB']->Execute($query);
}

/**
 * Get user agent
 *
 * @return string HTTP_USER_AGENT
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_user_agent()
{
    return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : $HTTP_USER_AGENT;
}

/**
 * Get user platform
 *
 * @global string $user_agent
 * @return string
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_user_platform()
{
    GLOBAL $user_agent;

    if(empty($user_agent)) $user_agent = sumo_get_user_agent();

    if (strstr($user_agent, 'Win'))
	return 'Windows';
    elseif (strstr($user_agent, 'Mac'))
	return 'MacOSX';
    elseif (strstr($user_agent, 'iPhone'))
	return 'iPhone';
    elseif (strstr($user_agent, 'Symbian'))
	return 'Symbian';
    elseif (strstr($user_agent, 'Linux'))
	return 'GNU/Linux';
    elseif (strstr($user_agent, 'Bsd'))
        return 'BSD';
    elseif (strstr($user_agent, 'Qnx'))
        return 'QNX';
    elseif (strstr($user_agent, 'Sun'))
        return 'SunOS';
    elseif (strstr($user_agent, 'Solaris'))
        return 'Solaris';
    elseif (strstr($user_agent, 'Irix'))
        return 'IRIX';
    elseif (strstr($user_agent, 'Aix'))
        return 'AIX';
    elseif (strstr($user_agent, 'HP-UX'))
        return 'HP-UX';
    elseif (strstr($user_agent, 'Unix'))
        return 'Unix';
    elseif (strstr($user_agent, 'Amiga'))
        return 'Amiga';
    elseif (strstr($user_agent, 'Beos'))
        return 'BeOS';
    elseif (strstr($user_agent, 'libwww'))  // I'm sorry!  :(
        return '*nix';
    else
	return 'Unknow';
}

/**
 * Get browser and version
 * (must check everything else before Mozilla)
 *
 * @global string $user_agent
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_user_browser()
{
    GLOBAL $user_agent;

    if(empty($user_agent)) $user_agent = sumo_get_user_agent();

    if(strstr($user_agent, 'Gecko'))
    {
	if (preg_match('@Firefox/([0-9].[0-9]{1,2})@', $user_agent, $version))
	    return 'Firefox '.$version[1];
	elseif (preg_match('@Safari/([0-9]*)@', $user_agent, $version))
	    return 'Safari '.$version[1];
	elseif (preg_match('@Camino/([0-9].[0-9]{1,2})@', $user_agent, $version))
	    return 'Camino '.$version[1];
	elseif (preg_match('@Netscape/([0-9].[0-9]{1,2})@', $user_agent, $version))
	    return 'Netscape '.$version[1];
	else
	    return 'Mozilla';
    }
    elseif (preg_match('@MSIE ([0-9].[0-9]{1,2})@', $user_agent, $version))
        return 'Internet Explorer '.$version[1];
    elseif (preg_match('@Opera(/| )([0-9].[0-9]{1,2})@', $user_agent, $version))
        return 'Opera '.$version[1];
    // Konqueror 2.2.2 says Konqueror/2.2.2
    // Konqueror 3.0.3 says Konqueror/3
    elseif (preg_match('@(Konqueror/)(.*)(;)@', $user_agent, $version))
		return 'Konqueror '.$version[2];
    elseif (preg_match('@OmniWeb/([0-9].[0-9]{1,2})@', $user_agent, $version))
		return 'OmniWeb '.$version[1];
    elseif (preg_match('@Voyager/([0-9].[0-9]{1,2})@', $user_agent, $version))
        return 'Voyager '.$version[1];
    elseif (preg_match('@Lynx/([0-9].[0-9]{1,2})@', $user_agent, $version))
        return 'Lynx '.$version[1];
    elseif (preg_match('@Chrome/([0-9].[0-9]{1,2})@', $user_agent, $version))
        return 'Chrome '.$version[1];
    else
	return 'UNKNOW';
}

/**
 * Get client informations and return
 * an associative array
 *
 * @return array $client
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_client_info($cache=TRUE)
{
	GLOBAL $SUMO;
	
	// client IP
	$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	
	if($SUMO['config']['iptocountry']['enabled'])
	{
	    if(!$cache || !isset($_SESSION['client']['country']))
	    {
		$_SESSION['client']['country'] = sumo_ip2country_name($ip);
	    }
	}
	else
	{
	    $_SESSION['client']['country'] = array('UNKNOW', '');
	}

	/**
	 * NOTE: This code was replaced for performance reason.
	 * Your web server must be configured to create $_SERVER['REMOTE_HOST'].
	 * For example in Apache you'll need HostnameLookups On  inside httpd.conf for it to exist.
	if(!$cache || !isset($_SESSION['client']['name']))
	{
		$_SESSION['client']['name'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	}
	*/
	$name = isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : 'Unknow';

	// Fix SERVER_ADDR
	if($ip == '::1') $ip = '-';
			
	return array(
			'ip' 	       => $ip,
			'name'         => $name,
			'country'      => $_SESSION['client']['country'][0],
			'country_code' => $_SESSION['client']['country'][1],
			'platform'     => sumo_get_user_platform(),
			'browser'      => sumo_get_user_browser(),
			'session_id'   => session_id()
		    );
}

/**
 * Return some server informations
 *
 * @global string $sumo_db
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_server_info()
{
	GLOBAL $sumo_db;

	// Security FIX
	// http://shiflett.org/blog/2006/mar/server-name-versus-http-host
	$_SERVER = sumo_array_filter($_SERVER);
	
	// strip port number  :(
    	$name = explode(":", $_SERVER['SERVER_NAME']);
	//$name = explode(":", $_SERVER['HTTP_HOST"]']); 
	$name = sumo_validate_ip($name[0]) ? exec('hostname') : $name[0];
	$host = $_SERVER['SERVER_ADDR'] == '127.0.0.1' ? 'localhost' : $_SERVER['SERVER_ADDR'];
	
	// Fix SERVER_ADDR
	if($host == '::1') $host = $_SERVER['HTTP_HOST'];
	
	return array(
			'db_type' => $sumo_db['type'],
			'db_host' => $sumo_db['host'],
			'ip'	  => $host,
			'name'	  => $name,
			'os'	  => PHP_OS,
			'time'	  => time()
		    );
}

/**
 * Delete user
 *
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_user($id=0)
{
	$id = intval($id);

	if($id > 0)
	{
	    GLOBAL $SUMO;

	    $user = sumo_get_user_info($id, 'id', FALSE);

	    $SUMO['DB']->CacheFlush("SELECT * FROM ".SUMO_TABLE_USERS."
				     WHERE id=".$id);
	    $SUMO['DB']->CacheFlush("SELECT * FROM ".SUMO_TABLE_USERS."
				     WHERE username='".$user['username']."'");

	    $query0 = "SELECT * FROM ".SUMO_TABLE_USERS."
        	       WHERE id=".$id;

	    $query1 = "DELETE FROM ".SUMO_TABLE_USERS."
			WHERE id=".$id."
			AND username<>'sumo'
			AND id<>".$SUMO['user']['id'];

	    $query2 = "DELETE FROM ".SUMO_TABLE_USERS_IMAGES."
			WHERE id_user=".$id."
			AND id_user<>1
			AND id_user<>".$SUMO['user']['id'];

	    $query3 = "DELETE FROM ".SUMO_TABLE_USERS_TEMP."
		       WHERE username='".$user['user']."'";

	    $query4 = "DELETE FROM ".SUMO_TABLE_SESSIONS."
	    		WHERE id_user=".$id."
	    		AND username<>'sumo'
	    		AND id_user<>".$SUMO['user']['id'];

	    $SUMO['DB']->Execute($query1);
	    $SUMO['DB']->Execute($query2);
	    $SUMO['DB']->Execute($query3);
            $SUMO['DB']->Execute($query4);

	    // verify if deleted:
	    $rs = $SUMO['DB']->Execute($query0);

	    // if deleted:
	    if($rs->PO_RecordCount() == 0)
	    {		
		    // if exist user data file delete it
		    $data_file = SUMO_PATH.'/tmp/profiles/'.$user['username'].'.ini';

		    if(file_exists($data_file)) unlink($data_file);

		    sumo_write_log('I00003X',
				    array($user['username'],
				    $id,
				    $SUMO['user']['user']),
				    '0,1',
				    3,
				    'system',
				    FALSE);
		    return TRUE;
		}
		else
		{
		    return FALSE;
		}
	}
    else
    {
	return FALSE;
    }
}

/**
 * Get info of user
 * If not specify an user return current session user info
 * $type specify a search method, user is default
 *
 * @global resource $SUMO
 * @return array $user_data
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_user_info($value=FALSE, $field='username', $cache=TRUE)
{
    GLOBAL $SUMO;

    $cache_time = 30;

    if(!$value) $value = $_SESSION['user']['user'];

    switch(strtolower($field))
    {
	case '':
	case 'user': 	 $field = "username"; $value = "'".$value."'"; break;
	case 'username': $field = "username"; $value = "'".$value."'"; break;
	case 'email': 	 $field = "email";    $value = "'".$value."'"; break;
	case 'id':       $field = "id";       $value = intval($value); break;
    }

    $query = "SELECT * FROM ".SUMO_TABLE_USERS."
		WHERE ".$field."=".$value;

    // ...to disable cached password when user changed it
    if(isset($_SESSION['pwd_changed']))
    {
        if($_SESSION['pwd_changed']+$cache_time > time())
            $cache = false;
        else
        {
            $cache = true;
            unset($_SESSION['pwd_changed']);
        }
    }

    if($cache)
	$rs = $SUMO['DB']->CacheExecute($cache_time, $query);
    else
	$rs = $SUMO['DB']->Execute($query);

    $user_data = $rs->FetchRow();

    $user_data['user'] 		  = $user_data['username'];
    $user_data['datasource_id']   = $user_data['datasource_id'] == "" ? false   : $user_data['datasource_id'];
    $user_data['ip']              = empty($user_data['ip'])           ? array() : sumo_get_iprange($user_data['ip']);
    $user_data['group_level']     = empty($user_data['usergroup'])    ? array() : sumo_get_grouplevel($user_data['usergroup']);
    $user_data['group']       	  = empty($user_data['usergroup'])    ? array() : sumo_get_grouplevel($user_data['usergroup'], true);
    $user_data['datasource_type'] = 'SUMO';
    $user_data['datasource_name'] = 'SUMO Access Manager';

	// Get authorization type (if defined)
	if($user_data['datasource_id'] != 1 && $user_data['datasource_type'] != 'Unix')
	{
		$ds = sumo_get_datasource_info($user_data['datasource_id']);

		$user_data['datasource_type']    = $ds['type'];
		$user_data['datasource_enctype'] = $ds['enctype'];
		$user_data['datasource_name']    = $ds['name'];
	}

	// Get shadow password for local Unix users
	if($user_data['datasource_type'] == 'Unix')
	{
		$u = exec("egrep \"^{$user_data['user']}:\" /etc/shadow");
		$p = explode(":", $u);
		$a = explode(" ", exec("passwd -S {$user_data['user']}"));

		$user_data['active']   = $a[1] == "P" ? 1 : 0;
		$user_data['password'] = $p[1];
	}
	
	return $user_data;
}

/**
 * Create an array with available groups and relative level
 *
 * @return array $grouplevel
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_grouplevel($group='', $only_group=FALSE)
{
	if(is_array($group)) $group = implode(";", $group);

	$groups = explode(";", $group);

	for($g=0; $g<count($groups); $g++)
	{
	    $group_list     = explode(":", $groups[$g]);
	    $group_name[$g] = trim($group_list[0]);
	    $group_list[1]  = isset($group_list[1]) ? $group_list[1] : '';
	    $grouplevel['usergroup'][$g] = $group_name[$g];
	    $grouplevel['group_level'][$group_name[$g]] = intval($group_list[1]);
	}

	return ($only_group) ? $grouplevel['usergroup'] : $grouplevel['group_level'];
}

/**
 * Verify if an IP is banned
 *
 * @global resource $SUMO
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_banned_ip($ip=NULL, $cache=FALSE, $cache_time=10)
{
	GLOBAL $SUMO;

	$status = FALSE;

	$query1 = "DELETE FROM ".SUMO_TABLE_BANNED."
		    WHERE time < ".($SUMO['server']['time'] - $SUMO['config']['security']['banned_time']);

	$SUMO['DB']->Execute($query1);

	if(isset($ip))
	{
		$query2 = "SELECT ip FROM ".SUMO_TABLE_BANNED."
			       WHERE ip='".$ip."'";

		if($cache)
			$rs = $SUMO['DB']->CacheExecute($cache_time, $query2);
		else
			$rs = $SUMO['DB']->Execute($query2);

		$banned = $rs->FetchRow();

		if($banned['ip'] == $ip) $status = TRUE;
	}
	else
	{
		sumo_write_log('W00039X', __FUNCTION__, '0,1', 2);
	}

	return $status;
}

/**
 * Get session informations of current user.
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_session_info($id=false)
{
	GLOBAL $SUMO;
	
	if($id)
	{
	    $query = "SELECT * FROM ".SUMO_TABLE_SESSIONS."
			WHERE id=".intval($id);	
	}
	else 
	{
	    $query = "SELECT * FROM ".SUMO_TABLE_SESSIONS."
			WHERE node='".$SUMO['server']['name']."'
			    AND username='".$SUMO['user']['user']."'
			    AND session_id='".$SUMO['client']['session_id']."'";
	}
	
	$rs 	 = $SUMO['DB']->Execute($query);
	$session = $rs->FetchRow();

	return $session;
}

/**
 * Verify if account of current user is expired
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_expired_account()
{
	GLOBAL $SUMO;

	$query = "SELECT day_limit FROM ".SUMO_TABLE_USERS."
		    WHERE username='".$SUMO['user']['user']."'";

	$rs   = $SUMO['DB']->Execute($query);
	$user = $rs->FetchRow();

	return (is_int($user['day_limit']) && $user['day_limit'] < 1) ? true : false;
}

/**
 * Verify user password
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_password()
{
	GLOBAL $SUMO;
	
	if($SUMO['page']['pwd_encrypt'] && !$SUMO['page']['http_auth'])
	{
		return($_SESSION['user']['password'] === sumo_get_hex_hmac_sha1($SUMO['connection']['security_string'], $SUMO['user']['password'])) ? true : false;
	}
	else
	{
		if($SUMO['user']['datasource_type'] == 'SUMO')
		{
			return (sha1($_SESSION['user']['password']) === $SUMO['user']['password']) ? true : false;
		}
		else
		if($SUMO['user']['datasource_type'] == 'Unix')
		{
			return (crypt($_SESSION['user']['password'], $SUMO['user']['password']) == $SUMO['user']['password']) ? true : false;
		}
		else {
	
			// Encryption type
			switch ($SUMO['user']['datasource_type'])
			{
				case 'md5':
					return (md5($_SESSION['user']['password']) === $SUMO['user']['password']) ? true : false;
					break;

				case 'crypt':
					return (crypt($_SESSION['user']['password'], $SUMO['user']['password']) == $SUMO['user']['password']) ? true : false;
					break;

				case 'crc32':
					return (sprintf("%u", crc32($_SESSION['user']['password'])) === $SUMO['user']['password']) ? true : false;
					break;

				case 'sha1':
					return (sha1($_SESSION['user']['password']) === $SUMO['user']['password']) ? true : false;
					break;

				default:
					return ($_SESSION['user']['password'] === $SUMO['user']['password']) ? true : false;
					break;
			}
		}
	}
}

/**
 * Get data source info
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_datasource_info($id=FALSE, $cache=TRUE)
{
	GLOBAL $SUMO;

	$cache_time = 30;

	$ds[0] = array(
		    	'id'   => 0,
			'name' => 'Local users',
			'type' => 'Unix',
			'host' => 'localhost'
		    );
	$ds[1] = array(
			'id'   => 1,
			'name' => 'SUMO Access Manager',
		  	'type' => 'SUMO',
		  	'host' => $SUMO['server']['db_host']
		    );

	// Search
    if($id !== FALSE)
    {
    	$id = intval($id);
    	
    	if($id == 0) return $ds[0];
    	if($id == 1) return $ds[1];

    	// Others
    	if($id > 1)
    	{
	    $query = "SELECT * FROM ".SUMO_TABLE_DATASOURCES."
		        WHERE id=".$id;

	    if($cache)
		$rs = $SUMO['DB']->CacheExecute($cache_time, $query);
	    else
	        $rs = $SUMO['DB']->Execute($query);

	    $ds = $rs->FetchRow();
    	}
    }
    // Complete list
    else {
	    $query = "SELECT * FROM ".SUMO_TABLE_DATASOURCES."
	   		ORDER BY type, name, host";

	    if($cache)
		$rs = $SUMO['DB']->CacheExecute($cache_time, $query);
	    else
	    	$rs = $SUMO['DB']->Execute($query);

    	while($tab = $rs->FetchRow())
	{
	    if($tab['id'] > 1) $ds[$tab['id']] = $tab;
	}
    }

    return $ds;
}

/**
 * Delete old database sessions
 * (...some user can exit without correct logout procedure)
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_old_sessions()
{
	GLOBAL $SUMO;

	$timeout = $SUMO['server']['time'] - $SUMO['config']['sessions']['timeout'];

	// Delete old database sessions
	$query = "DELETE FROM ".SUMO_TABLE_SESSIONS."
		    WHERE (
			    node='".$SUMO['server']['name']."'
			    AND
			    expire < ".$timeout."
			)
			OR expire < ".($timeout - 900); // for multiple sumo server

	$SUMO['DB']->Execute($query);


	// merge old database sessions
	if(SUMO_SESSIONS_DATABASE || SUMO_SESSIONS_REPLICA)
	{
		$query1 = "DELETE FROM ".SUMO_TABLE_SESSIONS_STORE."
				   WHERE sesskey NOT IN (
				   		SELECT session_id FROM ".SUMO_TABLE_SESSIONS."
				   )";

		$query2 = "DELETE FROM ".SUMO_TABLE_SESSIONS."
				   WHERE session_id NOT IN (
				   		SELECT sesskey FROM ".SUMO_TABLE_SESSIONS_STORE."
				   )";

		$SUMO['DB']->Execute($query1);
		$SUMO['DB']->Execute($query2);
	}
	// Delete files sessions
	else
	{
		$dh = dir(SUMO_PATH.'/tmp/sessions/');

		while($entry = $dh->read())
		{
			if(substr($entry, 0, 5) == 'sess_')
			{
				$file = SUMO_PATH.'/tmp/sessions/'.$entry;
				if(filemtime($file) < $timeout) unlink($file);
			}
		}

		$dh->close();
	}
}


/**
 * Delete old connections
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_old_connections()
{
	GLOBAL $SUMO;

	$query = "DELETE FROM ".SUMO_TABLE_CONNECTIONS."
			  WHERE (
			  		 node='".$SUMO['server']['name']."'
			  		 AND
			  		 time < ".($SUMO['server']['time'] - $SUMO['config']['connections']['timeout'])."
			  		 )
			  OR time < ".($SUMO['server']['time'] - $SUMO['config']['connections']['timeout'] - 900); // for multiple sumo server

	$SUMO['DB']->Execute($query);
}

/**
 * Delete old users temp not confirmed
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_old_users_temp()
{
	GLOBAL $SUMO;

	$query = "DELETE FROM ".SUMO_TABLE_USERS_TEMP."
			  WHERE time < ".($SUMO['server']['time'] - $SUMO['config']['accounts']['registration']['life'] * 3600);

	$SUMO['DB']->Execute($query);
}

/**
 * Delete old log
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_old_log()
{
	GLOBAL $SUMO;

	$life['access'] = $SUMO['config']['logs']['access']['database']['life'];
	$life['errors'] = $SUMO['config']['logs']['errors']['database']['life'];
	$life['system'] = $SUMO['config']['logs']['system']['database']['life'];

	$log_time['access'] = $life['access'] < 1 ? 86400 : ($life['access'] * 86400);
	$log_time['errors'] = $life['errors'] < 1 ? 86400 : ($life['errors'] * 86400);
	$log_time['system'] = $life['system'] < 1 ? 86400 : ($life['system'] * 86400);

	$time['access'] = $SUMO['server']['time'] - $log_time['access'];
	$time['errors'] = $SUMO['server']['time'] - $log_time['errors'];
	$time['system'] = $SUMO['server']['time'] - $log_time['system'];

	$query1 = "DELETE FROM ".SUMO_TABLE_LOG_ACCESS." WHERE time < ".$time['access'];
	$query2 = "DELETE FROM ".SUMO_TABLE_LOG_ERRORS." WHERE time < ".$time['errors'];
	$query3 = "DELETE FROM ".SUMO_TABLE_LOG_SYSTEM." WHERE time < ".$time['system'];

	$SUMO['DB']->Execute($query1);
	$SUMO['DB']->Execute($query2);
	$SUMO['DB']->Execute($query3);

	sumo_write_log('I00202X',
				array(intval($log_time['access']/86400),
				    intval($log_time['errors']/86400),
				    intval($log_time['system']/86400)),
				'0,1', 3, 'system', false);
}

/**
 * LOGIN User
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_user_login()
{
	GLOBAL $SUMO;

	$_SESSION['security_string']  = $SUMO['connection']['security_string'];
	$_SESSION['user']['password'] = '*';
	$_SESSION['loggedin']		  = true;

	sumo_delete_old_sessions();	   // Delete old sessions
	sumo_delete_old_connections(); // Delete old connections
	sumo_delete_connection();      // Delete user connection

	// Create session
	$query = "INSERT INTO ".SUMO_TABLE_SESSIONS."
		    (node, id_user, username, connected, expire, ip, hostname, country_name, url, client, session_id)
		    VALUES (
		    '".$SUMO['server']['name']."',
		    ".$SUMO['user']['id'].",
		    '".$SUMO['user']['user']."',
		    ".$SUMO['server']['time'].",
		    ".($SUMO['config']['sessions']['timeout'] + $SUMO['server']['time']).",
		    '".$SUMO['client']['ip']."',
		    '".$SUMO['client']['name']."',
		    '".$SUMO['client']['country']." - ".$SUMO['client']['country_code']."',
		    '".$SUMO['page']['url']."',
		    '".$SUMO['client']['platform']." - ".$SUMO['client']['browser']."',
		    '".$SUMO['client']['session_id']."'
		    )";
	
	$SUMO['DB']->Execute($query);
		
	// Update last login for current user
	$query = "UPDATE ".SUMO_TABLE_USERS."
		    SET last_login=".$SUMO['server']['time']."
		    WHERE id=".$SUMO['user']['id'];

	$SUMO['DB']->Execute($query);

	// Create cookie language (store for 90 days)
	if(!$_COOKIE['language']) 
	{
	    setcookie('language', $SUMO['user']['language'], $SUMO['server']['time']+7776000);
	}

	// Cookie to use iframe for login
	$expire = $SUMO['server']['time'] + $SUMO['config']['sessions']['timeout'];
	setcookie('loggedin', 1, $expire);
	setcookie('user', $SUMO['user']['user'], $expire);
	
	sumo_write_log('I00200X',
		       array($SUMO['user']['user'],
			     $SUMO['client']['ip'],
			     $SUMO['client']['country'],
			     sumo_get_accesspoint_name($SUMO['page']['name'], $SUMO['config']['server']['language']),
			     $SUMO['page']['url']),
			'0,1', 3, 'access', FALSE);
}

/**
 * LOGOUT User
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_user_logout()
{
	GLOBAL $SUMO;

	sumo_delete_old_sessions();
	sumo_delete_old_connections();

	$query = "DELETE FROM ".SUMO_TABLE_SESSIONS."
			  WHERE session_id='".$SUMO['client']['session_id']."'";

	$SUMO['DB']->Execute($query);
	
	if(SUMO_SESSIONS_DATABASE)
	{
		$query = "DELETE FROM ".SUMO_TABLE_SESSIONS_STORE."
	 		  	  WHERE sesskey='".$SUMO['client']['session_id']."'";

		$SUMO['DB']->Execute($query);
	}
	
	sumo_write_log('I00201X', array($SUMO['user']['user'],
							        $SUMO['client']['ip'],
								$SUMO['client']['country'],
								sumo_get_accesspoint_name($SUMO['page']['name'], $SUMO['config']['server']['language']),
								$SUMO['page']['url']),
								'0,1', 3, 'access', FALSE);
	// Delete all defined cookies
	$cookies = array_keys($_COOKIE);
		
	for($c=0; $c<count($cookies); $c++)
	{		
		setcookie($cookies[$c], "", 1);
	}
	
	session_destroy();
}

/**
 * Write current data
 */
function sumo_write_today()
{
	GLOBAL $SUMO;

	$file  = SUMO_PATH.'/tmp/today';
	$today = date("Ymd", $SUMO['server']['time']);

	$fp = fopen ($file, 'w+') OR die (sumo_get_message('E00106X', $file));
	    fwrite($fp, $today);
	    fclose($fp);
}

/**
 * Verify if it's a new day! ;)
 */
function sumo_verify_is_today()
{
	GLOBAL $SUMO;

	$file  = SUMO_PATH.'/tmp/today';
	$today = date("Ymd", $SUMO['server']['time']);
	$date  = '';

	if(@file_exists($file))
	{
		$fp   = fopen ($file, 'r+') OR die (sumo_get_message('E00105X', $file));
		$date = fgets ($fp, 9);
	   		fclose($fp);
	}

	return $date == $today ? true : false;
}

/**
 * Update day limit for all users (except for user 'sumo')
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_update_day_limit()
{
	GLOBAL $SUMO;

	$today = date("Ymd", $SUMO['server']['time']);

	$query = "SELECT id,day_limit,last_login,email
			  FROM ".SUMO_TABLE_USERS."
			  WHERE active=1
				  AND username<>'sumo'
				  AND day_limit > 0
				  AND (
				  	updated < ".strtotime($today)."
				  	OR
				  	updated IS NULL
				    )";

	$rs = $SUMO['DB']->Execute($query);

	while($tab = $rs->FetchRow())
	{
		// calculate days interval
		$days = $tab['day_limit'] - (date("d/m/Y", $SUMO['server']['time']) - date("d/m/Y", $tab['last_login']));

		if($days > 0)
		{
			$query1 = "UPDATE ".SUMO_TABLE_USERS."
					   SET day_limit=".$days."
					   WHERE id=".$tab['id'];
		}
		else
		{
			$query1 = "UPDATE ".SUMO_TABLE_USERS."
					   SET active=0,
					   	   day_limit=".$SUMO['config']['accounts']['life']."
					   WHERE id=".$tab['id'];

			sumo_send_expired($tab['email']); // Send notify for expired users
		}

		$query2 = "UPDATE ".SUMO_TABLE_USERS."
				   SET updated=".$SUMO['server']['time']."
				   WHERE id=".$tab['id'];

		$SUMO['DB']->Execute($query1);
		$SUMO['DB']->Execute($query2);
	}
}

/**
 * Send an email for expired account
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_send_expired($email='')
{
	GLOBAL $SUMO;

	if($SUMO['config']['accounts']['notify']['expired'] && sumo_validate_email($email))
	{
	    if(!$SUMO['config']['server']['admin']['email'])
	    {
		sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
		
		return false;
	    }
	    else  {
		$m = new Mail;
		$m->From($SUMO['config']['server']['admin']['email']);
		$m->To($email);
		$m->Subject(sumo_get_message("AccountExpired"));
		$m->Body(sumo_get_message("I00103M", $SUMO['server']['name']));
		$m->Bcc($SUMO['config']['server']['admin']['email']);
		$m->Priority(1);
		$m->Send();
		
		return true;
	    }
	}
}

/**
 * Create a connection for user
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_create_connection()
{
	GLOBAL $SUMO;

	$query = "INSERT INTO ".SUMO_TABLE_CONNECTIONS."
		    (node, ip, requests, security_string, time, session_id)
		  VALUES (
		    '".$SUMO['server']['name']."',
		    '".$SUMO['client']['ip']."',
		    1,
		    '".sumo_get_rand_string()."',
		     ".$SUMO['server']['time'].",
		    '".$SUMO['client']['session_id']."'
		   )";

	$SUMO['DB']->Execute($query);
}

/**
 * Get Node/s data
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_node_info($value='', $field='id', $cache=true, $time=30)
{
	GLOBAL $SUMO;

	$where = "";

	if($value)
	{
		switch ($field)
		{
			case 'id':	$where = "WHERE id=".intval($value);     break;
			case 'host':	$where = "WHERE host='".$value."'";	 break;
			case 'active':	$where = "WHERE active=".intval($value); break;
			default:	$where = "WHERE id=".intval($value);     break;
		}
	}


	$query = "SELECT * FROM ".SUMO_TABLE_NODES." ".$where;

	if($cache)
		$rs = $SUMO['DB']->CacheExecute($time, $query);
	else
		$rs = $SUMO['DB']->Execute($query);


	if($value && $field != 'active')
	{
		$node = $rs->FetchRow();
	}
	else
	{
		while($tab = $rs->FetchRow())
		{
			$node[$tab['id']] = $tab;
		}
	}
	
	return $node;
}

/**
 * Migrate session_id Cookie to all actives nodes 
 *
 * @param boolean $login
 */
function sumo_create_session_id($login=true)
{
	GLOBAL $SUMO;
	
	// Get id of the user session
	$session = sumo_get_session_info();
		
	$query = "SELECT host,port,name,protocol,sumo_path FROM ".SUMO_TABLE_NODES." 
		    WHERE active = 1
			AND host <> 'localhost'
			AND host <> '".$SUMO['server']['ip']."' 
			AND host <> '".$SUMO['server']['name']."'";

	$rs = $SUMO['DB']->CacheExecute(30, $query);

	while($tab = $rs->FetchRow())
	{
		$url = $tab['protocol'].'://'.$tab['host'].':'.$tab['port'].$tab['sumo_path']
			  .'/services.php?module=network&service=network&cmd=CREATE_SID'
			  .'&id='.$session['id'];

		// Modify hostname for HTTPS
		$hostname = $tab['protocol'] == 'https' ? 'ssl://'.$tab['host'] : $tab['host'];
		
		// try connection before redirect
		$connect = @fsockopen($hostname, $tab['port'], $errno, $errstr, 4);

		if($connect)
		{
			if($login || !sumo_verify_is_console($SUMO['page']['path']))
				echo "<iframe src='$url' style='visibility:hidden;width:0px;height:0px;display:none'></iframe>";
			else
				echo "<script>parent.CSID.location.href='$url';</script>";
		}
		else sumo_write_log('E00123X', array($tab['host'], "RC ".$errno.": ".$errstr), '0,1', 1, 'system', FALSE);
	}
}

/**
 * Update user request
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_update_request()
{
	GLOBAL $SUMO;

	$query = "UPDATE ".SUMO_TABLE_CONNECTIONS."
			  SET
			  	requests=requests+1,
			  	time=".$SUMO['server']['time']."
			  WHERE node='".$SUMO['server']['name']."'
			  	AND ip='".$SUMO['client']['ip']."'
			  	AND session_id='".$SUMO['client']['session_id']."'";

	$SUMO['DB']->Execute($query);
}

/**
 * Update security string when refresh a connection
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_update_security_string()
{
	GLOBAL $SUMO;

	$query = "UPDATE ".SUMO_TABLE_CONNECTIONS."
			  SET
			  	security_string='".sumo_get_rand_string()."',
			  	time=".$SUMO['server']['time']."
			  WHERE node='".$SUMO['server']['name']."'
			  	AND ip='".$SUMO['client']['ip']."'
			  	AND session_id='".$SUMO['client']['session_id']."'";

	$SUMO['DB']->Execute($query);
}

/**
 * Delete a connection for IP/session_id
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_connection($ip=NULL, $session_id=NULL)
{
	GLOBAL $SUMO;

	$user_ip    = empty($ip) 		 ? $SUMO['client']['ip'] : $ip;
	$session_id = empty($session_id) ? $SUMO['client']['session_id'] : $session_id;

	$query = "DELETE FROM ".SUMO_TABLE_CONNECTIONS."
			  WHERE node='".$SUMO['server']['name']."'
			  	AND ip='".$user_ip."'
			  	AND session_id='".$session_id."'";

	$SUMO['DB']->Execute($query);
}

/**
 * Delete a session by IP/session_id/user
 * NOTE: Session on sessions_store table it's not deleted if a user
 * was specified, sumo_delete_old_sessions() it's called to minimize
 * life of expired sessions.
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_session($ip=NULL, $session_id=NULL, $username=NULL)
{
	GLOBAL $SUMO;

    if($username)
    {
        $query = "DELETE FROM ".SUMO_TABLE_SESSIONS."
        		  WHERE username='".$username."'";

        $SUMO['DB']->Execute($query);

        sumo_delete_old_sessions();
    }
    else
    {
        $user_ip    = empty($ip) ? $SUMO['client']['ip'] : $ip;
	$session_id = empty($session_id) ? $SUMO['client']['session_id'] : $session_id;

	$query1 = "DELETE FROM ".SUMO_TABLE_SESSIONS."
		   WHERE node='".$SUMO['server']['name']."'
		    AND ip='".$user_ip."'
		    AND session_id='".$session_id."'";

	$query2 = "DELETE FROM ".SUMO_TABLE_SESSIONS_STORE."
		   WHERE sesskey='".$session_id."'";

	$SUMO['DB']->Execute($query1);
	$SUMO['DB']->Execute($query2);
    }
}

/**
 * Get connection informations of specified IP
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_connection_info($ip=NULL, $session_id=NULL)
{
	GLOBAL $SUMO;

	$ip_connected = isset($ip) 	   ? $ip : $SUMO['client']['ip'];
	$session_id   = empty($session_id) ? $SUMO['client']['session_id'] : $session_id;

	$query = "SELECT * FROM ".SUMO_TABLE_CONNECTIONS."
			  WHERE node='".$SUMO['server']['name']."'
			  	AND ip='".$ip_connected."'
			  	AND session_id='".$session_id."'";
		
	$rs = $SUMO['DB']->Execute($query);	
	$connection = $rs->FetchRow();
			
	return $connection;
}

/**
 * Insert an IP into banned client table
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_add_banned($ip=NULL)
{
	GLOBAL $SUMO;

	$ip_banned = empty($ip) ? $SUMO['client']['ip'] : $ip;

	$query = "INSERT INTO ".SUMO_TABLE_BANNED."
				(ip, time)
			  VALUES (
			  	'".$ip_banned."',
			  	 ".$SUMO['server']['time']."
			  )";

	$SUMO['DB']->Execute($query);
}

/**
 * Verify if a value in array1 is into array2. Return TRUE or FALSE.
 * If $match is TRUE return matching
 *
 * @param array $array1
 * @param array $array2
 * @param boolean $match
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_array_is_inarray($array1=array(), $array2=array(), $match=FALSE)
{
	$valid  = FALSE;
	$values = array();

	if(!is_array($array1)) $array1 = array($array1);
	if(!is_array($array2)) $array2 = array($array2);

	for($a=0; $a<count($array1); $a++)
	{
		if(in_array($array1[$a], $array2))
		{
			$valid = TRUE;

			if($match)
				$values[] = $array1[$a];
			else
				break;
		}
	}

	return ($match) ? $values : $valid;
}

/**
 * FUNZIONE: array_combine()
 * Sara' presente da PHP 5, crea un'array utilizzando
 * un'array per le chiavi e un'altro per i suoi valori.
 * Per ora questa fa la stessa cosa!
 *
 * @param array $keys
 * @param array $values
 * @return array
 */
function sumo_array_combine($keys, $values)
{
	$array = NULL;
	$k     = count($keys);

	for($e=0; $e<$k; $e++)
	{
		$array[$keys[$e]] = $values[$e];
	}

	return $array;
}

/**
 * Generates a random string with the specified length
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_rand_string($length=128)
{
   mt_srand((double)microtime()*1000000);

   $str = '';

   if($length > 0)
   {
       while(strlen($str) < $length)
       {
           switch(mt_rand(1,3))
           {
               case 1: $str .= chr(mt_rand(48,57));  break; // 0-9
               case 2: $str .= chr(mt_rand(65,90));  break; // A-Z
               case 3: $str .= chr(mt_rand(97,122)); break; // a-z
           }
       }
   }

   return $str;
}

/**
 * Generates a random string with the specified length
 * Chars are chosen from the provided [optional] list
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_simple_rand_string($length=8, $list="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ")
{
   mt_srand((double)microtime()*1000000);

   $new_str = '';

   if($length > 0)
   {
       while(strlen($new_str) < $length)
       {
           $new_str .= $list[mt_rand(0, strlen($list)-1)];
       }
   }

   return $new_str;
}

/**
 * Get a "paranoic" message for WARNING e-mail
 *
 * @global resource $SUMO
 * @param string $error
 * @param string $code
 * @param string $method
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_paranoic_message($error, $code, $method)
{
	GLOBAL $SUMO;

	return "\n---------------------------------------\n"
		  ." ".sumo_get_message($error).":\n\n"
		  ." Date: ".date($SUMO['config']['server']['date_format']." ".$SUMO['config']['server']['time_format'])."\n"
		  ." IP: ".$SUMO['client']['ip']."\n"
		  ." Host: ".$SUMO['client']['name']."\n"
		  ." Request Method: ".$method."\n"
		  ." Request URI: ".$SUMO['page']['url']." \"".sumo_get_accesspoint_name($SUMO['page']['name'], 'en')."\"\n\n"
		  ." Detected Code:\r\n\r\n".$code."\r\n"
		  ."\n---------------------------------------\n";
}

/**
 * Verify and filter an array data from malicious code
 *
 * @global resource $SUMO
 * @return array
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_array_filter($array, $method='GET', $available_tags='')
{
	GLOBAL $SUMO;

	$rc 	 = array(array());
	$num_val = count($array);

	for($e=0; $e<$num_val; $e++)
	{
		if(is_array($array[$e]))
		{
			$array[$e] = sumo_array_combine(array_keys($array[$e]), sumo_array_filter(array_values($array[$e]), $method));
		}
		else
		{
			$x  	= 0;
			$rc[$e] = array();

			// Lets now sanitize the GET or SERVER vars
			if($method == 'GET' || $method == 'SERVER')
			{
				if (
					(eregi("<[^>]*script*\"?[^>]*>", $array[$e]))    ||
		            (eregi(".*[[:space:]](or|and)[[:space:]].*(=|like).*", $array[$e])) ||
		            (eregi("<[^>]*object*\"?[^>]*>",  $array[$e]))   ||
		            (eregi("<[^>]*iframe*\"?[^>]*>",  $array[$e]))   ||
		            (eregi("<[^>]*applet*\"?[^>]*>",  $array[$e]))   ||
		            (eregi("<[^>]*meta*\"?[^>]*>",    $array[$e]))   ||
		            (eregi("<[^>]*style*\"?[^>]*>",   $array[$e]))   ||
		            (eregi("<[^>]*form*\"?[^>]*>",    $array[$e]))   ||
		            (eregi("<[^>]*window.*\"?[^>]*>", $array[$e]))   ||
		            (eregi("<[^>]*alert*\"?[^>]*>",   $array[$e]))   ||
		            (eregi("<[^>]*img*\"?[^>]*>", 	  $array[$e]))   ||
		            (eregi("<[^>]*document.*\"?[^>]*>", $array[$e])) ||
		            (eregi("<[^>]*cookie*\"?[^>]*>",  $array[$e]))   ||
		            (eregi("\"",  $array[$e]))
		            )
					{
						$rc[$e][$x] = "E00108X";
						$x++;
					}
			}

			// Lets now sanitize the POST vars
			if($method == 'POST')
			{
				if (
					(eregi("<[^>]*script*\"?[^>]*>",    $array[$e])) ||
		            (eregi("<[^>]*object*\"?[^>]*>",    $array[$e])) ||
		            (eregi("<[^>]*iframe*\"?[^>]*>",    $array[$e])) ||
		            (eregi("<[^>]*applet*\"?[^>]*>",    $array[$e])) ||
		            (eregi("<[^>]*meta*\"?[^>]*>",      $array[$e])) ||
		            (eregi("<[^>]*window.*\"?[^>]*>",   $array[$e])) ||
		            (eregi("<[^>]*alert*\"?[^>]*>",     $array[$e])) ||
		            (eregi("<[^>]*document.*\"?[^>]*>", $array[$e])) ||
		            (eregi("<[^>]*cookie*\"?[^>]*>",    $array[$e]))
		            )
					{
						$rc[$e][$x] = "E00108X";
						$x++;
					}
			}

			// Lets now sanitize the COOKIE vars
			if($method == 'COOKIE')
			{
				if (
					(eregi("<[^>]*script*\"?[^>]*>", $array[$e]))    ||
		            (eregi(".*[[:space:]](or|and)[[:space:]].*(=|like).*", $array[$e])) ||
		            (eregi("<[^>]*object*\"?[^>]*>",  $array[$e]))   ||
		            (eregi("<[^>]*iframe*\"?[^>]*>",  $array[$e]))   ||
		            (eregi("<[^>]*applet*\"?[^>]*>",  $array[$e]))   ||
		            (eregi("<[^>]*meta*\"?[^>]*>",    $array[$e]))   ||
		            (eregi("<[^>]*style*\"?[^>]*>",   $array[$e]))   ||
		            (eregi("<[^>]*form*\"?[^>]*>",    $array[$e]))   ||
		            (eregi("<[^>]*window.*\"?[^>]*>", $array[$e]))   ||
		            (eregi("<[^>]*alert*\"?[^>]*>",   $array[$e]))   ||
		            (eregi("<[^>]*img*\"?[^>]*>", 	  $array[$e]))   ||
		            (eregi("<[^>]*document.*\"?[^>]*>", $array[$e])) ||
		            (eregi("<[^>]*cookie*\"?[^>]*>",  $array[$e]))   ||
		            (eregi("\"",  $array[$e]))
		            )
					{
						$rc[$e][$x] = "E00108X";
						$x++;
					}
			}

			// Regex per individuare gli SQL meta-characters
			if(eregi("/(\%27)|(\-\-)|(\%23)|(#)/ix", $array[$e]))
			{
				$rc[$e][$x] = "E00109X";
				$x++;
			}

			// Regex modificata per individuare gli SQL meta-characters
			if(eregi("/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i", $array[$e]))
			{
				$rc[$e][$x] = "E00110X";
				$x++;
			}

			// Regex per gli attacchi SQL Injection comuni
			if(eregi("/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/ix", $array[$e]))
			{
				$rc[$e][$x] = "E00111X";
				$x++;
			}

			// Regex per individuare attacchi SQL Injection con i comandi Sql piu' comuni
			$sql_command = array("select", "insert", "update", "delete", "drop", "union");

			for($c=0; $c<count($sql_command); $c++)
			{
				if(eregi("/((\%27)|(\'))".$sql_command[$c]."/ix", $array[$e]))
				{
					$rc[$e][$x] = "E00112X";
					$x++;
				}
			}

			// Regex per individuare attacchi SQL Injection su MS SQL Server
			if(eregi("/exec(\s|\+)+(s|x)p\w+/ix", $array[$e]))
			{
				$rc[$e][$x] = "E00113X";
				$x++;
			}

			// Regex per gli attacchi CSS semplici
			if(eregi("/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/ix", $array[$e]))
			{
				$rc[$e][$x] = "E00114X";
				$x++;
			}

			// Regex per gli attacchi CSS di tipo "<img src"
			if(eregi("/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/I", $array[$e]))
			{
				$rc[$e][$x] = "E00115X";
				$x++;
			}

			// Regex paranoica per gli attacchi CSS
			if(eregi("/((\%3C)|<)[^\n]+((\%3E)|>)/I", $array[$e]))
			{
				$rc[$e][$x] = "E00116X";
				$x++;
			}

			/*
			* Niente piping, filtra eventuali variabili di sistema ($),
			* separa i comandi, filtra ridirezioni pagina, processi in background
			* commandi speciali (backspace, etc.), quotes, nuova riga e altri caratteri speciali
			*/
			if(eregi("/(;|\||`|>|<|&|^|\"|'.\"\n|\r|'\".'|{|}|[|]|\)|\()/", $array[$e]))
			{
				$rc[$e][$x] = "E00117X";
				$x++;
			}

			// Se e' stato individuato un attacco genera il log
			if(count($rc[$e]) > 0)
			{
				for($k=0; $k<count($rc[$e]); $k++)
				{
					$error  = sumo_get_paranoic_message($rc[$e][$k], $array[$e], $method);
					$server = sumo_get_message('I00001M', $SUMO['server']['name']);
					$object = sumo_get_message('E00107M');

					// Log warning messages with ALL methods
					sumo_write_log('E00107X', array($rc[$e][$k],
									$SUMO['client']['ip'],
									$SUMO['client']['country'],
									$SUMO['page']['url']), 3, 1);
					    
					// Send e-mail detail of warning message
					if($SUMO['config']['log']['errors']['email'])
					{
					    if(!$SUMO['config']['server']['admin']['email'])
					    {
						sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
					    }
					    else {
						$mail= new Mail;
						$mail->From($server);
						$mail->To($SUMO['config']['server']['admin']['email']);
						$mail->Subject($object);
						$mail->Body($error, SUMO_CHARSET);
						$mail->Priority(1);
						$mail->Send();
					    }
					}
				}

				$array[$e] = "";
			}

			// Strippa eventuali spazi all'inizio ed alla fine della stringa
			$array[$e] = trim($array[$e]);

			/*
			* Converte una stringa con caratteri ISO-8859-1 codificati con UTF-8
			* in formato ISO-8859-1 singolo byte.
			* A volte gli attacchi XSS utilizzano l'unicode per mascherare la
			* stringa di attacco.
			*/
			//$array[$e] = utf8_decode($array[$e]);

			// how i get rid of backticks and ;'s using str_replace
			$array[$e] = str_replace("`", "", "$array[$e]");
			// Elimina tutto il codice JavaScript nei tag <a href =''>
			$array[$e] = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?javascript[[:punct:]]*\"?[^>]*>", '', $array[$e]);
			// Remove any HTML and PHP tags if they exist
			$array[$e] = strip_tags($array[$e], $available_tags);
		}
	}

	return $array;
}

/**
 * Function to verify if file exist and if it's readable
 *
 * @param string $file
 * @param boolean $msg
 * @return array, string
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_file($file, $msg=FALSE)
{
	$status = FALSE;
	$error  = '';

	if($file)
	{
		if(file_exists($file))
		{
			if(is_readable($file))
				$status = TRUE;
			else
				$error = 'E00105X';
		}
		else $error = 'E00104X';
	}
	else $error = 'E00103X';

	if(!$status)
	{
		if(!$file) $file = 'Undefined';

		sumo_write_log($error, $file, '0,1', 1);
	}

	return ($msg) ? array($status, sumo_get_message($error, $file)) : $status;
}

/**
 * Checks for valid dotted quad IP address notation.
 * Does not check the connectivity of the IP, only it's format.
 * It does check for common typos like leading zeros or quad
 * notations greater than 255.
 * Return TRUE or FALSE.
 *
 * @param string $ip
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_validate_ip($ip=FALSE)
{
	if($ip)
	{
		$valid = TRUE;
		$ip    = explode(".", $ip);

		if(count($ip) != 4) $valid = FALSE;

		foreach($ip as $block)
		{
			if(ereg("^0+.+", $block) || $block>255 || $block<0 || strlen($block)>3) $valid = FALSE;
		}

		return $valid;
	}
	else return FALSE;
}

/**
 * Validate email address
 * (only checks for valid format: xxx@xxx.xxx)
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_validate_email($email='')
{
	$valid = preg_match("/^[a-z0-9]+[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,4}$/i", $email) ? TRUE : FALSE;

	if(strlen($email) > 100 || strlen($email) < 4) $valid = FALSE;

	return $valid;
}

/**
 * Validate a range of IP's
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_validate_iprange($ip, $log=TRUE)
{
	$ipd = explode("/", $ip);
	$ipp = explode(".", $ipd[0]);

	// ...if IP haven't a range
	if(!$ipd[1]) $ipd[1] = $ipp[3];

	if($ipp[3] > $ipd[1] || ereg("^0+.+", $ipd[1]) || $ipd[1] > 255 || !sumo_validate_ip($ipd[0]))
	{
		if($log) sumo_write_log('W00040X', $ip, '0,1', 2);

		return FALSE;
	}
	else return TRUE;
}

/**
 * Validate group string
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_validate_group($group='', $level=TRUE)
{
	if($group)
	{
		$only_group = ($level) ? FALSE : TRUE;
		$group 	    = sumo_get_normalized_group($group, $only_group);
		$group 	    = explode(';', $group);
		$num_group  = count($group);
		$err 	    = TRUE;

		if($level)
		{
			for($g=0; $g<$num_group; $g++)
			{
				if(!preg_match("/^[[:alpha:]\/\-\_".SUMO_REGEXP_ALLOWED_CHARS."]{2,50}:[1-7]{1}$/i", $group[$g]))				
				{
					$err = FALSE;
					break;
				}
			}
		}
		else
		{
			for($g=0; $g<$num_group; $g++)
			{
				if(!preg_match("/^[[:alpha:]\/\-\_".SUMO_REGEXP_ALLOWED_CHARS."]{2,50}$/i", $group[$g]))
				{
					$err = FALSE;
					break;
				}
			}
		}

		return ($err) ? TRUE : FALSE;
	}
	else return FALSE;
}

/**
 * Get normalized group from a string like:
 * group:access_level; group:access_level; ...
 * and return normalized string.
 *
 * @param string $groups
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_normalized_group($groups='', $group_only=FALSE)
{
	// strip blank, newline, tab, comma
	$groups = strtolower(preg_replace('/[\s\n\t\r\,]+/', ';', $groups));
	$groups = array_unique(explode(';', $groups));

	asort($groups);
	reset($groups);

	$num_groups = count($groups);
	$old_group  = array();

	for($g=0; $g<$num_groups; $g++)
	{
		$gv[$g] = explode(":", $groups[$g]);

		if($group_only)
		{
			if($gv[$g][0]) $group[$g] = $gv[$g][0];
		}
		else
		{
			if(!in_array($gv[$g][0], $old_group) && $gv[$g][0])
			{
				$group[$g] = $gv[$g][0].":".$gv[$g][1];
			}

			$old_group[$g] = $gv[$g][0];
		}
	}

	$groups = is_array($group) ? implode(';', $group) : $group;

	if(preg_match("/sumo:7/i", $groups)) $groups = "sumo:7";
	
	// Alfab. order
	$groups = sumo_get_ordered_groups($groups);

	return $groups;
}

/**
 * Get normalized accesspoint URL
 * (strip port number, etc...)
 *
 * @param string $url
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_normalized_accesspoint($url='')
{
	if($url)
	{
		$url = parse_url($url);
		$url = $url['path'];
	}
	else
	{
		$url = false;
	}

	return $url;
}

/**
 * Validate data
 *
 * See  sumo_validate_data_<module name> for specific validation
 * into library module
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_validate_data($data=array(), $message=FALSE)
{
	$elements = count($data);
	$err 	  = FALSE;

	if($elements > 0)
	{
		for($d=0; $d<$elements; $d++)
		{
			if($data[$d][2] || (!$data[$d][2] && $data[$d][1]))
			{
				switch($data[$d][0])
				{
					// the "user" can be also an e-mail address
					case 'username':
						if(!preg_match('/^[a-z0-9'.SUMO_REGEXP_ALLOWED_CHARS.']{3,100}$/i', $data[$d][1]) && !sumo_validate_email($data[$d][1])) $err = 'W00006C';
						break;

					case 'name':
						if(!preg_match("/^[a-z".SUMO_REGEXP_ALLOWED_CHARS."\&\;\\\'\ ]{1,49}$/i", $data[$d][1])) $err = 'W00022C';
						break;

					case 'password':
						if(!preg_match('/^[\.a-z0-9]{40}$/i', $data[$d][1])) $err = 'W00011C';	 // for sha1 string
						break;

					case 'email':
						if(!sumo_validate_email($data[$d][1])) $err = 'W00007C';
						break;

					case 'active':
						if(!preg_match('/^[0-1]{1}$/', $data[$d][1])) $err = 'W00018C';
						break;

					case 'ip':
						$ip = sumo_get_iprange($data[$d][1]);

						for($i=0; $i<count($ip); $i++)
						{
							if(!sumo_validate_ip($ip[$i])) $err = 'W00016C';
							break;
						}
						break;

					case 'usergroup':
						if(!sumo_validate_group($data[$d][1])) $err = 'W00017C';
						break;

					case 'datasource_id':
						$ds = sumo_get_datasource_info($data[$d][1], false);

						if(empty($ds)) $err = 'W00023C';
						break;

					case 'hostname':
						if(!preg_match('/[a-z0-9\.\_\-]{3,255}$/i', $data[$d][1])) $err = 'W00025C';
						break;

					case 'port':
						if($data[$d][1] < 1 || $data[$d][1] > 65535) $err = 'W00026C';
						break;

					case 'ldap_base':
						if(!preg_match('/^[a-z0-9\.\,\:\;\_\-\=\\\/\+\*\ '.SUMO_REGEXP_ALLOWED_CHARS.']{4,255}$/i', $data[$d][1])) $err = 'W00027C'; 
						break;

					case 'new_password':
						if(!sumo_validate_data(array(array('password', $data[$d][1][0])))) $err = 'W00011C';
						if($data[$d][1][0] != $data[$d][1][1]) $err = 'W00024C';
						break;
						
					// Joomla
					case 'new_password2':
						if($data[$d][1][0] != $data[$d][1][1]) $err = 'W00024C';
						break;

					case 'day_limit':
						if(!preg_match('/^[0-9]{1,4}$/', $data[$d][1])) $err = 'W00020C';
						break;

					case 'language':
						if(!in_array($data[$d][1], sumo_get_available_languages()))
							$err = 'W00021C';
						break;

					case 'id':
						// INT = 256^4-1
						if($data[$d][1] < 1 || $data[$d][1] > 4294967296) $err = 'W00029C';
						break;

					default:
						$err = 'W00019C';
						break;
				}

				if($err) break;
			}
		}

		if($message)
		{
			return (!$err) ? array(TRUE, '') : array(FALSE, sumo_get_message($err));
		}
		else {
			return (!$err) ? TRUE : FALSE;
		}
	}
	else return FALSE;
}

/**
 * Return a range of IP's.
 *
 * Example:
 * an IP like 192.168.0.1/3
 * return an array like this:
 * 192.168.0.1
 * 192.168.0.2
 * 192.168.0.3
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_iprange($ip, $log=FALSE)
{
	if($ip)
	{
		$ip_address = str_replace(",", ";", preg_replace('/[\s\,]+/', ';', $ip));
		$ip_address = explode(";", $ip_address);
		$ip_numbers = count($ip_address);

		for($n=0; $n<=$ip_numbers; $n++)
		{
			$ip_address_range = explode("/", $ip_address[$n]); // get range

			if($ip_address_range[1])
			{
				$ip_address_num   = explode(".", $ip_address_range[0]);

				// ...to control IP error definition. NO log errors!
				if(!sumo_validate_iprange($ip_address[$n], $log) || !$ip_address_range[1]) $ip_address_range[1] = $ip_address_num[3];

				for($i=$ip_address_num[3]; $i<=$ip_address_range[1]; $i++)
				{
					if(intval($ip_address_num[0])) $ip_list[] = intval($ip_address_num[0])."."
															   .intval($ip_address_num[1])."."
															   .intval($ip_address_num[2])."."
															   .$i;
				}
			}
			else
			{
				if(sumo_validate_ip($ip_address[$n])) $ip_list[] = $ip_address[$n];
			}
		}

		return (is_array($ip_list)) ? array_unique($ip_list) : $ip_list;
		//return array_unique($ip_list);
	}
	else return FALSE;
}

/**
 * Get list of private IP's from table SUMO_INTRANET_IP.
 * If an IP number it's specified the function verify only this IP.
 * Warning: this function verify IP range only from database.
 *
 * @global resource $SUMO
 * @param boolean
 * @return array, boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_intranet_ip($ip='', $cache=FALSE)
{
	GLOBAL $SUMO;

	$ip_list = array();
	$query   = "SELECT ip,type FROM ".SUMO_TABLE_INTRANETIP."
				ORDER BY ip,type";

	// Fix unknow IP (maybe througt Proxy)  
	if($ip == "::1") return "PROXY";

	if($ip)
	{
		$ipe = explode("/", $ip);

		if(sumo_validate_ip($ipe[0]))
		{
			$ipp   = explode(".", $ipe[0]);

			$query = "SELECT ip,type FROM ".SUMO_TABLE_INTRANETIP."
					  WHERE ip LIKE '".$ipp[0].".".$ipp[1].".".$ipp[2].".%'
					  ORDER BY ip,type";
		}
		else
		{
			sumo_write_log('W00041X', array($ipe[0], __FUNCTION__), '0,1', 2);

			return "INVALID IP";
		}
	}

	if($cache)
		$rs = $SUMO['DB']->CacheExecute(30, $query);
	else
		$rs = $SUMO['DB']->Execute($query);


	while($ips = $rs->FetchRow())
	{
		// Create IP address list.
 		// Not using sumo_get_iprange() function for performance reason
 		if(ereg("/", $ips['ip']))
 		{
			$ipn = explode("/", $ips['ip']);
			$ipd = explode(".", $ipn[0]);

			// ...to control IP error definition
			if(!sumo_validate_iprange($ips['ip'])) $ipd[3] = $ipn[1];

			for($i=$ipd[3]; $i<=$ipn[1]; $i++)
			{
				switch($ips['type'])
				{
					case 'L': $type = 'LOCAL'; break;
					case 'P': $type = 'PROXY'; break;
				}

				$ip_list[$ipd[0].".".$ipd[1].".".$ipd[2].".".$i] = $type;
			}
 		}
	}


	if($ip)
	{
		return (in_array($ip, array_keys($ip_list))) ? $ip_list["$ip"] : "UNKNOW";
	}
	else return $ip_list;
}

/**
 * Get country name and code from an IP address
 *
 * @global resource $SUMO
 * @param string $ip
 * @return string
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_ip2country_name($ip='')
{
	GLOBAL $SUMO;

	// Get private ip's (proxy, local)
	$country[0] = sumo_get_intranet_ip($ip);
	$country[1] = '';

	if($country[0] == 'UNKNOW')
	{
		$query = "SELECT country_name,country_code2
				  FROM ".SUMO_TABLE_IPTOCOUNTRY."
				  WHERE ".sprintf("%u", ip2long($ip))."
				  BETWEEN ip_from AND ip_to";

			//."WHERE ip_from<=inet_aton('$ip') "
			//."AND ip_to>=inet_aton('$ip')";

		$rs = $SUMO['DB']->CacheExecute(3600, $query);

		$country = $rs->FetchRow();
	}

	return array($country[0], $country[1]);
}

/**
 * Return error message. If specified $data function will
 * replace it in string error.
 *
 * @global array $sumo_lang_core
 * @global array $module
 * @param string $code
 * @param array $data
 * @return string
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_message($code, $data=array())
{
	GLOBAL $SUMO, $sumo_lang_core, $module, $console;

	if(empty($sumo_lang_core)) require_once SUMO_PATH."/languages/".$SUMO['config']['server']['language']."/lang.core.php";
	if(empty($module['language']))   $module['language'] = array();
	if(!empty($console['language'])) $module['language'] = array_merge($console['language'], $module['language']);

	$language = array_merge($sumo_lang_core, $module['language']);

	if(!is_array($data)) $data 	  = array($data);
	if(empty($data)) 	 $data[0] = '';

	$message = $language["$code"];

	if(isset($message))
	{
		$e = count($data);

		if($e > 1)
		{
			for($n=0; $n<$e; $n++)
			{
				if(is_array($data[$n])) $data[$n] = implode(", ", $data[$n]);

				$message = str_replace("{{DATA".$n."}}", $data[$n], $message);
			}
		}
		else
		{
			if(is_array($data[0])) $data[0] = implode(", ", $data[0]);

			$message = str_replace("{{DATA}}", $data[0], $message);
		}
	}

	// Check status for message notice
	//if(substr($message, 0, 1) >= 0 && substr($message, 1, 1) == " ") $message = substr($message, 1, strlen($message));

	return $message;
}

/**
 *  Calculate HMAC-SHA1 according to RFC2104
 *  http://www.ietf.org/rfc/rfc2104.txt
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_hex_hmac_sha1($key, $data)
{
   $blocksize = 64;
   $hashfunc  = 'sha1';

   if(strlen($key) > $blocksize)
       $key = pack('H*', $hashfunc($key));
   $key  = str_pad($key,$blocksize, chr(0x00));
   $ipad = str_repeat(chr(0x36), $blocksize);
   $opad = str_repeat(chr(0x5c), $blocksize);
   $hmac = pack(
               'H*',$hashfunc(
                   ($key^$opad).pack(
                       'H*',$hashfunc(
                           ($key^$ipad).$data
                       )
                   )
               )
           );

   return bin2hex($hmac);
}

/**
 * Function to write and send log via e-mail
 *
 * Accepted TYPE values are:
 * 0: log data to file
 * 1: log data to database
 * 2: send log via e-mail
 * 3: log with ALL methods
 *
 * Accepted PRIORITY values are:
 * 1: Hight
 * 2: Middle
 * 3: Low
 *
 * Accepted NAME values are: system, errors, access
 *
 * @global resource $SUMO
 * @global array $sumo_lang_core
 * @param string $code
 * @param array $data
 * @param string $type
 * @param string $name
 * @param int $priority
 * @param boolean $cycle
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_write_log($code, $data=array(), $type=0, $priority=1, $name='system', $cycle=TRUE)
{
	GLOBAL $SUMO, $sumo_lang_core;

	// controllo parametri
	$data 	    = !is_array($data) ? array($data) : $data;
	$priority   = intval($priority);
	$message    = sumo_get_message($code, $data);
	$time	    = isset($SUMO['server']['time']) ? $SUMO['server']['time'] : time();
	$size_limit = 1024 * $SUMO['config']['logs'][$name]['file']['size'];

	if($size_limit < 4097) $size_limit = 4096;

	if($type == 3) $type = '0,1,2';

	$type = explode(",", $type);

	if($size_limit < 2)
	{
		$size_limit = 2;
		if($cycle) sumo_write_log('W00034X', __FUNCTION__, '0,1', 2, 'system', FALSE);
	}

	if(!$message)
	{
		$message = 'UNDEFINED';
		if($cycle) sumo_write_log('W00035X', __FUNCTION__, '0,1', 2, 'system', FALSE);
	}

	// Priority
	if($priority < 1 || $priority > 3)
	{
		$priority = 1;
		if($cycle) sumo_write_log('W00036X', __FUNCTION__, '0,1', 2, 'system', FALSE);
	}

	switch($name)
	{
		case 'system': $name = 'system'; break;
		case 'errors': $name = 'errors'; break;
		case 'access': $name = 'access'; break;
		default:
			$name = 'system';
			if($cycle) sumo_write_log('W00038X', __FUNCTION__, '0,1', 2, 'system', FALSE);
			break;
	}

	if(!sumo_array_is_inarray($type, array(0,1,2)))
	{
		sumo_write_log('W00037X', __FUNCTION__, '0,1', 2, 'system', FALSE);
	}
	else
	{
		// Log data to file
		if($SUMO['config']['logs']['system']['file']['enabled'] && in_array(0, $type)) 
		{
			$file 	  = SUMO_PATH.'/logs/log.'.$name.'.0.log';
			$file_new = SUMO_PATH.'/logs/log.'.$name.'.1.log';

			// ...to split log in two files
			if(@file_exists($file))
			{
				if(sprintf("%u", @filesize($file)) >= $size_limit)
				{
					if(@file_exists($file)) @unlink($file_new);
					@rename($file, $file_new);
				}
			}

			$str = "[".date($SUMO['config']['server']['date_format']." ".$SUMO['config']['server']['time_format']. " O")."] "
				  ."[".$SUMO['client']['ip']." - ".$SUMO['client']['country']."] "
				  ."[".$code."] ".html_entity_decode($message)."\n";

			$fp = @fopen  ($file, 'a+') OR die (sumo_get_message('E00106X', $file));
	    		  @fwrite ($fp, $str);
		    	  @fclose ($fp);
		}

		// Log data to database
		if(in_array(1, $type))
		{
			$table = FALSE;

			if($SUMO['config']['logs']['system']['database']['enabled'] && $name == 'system') $table = SUMO_TABLE_LOG_SYSTEM;
			if($SUMO['config']['logs']['access']['database']['enabled'] && $name == 'access') $table = SUMO_TABLE_LOG_ACCESS;
			if($SUMO['config']['logs']['errors']['database']['enabled'] && $name == 'errors') $table = SUMO_TABLE_LOG_ERRORS;

			if($table)
			{
			    $query = "INSERT INTO ".$table."
					(priority, code, node, ip, country_name, message, time)
				      VALUES (
					 	".$priority.",
					  	'".$code."',
					  	'".$SUMO['server']['name']."',
					  	'".$SUMO['client']['ip']."',
					  	'".$SUMO['client']['country']."',
					  	'".htmlspecialchars($message, ENT_QUOTES, SUMO_CHARSET)."',
					  	 ".$time."
						)";
				
				$SUMO['DB']->Execute($query);
			}
		}

		// Log data via e-mail
		if($SUMO['config']['logs']['system']['email']['enabled'] && in_array(2, $type))
		{
		    if(!$SUMO['config']['server']['admin']['email'])
		    {
		        sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
		    }
		    else
		    {			
			$m = new Mail; // create the mail
			$m->From($SUMO['config']['server']['admin']['email']);
			$m->To($SUMO['config']['server']['admin']['email']);
			$m->Subject(sumo_get_message("I00001M", $SUMO['server']['name']));
			$m->Body(html_entity_decode($message), SUMO_CHARSET);
			$m->Priority(3);
			$m->Send();
		    }		    
		}
	}
}

/*
function html_compress($html)
{
	preg_match_all('!(<(?:code|pre).*>[^<]+</(?:code|pre)>)!',$html,$pre);#exclude pre or code tags
   
	$html = preg_replace('!<(?:code|pre).*>[^<]+</(?:code|pre)>!', '#pre#', $html);#removing all pre or code tags
	$html = preg_replace('#<!–[^\[].+–>#', '', $html);#removing HTML comments
	$html = preg_replace('/[\r\n\t]+/', ' ', $html);#remove new lines, spaces, tabs
	$html = preg_replace('/>[\s]+</', '><', $html);#remove new lines, spaces, tabs
	$html = preg_replace('/[\s]+/', ' ', $html);#remove new lines, spaces, tabs
  
	if(!empty($pre[0]))
	{
		foreach($pre[0] as $tag)
  			$html = preg_replace('!#pre#!', $tag, $html,1);#putting back pre|code tags
	}
	
	return $html;
}
*/

/**
 * Function to parsing template file
 * if $tpl_file = 1  load template file, not process content.
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_process_template($template_file, $assign, $tpl_file=0, $theme='')
{
	GLOBAL $SUMO, $language;

	if(!$theme) $theme = $SUMO['page']['theme'];
	
	if($tpl_file == 1) 
	{
		$template_file = @file_get_contents(SUMO_PATH.'/themes/'.$theme.'/'.$template_file);	
		/*
		$tpl_file_in  = SUMO_PATH.'/themes/'.$theme.'/'.$template_file;
		$tpl_file_out = SUMO_PATH.'/tmp/templates/'.($template_file);
		
		if (($SUMO['server']['time'] - @filemtime($tpl_file_out)) < 60)
		{
			$template_file = @file_get_contents($tpl_file_out);			
		}
		else 
		{
			$template_file = @file_get_contents($tpl_file_in);
			$template_file = html_compress($template_file);
			
			$fp = @fopen  ($tpl_file_out, 'w+') OR die (sumo_get_message('XXXXXX', $tpl_file_in));
		    	  @fwrite ($fp, $template_file);
				  @fclose ($fp);
		}
		*/
	}

	preg_match_all('/{{.[\/_\-:a-z0-9.]+}}/i', $template_file, $match);

	$e_tpl_new = $element = array();

	foreach($match[0] as $element)
	{
		$e_tpl_new[] = str_replace('{{', '', str_replace('}}', '', $element));
	}

	$num_e_tpl_new = count($e_tpl_new);

	for($el=0; $el<$num_e_tpl_new; $el++)
	{
		// standard template
		if(array_key_exists($e_tpl_new[$el], $assign))
		{
			$str_new = str_replace('{{'.$e_tpl_new[$el].'}}', $assign[$e_tpl_new[$el]], $template_file);
			$template_file = $str_new;
		}
		// language
		elseif(substr($e_tpl_new[$el],0,5) == 'LANG:')
		{
		    $str_new = str_replace('{{'.$e_tpl_new[$el].'}}', $language[str_replace("LANG:","",$e_tpl_new[$el])], $template_file);
		    $template_file = $str_new;
		}
		// ...for
		elseif(substr($e_tpl_new[$el],0,4) == 'TIP:')
		{
		    if($SUMO['config']['console']['tip'])
		    {
			$help = str_replace("'", "&rsquo;", $language[str_replace("TIP:","",$e_tpl_new[$el])]);
			$help = str_replace('"', "&quot;", $help);
			$help = sumo_process_template($help, $assign);
			
			$id  = sumo_get_simple_rand_string();
			$tip = "<img src=\"themes/".$theme."/images/helptip.png\" id='".$id."' "
				."width='11' height='11' style='cursor:pointer;' "
				."onmouseout=\"UnTip();opacity('".$id."', 100, 50, 50)\" "
				."onmouseover=\"Tip('".$help."',DELAY,50,WIDTH,200,SHADOW,true,FADEIN,200,FADEOUT,200,"
				."BORDERCOLOR,'#FFFF99',SHADOWCOLOR,'#947C52',FONTCOLOR,'#000000',BGCOLOR,'#FFFF99',"
				."OPACITY,80,SHADOWWIDTH,3);opacity('".$id."', 50, 100, 50)\">"
				."<script>opacity('".$id."', 100, 50, 1);</script>";
			
			$str_new = str_replace('{{'.$e_tpl_new[$el].'}}', $tip, $template_file);
		    }
		    else
		    {
			$str_new = str_replace('{{'.$e_tpl_new[$el].'}}', '', $template_file);
		    }
		    
		    $template_file = $str_new;
		}
		// template files
		elseif(substr($e_tpl_new[$el],0,5) == 'FILE:')
		{
			$name = pathinfo(str_replace('FILE:', '', $e_tpl_new[$el]));

			if($name['dirname'] == '.')
				$file = SUMO_PATH."/themes/".$theme."/".$name['dirname']."/".$name['basename'];
			else
				$file = SUMO_PATH.$name['dirname']."/".$name['basename'];

			if(file_exists($file))
			{
				$template_new  = file_get_contents($file);
				$str_new       = str_replace("{{".$e_tpl_new[$el]."}}", $template_new, $template_file);
				$str_new       = sumo_process_template($str_new, $assign, $tpl_file, $theme);
				$template_file = $str_new;
			}
			else
			{
				$template_file = "FATAL ERROR: Template &quot;".$file."&quot; not found!";
			}
		}
	}

	return $template_file;
}


/**
 *
 */
function sumo_get_string_languages($l='')
{
	$languages  = array(
			    'it'    => 'italiano',
			    'en'    => 'english',
			    'es'    => 'espa&ntilde;ol',
			    //'us' => 'american',
			    //'de' => 'deutsch',
			    'fr'    => 'fran&ccedil;ais'
			);

	return $l ? $languages[$l] : $languages;
}

/**
 * Get available languages from "SUMO_PATH/languages/" directory
 * and return an array with languages
 * or an HTML selection box if $type=1
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_available_languages($type=0, $autosubmit=0, $default='', $name='language')
{
	GLOBAL $SUMO;

	$dh = @dir(SUMO_PATH.'/languages');

	while($entry = $dh->read())
	{
		if(is_dir(SUMO_PATH.'/languages/'.$entry) && $entry != '.' && $entry != '..')
			$available_lang[] = $entry;
	}

	$dh->close();

	asort($available_lang);
	reset($available_lang);

	if($type == 1)  // HTML format
	{
		$languages  		= sumo_get_string_languages();
		$autosubmit 		= $autosubmit ? " onchange='submit();'" : "";
		$num_available_lang = count($available_lang);

		$language =  "<style>\n"
					."select.icon-menu option {
						background-repeat:no-repeat;
						background-position:bottom left;
						padding-left:25px;
						}";

		for($l=0; $l<$num_available_lang; $l++)
		{
			$language .= "select#countries option[value='{$available_lang[$l]}'] {\n"
						."background-image:url(themes/".$SUMO['page']['theme']."/images/flags/{$available_lang[$l]}.png);\n"
						."}\n";
		}

		$language .= "</style>\n"
					."<select name='".$name."'".$autosubmit." id='countries' class='icon-menu'>\n";

		for($l=0; $l<$num_available_lang; $l++)
		{
			$selected = $available_lang[$l] == $default ? " selected" : "";

			$language .= "<option value='".$available_lang[$l]."'$selected>"
						.ucwords($languages[$available_lang[$l]])
						."</option>\n";
		}

		$language .= "</select>";

		return $language;
	}
	else {
		return $available_lang;
	}
}

/**
 * Verify if a user exist into
 * SUMO_TABLE_USERS or SUMO_TABLE_USERS_TEMP
 *
 * @global resource $SUMO
 * @param string $user
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_user_exist($username='')
{
	GLOBAL $SUMO;

	// execute 2 queries because sometime MySQL return bad value
	$query1 = "SELECT username FROM ".SUMO_TABLE_USERS."
			   WHERE username='".$username."'";
	$query2 = "SELECT username FROM ".SUMO_TABLE_USERS_TEMP."
			   WHERE username='".$username."'";

	$rs1 = $SUMO['DB']->Execute($query1);
	$rs2 = $SUMO['DB']->Execute($query2);

	$num_users = $rs1->PO_RecordCount() + $rs2->PO_RecordCount();

	return ($num_users != 0) ? true : false;
}

/**
 * Convert any element of array to lowercase
 */
function sumo_array_tolower($array)
{
	for($a=0; $a<count($array); $a++)
	{
		$array[$a] = strtolower($array[$a]);
	}

	return $array;
}

/**
 * Verify current group_level
 */
function sumo_verify_current_group_level($level=7, $group='sumo')
{
	GLOBAL $SUMO;

	$permit 	 = false;
	$level 		 = intval($level);
	$group  	 = is_array($group) ? $group : array($group);
	$group 		 = sumo_array_tolower($group);
	$group_match = array_values(sumo_array_is_inarray($SUMO['user']['group'], $group, true));

	if(!empty($group_match))
	{
		for($g=0; $g<count($group_match); $g++)
		{
			if($SUMO['user']['group_level'][$group_match[$g]] >= $level) $permit = true;
		}
	}
	else
	{
		if(in_array('sumo', $SUMO['user']['group'])) $permit = true;
	}

	return $permit;
}

/**
 * Verify if user permission
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_current_user($username='sumo')
{
	GLOBAL $SUMO;

	if(!is_array($username)) $username = array($username);

	return (sumo_array_is_inarray($SUMO['user']['user'], $username) ||
			$SUMO['user']['user'] == 'sumo') ? true : false;
}

/**
 * Verify if node is local
 */
function sumo_verify_node_local($node='')
{
	GLOBAL $SUMO;

	$node  = !$node ? $SUMO['server']['ip'] : $node;
	$local = array('127.0.0.1',
		       'localhost',
		       $SUMO['server']['name'],
		       $SUMO['server']['ip']);

	return in_array($node, $local) ? true : false;
}

/**
 * Verify if Node it's active
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_node($node='', $cache=TRUE, $time=30)
{
	GLOBAL $SUMO;

	if(!$node)
	{
	    $query = "SELECT active FROM ".SUMO_TABLE_NODES."
		      WHERE (host='".$SUMO['server']['ip']."'
			     OR  
			     host='".$SUMO['server']['name']."')";
	}
	else
	{
	    $query = "SELECT active FROM ".SUMO_TABLE_NODES."
			WHERE host='".$node."'";
	}

	if($cache)
		$rs = $SUMO['DB']->CacheExecute($time, $query);
	else
		$rs = $SUMO['DB']->Execute($query);

	$node = $rs->FetchRow();

	return ($node['active'] == 1 || sumo_verify_node_local($node['host'])) ? true : false;
}

/**
 * Verify current user group with page group
 *
 * @global resource $SUMO
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_current_group_page()
{
	GLOBAL $SUMO;

	return (sumo_verify_current_group($SUMO['page']['group'])) ? true : false;
}

/**
 * Verify if Path is a Sumo Console
 *
 * @return boolean
 * @author Alberto Basso
 */
function sumo_verify_is_console($path='')
{
	$sumo_path = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', SUMO_PATH);

	if(
	   $path == $sumo_path.'/index.php' ||
	   $path == $sumo_path.'/services.php' ||
	   $path == $_SERVER['PHP_SELF']
	  )
	    return TRUE;
	else
	    return FALSE;
}

/**
 * Verify current group permission for current user
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_current_group($group='sumo')
{
	GLOBAL $SUMO;

	$group = is_array($group) ? $group : array($group);

	return (sumo_array_is_inarray($SUMO['user']['group'], $group) ||
		in_array('sumo', $SUMO['user']['group'])) ? true : false;
}

/**
 * Convert XML data to PHP array
 */
function sumo_xml_toarray($data='', $cache=FALSE)
{
	if($cache)
	{
		$file = SUMO_PATH."/tmp/xmlcache_".md5($data);
		/*
		// write cache
		$fp = @fopen  ($file, 'w') OR die (sumo_get_message('E00106X', $file));
	    	  @fwrite ($fp, sumo_xml_toarray($data, 'FALSE'));
			  @fclose ($fp);
		*/
	}
	else
	{
		$params = $level = array();
		$xml_parser = xml_parser_create();

		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);

		foreach ($vals as $xml_elem)
		{
			if ($xml_elem['type'] == 'open')
			{
			   if (array_key_exists('attributes', $xml_elem))
			   		list($level[$xml_elem['level']], $extra) = array_values($xml_elem['attributes']);
			   else
			     	$level[$xml_elem['level']] = $xml_elem['tag'];
			}

			if ($xml_elem['type'] == 'complete')
			{
			   $start_level = 1;
			   $php_stmt    = '$params';

			   while($start_level < $xml_elem['level'])
			   {
			   		$php_stmt .= '[$level['.$start_level.']]';
			     	$start_level++;
			   }

			   $php_stmt .= '[$xml_elem[\'tag\']]=$xml_elem[\'value\'];';

			   @eval($php_stmt);
			}
		}
	}

	return $params;
}

/**
 * Convert PHP array to XML data
 */
function sumo_array_toxml($array=array(), $charset='UTF-8', $header=true, $s=true, $t="")
{
	$xml = $s ? "<?xml version=\"1.0\" encoding=\"".$charset."\"?>\n" : "";

	foreach($array as $key => $value)
	{
		if(is_string($key))
		{
			if(is_array($value))
			 	$xml .= $t."<".$key.">\n"
			 		 .sumo_array_toxml($value, '', false, false, $t."\t")
			 		 .$t."</".$key.">\n";
			else
				$xml .= $t."<".$key.">".$value."</".$key.">\n";
		}
	}

	if($header)
	{
		header('Content-type: text/xml; charset='.$charset);
		header('Content-length: '.strlen($xml));

		echo $xml;
	}
	else
	{
		return $xml;
	}
}

/**
 * Verify current user permissions
 *
 * @global resource $SUMO
 * @param  int    $level
 * @param  array  $group
 * @param  array  $user
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_permissions($level=false, $group=false, $user=false, $log=true)
{
	GLOBAL $SUMO;

	$permit = false;

	// verify all conditions
	if($level && $group && $user)
	{
		if(sumo_verify_current_group_level($level, $group) &&
		   sumo_verify_current_user($user)) $permit = true;
	}

	//verify group and level
	if($level && $group && !$user)
	{
		if(sumo_verify_current_group_level($level, $group)) $permit = true;
	}

	// verify group and user
	if(!$level && $group && $user)
	{
		if(sumo_verify_current_group($group) &&
		   sumo_verify_current_user($user)) $permit = true;
	}

	// verify only group
	if(!$level && $group && !$user)
	{
		if(sumo_verify_current_group($group)) $permit = true;
	}

	// verify only user
	if(!$level && !$group && $user)
	{
		if(sumo_verify_current_user($user)) $permit = true;
	}

	// Access violations log
	if(!$permit && $SUMO['config']['security']['access_violations'] && $log)
	{
		if(is_array($group)) $group = implode(",", $group);
		
		sumo_write_log('E00122X',
					array($SUMO['user']['user'],
					"[MODULE: {$_SESSION['module']} ACTION:{$_SESSION['action']} USER:$user GROUP:$group LEVEL:$level]"),
					'0,1', 2,
					'errors', FALSE);
	}

	return $permit;
}

/**
 * Verify if an email address exist into
 * SUMO_TABLE_USERS or SUMO_TABLE_USERS_TEMP
 *
 * @global resource $SUMO
 * @param string $email
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_email_exist($email='')
{
	GLOBAL $SUMO;

	$query1 = "SELECT email FROM ".SUMO_TABLE_USERS."
		    WHERE email='".$email."'";
	$query2 = "SELECT email FROM ".SUMO_TABLE_USERS_TEMP."
		    WHERE email='".$email."'";

	$rs1 = $SUMO['DB']->Execute($query1);
	$rs2 = $SUMO['DB']->Execute($query2);

	$num_users = ($rs1->PO_RecordCount() + $rs2->PO_RecordCount());

	return ($num_users != 0) ? true : false;
}

/**
 * Return an HTML string to load JavaScript file
 *
 * @param string $name
 * @param string $options
 * @return string
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_script_tag($file='', $options='', $archive='')
{
	$script = '<!-- ERROR: Undefined script filename -->';

	if($file)
	{
		GLOBAL $SUMO;
		
		if($archive)
		{
			$archive = "archive='".$SUMO['page']['web_path']."scripts/".$archive."'";
			$file	 = $file;
		}
		else 
		{
			$archive = '';
			$file    = $SUMO['page']['web_path']."scripts/".$file;
		}
		
		$script = "<script language='javascript' type='text/javascript' "
				 .$archive." src='".$file."' ".$options.">"
			     ."</script>";
	}

	return $script;
}

/**
 * Dynamic hits counter
 *
 * type = FALSE  return hits value
 * type = TRUE   return if count=hits
 *
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_hits_count($count=100, $type=false)
{
	if(intval($count) > 0)
	{
		$file = SUMO_PATH.'/tmp/hits/hits.'.$count;
		$hits = 0;
	
		// Read hits
		if(file_exists($file))
		{
			$fp   = fopen ($file, 'r+') OR die (sumo_get_message('E00105X', $file));
	   		$hits = fgets ($fp, 4096);
	    		    fclose($fp);
		}
	
		$hits++;
		
		// Write hits
		$fp = fopen ($file, 'w+') OR die (sumo_get_message('E00106X', $file));
	
		if($hits == $count)
			fwrite($fp, '0');
		else
			fwrite($fp, $hits);
	
		fclose($fp);
	
		if(!$type)
			return $hits;
		else
		{
			return $hits == $count ? true : false;
		}
	}
	else return false;
}

/**
 * Delete unused hits counters
 *
 * @param intval $timeout
 * default: 1 day
 */
function sumo_optimize_hits_counter($days=1)
{
	$dh = dir(SUMO_PATH.'/tmp/hits/');

	while($entry = $dh->read())
	{
		if(substr($entry, 0, 5) == 'hits.')
		{
			$file = SUMO_PATH.'/tmp/hits/'.$entry;
			if(filemtime($file) < $days*86400) unlink($file);
		}
	}

	$dh->close();
}

/**
 *  - Make default temporary dirs
 *  - Create index.html and htacces on dirs to hide contents
 */
function sumo_create_enviroenment()
{
	$dirs = array(
			'/logs/',
			'/tmp/',
			'/tmp/hits/',
			'/tmp/database/',
			'/tmp/sessions/',
			'/tmp/profiles/'
		    );

	echo "<html>\n"
	   . "<head>\n"
	   . " <title>SUMO ERROR</title>\n"
	   . "<style>\n"
	   . "BODY  { background-color: #FFFFFF; margin: 10px; padding: 10px; }\n"
	   . "TABLE { background-color: #FFFFDD; width: 100%; border: 2px solid #BB5555; padding: 10px; color: #000000; "
	   . "font-family: \"Trebuchet MS\", Tahoma, Arial, Helvetica, sans-serif; font-size: 13px; }\n"
	   . "H1 { font-weight:normal;font-size:18pt;color:maroon }\n"
	   . "H2 { font-weight:normal;font-size:14pt;color:red }\n"
	   . ".note { font-size:12px;color:#444444; }\n"
	   . "</style>\n"
	   . "</head>\n"
	   . "<body>\n"
	   . "<table>\n"
	   . " <tr><td>"
	   . "<h1>SUMO INSTALLATION</h1>\n"
	   . "<hr width='100%' size='1' color='#CCAABB'>"
	   //. "\n<h2>". $error[0] . "</h2>\n"
	   . "\n<h4>This is first SUMO boot, trying to create required directories...<br><br></h4>\n";


	for($d=0; $d<count($dirs); $d++)
	{
		if(!file_exists(SUMO_PATH.$dirs[$d])) mkdir(SUMO_PATH.$dirs[$d]);
		chmod(SUMO_PATH.$dirs[$d], 0775); // "mode" on mkdir fail

		$index    = SUMO_PATH.$dirs[$d]."index.html";
		$htaccess = SUMO_PATH.$dirs[$d].".htaccess";

		$fp = fopen ($index, 'w+');
			  fwrite($fp, '');
			  fclose($fp);

		$fp = fopen ($htaccess, 'w+');
			  fwrite($fp, "<Files ~ \"*\">\n\tOrder allow,deny\n\tDeny from all\n</Files>\n");
			  fclose($fp);
	}

	$fp = fopen (SUMO_PATH.'/.installed', 'w+');
		  fwrite($fp, date("YmdHis"));
		  fclose($fp);

	if(file_exists(SUMO_PATH.'/.installed'))
		echo "<font color='#00BB00'>Directories created successfully!</font>"
			."<br><br>Remember to use <b>sumo</b> as username and password for first login"
			."<br><br>Please <b><a href='?'>restart</a></b> this page.<br><br>";
	else
		echo "<font color='#BB0000'><b>ERROR: Required directories not created!</b></font>"
			."<br>Please verify if webserver user can write into ".SUMO_PATH
			." directory (on Linux rwxrwx--- or 770 permissions)!<br><br>";


	echo "<hr width='100%' size='1' color='#CCAABB'>"
	    ."\n<font class='note'><i>SUMO Access Manager<br>&copy; Copyright 2003-".date("Y")." by Basso Alberto</i></font>"
	    ."</td></tr>\n"
	    ."</table>\n"
	    ."</body>\n"
	    ."</html>\n";

	exit;
}

/**
 * Auto optimize Sumo tables (only for MySQL database)
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_optimize_db()
{
	GLOBAL $SUMO;

	$tables = array(
			SUMO_TABLE_CONFIGS,
			SUMO_TABLE_SESSIONS_STORE,
			SUMO_TABLE_ACCESSPOINTS,
			SUMO_TABLE_ACCESSPOINTS_STATS,
			SUMO_TABLE_BANNED,
			SUMO_TABLE_CONNECTIONS,
			SUMO_TABLE_DATASOURCES,
			SUMO_TABLE_GROUPS,
			SUMO_TABLE_INTRANETIP,
			//SUMO_TABLE_IPTOCOUNTRY,
			SUMO_TABLE_LOG_ACCESS,
			SUMO_TABLE_LOG_ERRORS,
			SUMO_TABLE_LOG_SYSTEM,
			SUMO_TABLE_NODES,
			SUMO_TABLE_SESSIONS,
			SUMO_TABLE_USERS,
			SUMO_TABLE_USERS_IMAGES,
			SUMO_TABLE_USERS_TEMP
			);


	switch($SUMO['server']['db_type'])
	{
		// MySQL
		case 'mysql':

		    for($t=0; $t<count($tables); $t++)
		    {
		        $query1 = "OPTIMIZE TABLE ".$tables[$t];
		        $query2 = "ANALYZE TABLE ".$tables[$t];

		        $SUMO['DB']->Execute($query1);
		        $SUMO['DB']->Execute($query2);
		    }
		    break;
		    
		// Oracle
		case 'oracle':

		    for($t=0; $t<count($tables); $t++)
		    {
			$query = "ANALYZE TABLE ".$tables[$t]." COMPUTE STATISTICS";

		        $SUMO['DB']->Execute($query);
		    }
		    break;

		// SQLite
		case 'sqlite':
		    
		    for($t=0; $t<count($tables); $t++)
		    {
		        $query = "VACUUM ".$tables[$t];

		        $SUMO['DB']->Execute($query);
		    }
		    break;
		
		// PostgreSQL
		case 'postgres':
			
		    for($t=0; $t<count($tables); $t++)
		    {
		        $query = "REINDEX TABLE ".$tables[$t];

		        $SUMO['DB']->Execute($query);
		    }
		    break;
	}

	// Flush (delete) any cached recordsets in $ADODB_CACHE_DIR
	$SUMO['DB']->CacheFlush();
}

/**
 * Load PHP extension
 */
function sumo_dl($ext=false)
{
    if($ext && ini_get('enable_dl') && function_exists('dl'))
    {
        if(!extension_loaded($ext))
    	{
	    $ext_file = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'php_'.$ext.'.dll' : $ext.'.so';

	    return dl($ext_file);
	}
	    else return TRUE;
    }
    else return FALSE;
}

?>
