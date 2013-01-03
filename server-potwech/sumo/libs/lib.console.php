<?php
/**
 * SUMO CONSOLE FUNCTIONS LIBRARY
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
 * Get available modules
 */
function sumo_get_available_modules()
{
	$dir = SUMO_PATH.'/modules';

	foreach(scandir($dir) as $entry)
	{
		if($entry != '.' && $entry != '..')
		{
		   if(is_dir($dir.'/'.$entry)) $modules[] = $entry;
		}
	}
    
	sort($modules);

	return $modules;
}


/**
 * Get avaible group
 */
function sumo_get_available_group()
{
	GLOBAL $SUMO;

	$query = "SELECT usergroup FROM ".SUMO_TABLE_GROUPS."
			ORDER BY usergroup";

	$rs = $SUMO['DB']->Execute($query);

	$group[0] = 'sumo';

	while($tab = $rs->FetchRow())
	{
		$group[] = $tab[0];
	}

	return $group;
}


/**
 * Enter description here...
 *
 * @param unknown_type $groups
 * @param unknown_type $separator
 */
function sumo_get_ordered_groups($groups='', $separator=';')
{
	$groups = explode($separator, $groups);
	
	sort($groups);
	
	return implode($separator, $groups);
}


/**
 * Get a human readable date from timestamp
 */
function sumo_get_human_date($timestamp=0, $hours=TRUE, $seconds=FALSE)
{
	if($timestamp > 0)
	{
		GLOBAL $language;

		$today 	   = date("Ymd");
		$yesterday = date("Ymd", strtotime("-1 day"));
		$tomorrow  = date("Ymd", strtotime("+1 day"));
		$day	   = date("Ymd", $timestamp);

		if($hours)
		{
			if($seconds)
			{
				$hours = "H:i:s";
				$time  = "%d %B %Y, %H:%M:%S";
			}
			else
			{
				$hours = "H:i";
				$time  = "%d %B %Y, %H:%M";
			}

			if($day == $today)
				return $language['Today'].", ".date($hours, $timestamp);
			if($day == $yesterday)
				return $language['Yesterday'].", ".date($hours, $timestamp);
			if($day == $tomorrow)
				return $language['Tomorrow'].", ".date($hours, $timestamp);
			else
				return strftime($time, $timestamp);
		}
		else
		{
			if($day == $today)
				return $language['Today'];
			if($day == $yesterday)
				return $language['Yesterday'];
			if($day == $tomorrow)
				return $language['Tomorrow'];
			else
				return strftime("%d %B %Y", $timestamp);
		}
	}
	else return FALSE;
}


/**
 *
 */
function sumo_get_formatted_username($firstname='', $lastname='')
{
	$username = ' ';

	if($lastname  && $firstname)  $username = $lastname.", ".$firstname;
	if($lastname  && !$firstname) $username = $lastname;
	if(!$lastname && $firstname)  $username = $firstname;

	return htmlspecialchars($username, ENT_QUOTES);
}


/**
 * Create SQL to select only groups for current user
 *
 * @return string
 */
function sumo_get_group_query($search=FALSE, $group_level=FALSE)
{
	GLOBAL $SUMO;

	$level = $group_level ? ':_' : '';

	if(!in_array('sumo', $SUMO['user']['group']))
	{
		$sql    = $search ? " AND (" : " WHERE (";
		$groups = count($SUMO['user']['group']);

		for($g=0; $g<$groups; $g++)
		{
			$sql .= "usergroup LIKE '".$SUMO['user']['group'][$g].$level."'
					  OR usergroup LIKE '".$SUMO['user']['group'][$g].$level.";%'
					  OR usergroup LIKE '%;".$SUMO['user']['group'][$g].$level.";%'
					  OR usergroup LIKE '%;".$SUMO['user']['group'][$g].$level."'";

			if($g < $groups-1) $sql .= " OR ";
		}

		$sql .= ") ";

		return $sql;
	}
	else return FALSE;
}


/**
 * Return an array with available groups for user
 */
function sumo_get_user_available_group($username='', $html=FALSE)
{
	GLOBAL $SUMO;

	if(!$username) $username = $SUMO['user']['user'];

	if(sumo_validate_data(array(array('username', $username, 1))))
	{
		$query = "SELECT usergroup FROM ".SUMO_TABLE_USERS."
				  WHERE username='".$username."'";

		$rs  = $SUMO['DB']->Execute($query);
		$tab = $rs->FetchRow();

		$group_level = explode(";", $tab[0]);

		if($html)
			return sumo_get_user_grouplevel($group_level);
		else
		{
			for($g=0; $g<count($group_level); $g++)
			{
				$group_data = explode(":", $group_level[$g]);
				$group_name  = $group_data[0];
				$group_value = $group_data[1];

				if($group_name == 'sumo')
				{
					$query = "SELECT usergroup FROM ".SUMO_TABLE_GROUPS."
							  ORDER BY usergroup";

					$rs = $SUMO['DB']->CacheExecute(3600, $query);

					$group_level   = array();
					$group_level[] = 'sumo:'.$group_value;

					while($tab = $rs->FetchRow())
					{
						$group_level[] = $tab[0].":7";
					}

					break;
				}
			}

			return $group_level;
		}
	}
	else return FALSE;
}


/**
 * Function to create SQL query
 * for search a string into a field of database
 */
function sumo_search_composer($search='', $field='', $operator='OR')
{
	// Set default serch operator
	$operator = in_array($operator, array('AND', 'OR', 'NOT')) ? strtoupper($operator) : 'OR';

	// Create array of words to search
	$new_word  = array_unique(explode(' ', $search));
	$num_words = count($new_word);
	$quotes    = get_magic_quotes_gpc();
	$query     = '';

	for($w=0; $w<=$num_words; $w++)
	{
		if(strlen($new_word[$w])>1) $new_word2[] = $new_word[$w];
	}

	$num_words2 = count($new_word2);

	for($w=0; $w<$num_words2; $w++)
	{
		$nw = $quotes ? $new_word2[$w] : addslashes($new_word2[$w]);

		$query .= " ".$field." LIKE '%".$nw."%' ";

		if($w < $num_words2-1) $query .= $operator;
	}

	return array($query, $new_word2);
}


/**
 * Get group description
 */
function sumo_get_group_description($group='')
{
	if($group)
	{
		GLOBAL $SUMO;

		$query = "SELECT description FROM ".SUMO_TABLE_GROUPS."
				  WHERE usergroup='".$group."'";

		$rs = $SUMO['DB']->CacheExecute(60, $query);

		if($rs)
			$desc = $rs->FetchRow();
		else
			$desc[0] = '';

		return $desc[0];
	}
	else return FALSE;
}


/**
 * Get available themes (for console and accesspoints)
 */
function sumo_get_available_themes()
{
	$dh = @dir(SUMO_PATH.'/themes');

	while($entry = $dh->read())
	{
		if(is_dir(SUMO_PATH.'/themes/'.$entry) && $entry != '.' && $entry != '..')
		{
			$themes[] = $entry;
		}
	}

	$dh->close();

	natsort($themes);

	return $themes;
}


/**
 * Get available manpages for module
 */
function sumo_get_available_manpages($lang='')
{
	GLOBAL $SUMO;

	$dh = dir(SUMO_PATH.'/modules/manpages/pages/'.$lang.'/');

	$manpages = array();

	while($entry = $dh->read())
	{
		if(!in_array($entry, array('.','..','index.html','.htaccess')))
		{
			$man = explode('.', $entry);

			$manpages[] = $man[0].'.'.$man[1];
		}
	}

	$dh->close();

	natsort($manpages);

	return $manpages;
}


/**
 * Get available data sources
 */
function sumo_get_available_datasources($onlydb=false)
{	
	if($onlydb)
		return array(
				'MySQL',				
				'Postgres',
				'Oracle',
				'MySQLUsers',
				'Joomla15'
			);
	else
		return array(
				 'SUMO',
				 'LDAP',
				 'LDAPS',
				 'ADAM',
				 'MySQL',					 
				 'Postgres',
				 'Oracle',
				 'MySQLUsers',
				 'Joomla15',
				 'GMail',
				 'Unix'					 
			);
}


/**
 * Get alternate string.
 * (ex. to alternate color string)
 *
 * @param string $str1
 * @param string $str2
 * @return string
 */
function sumo_alternate_str($s1='', $s2='', $string=false)
{
	if(!$string)
	{
		static $str;

		$str = $str == $s1 ? $s2 : $s1;

		return $str;
	}
	else
	{
		static $string;

		$string = $string == $s1 ? $s2 : $s1;

		return $string;
	}
}


/**
 * Create a bar graph
 *
 * Mode:
 * 0: only graph
 * 1: graph + value (inside the graph)
 * 2: graph + percent (inside the graph)
 * 3: graph + value
 * 4: graph + percent
 */
function sumo_get_graph($value=100, $max=0, $mode=0, $style='blue', $width=150, $height="")
{
	$p = $max ? intval($value * 100 / $max) : intval($value * 100);

	// ...because queries cache data and
	// values aren't updated
	if($p > 100) $p = 100;

	switch($mode)
	{
		case 0:  $val = ''; 	$val2 = ''; break;
		case 1:  $val = $value; $val2 = ''; break;
		case 2:  $val = $p.'%'; $val2 = ''; break;
		case 3:  $val = ''; 	$val2 = "  <td>".number_format($value, 0, '', '.')."&nbsp;&nbsp;</td>\n";  break;
		case 4:  $val = ''; 	$val2 = "  <td>".$p."%&nbsp;&nbsp;</td>\n";  break;
	}

	return "<table cellpadding='0' cellspacing='0' width='$width' height='$height'>\n"
		  ." <tr>\n"
		  .$val2
		  ."  <td class='bar-".$style."' style='width:".$p."%;' title='".$value."'>".$val."</td>\n"
		  ."  <td class='bar-silver' style='width:".(100-$p)."%;'></td>\n"
		  ." </tr>\n"
		  ."</table>\n";
}


/**
 *
 */
function sumo_get_action_link($module='', $action='', $visibility=false)
{	
	GLOBAL $SUMO, $language;

	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];

	$visible1 = $visibility ? 1 : 0;
	$visible2 = $visibility ? "" : "obj.style.height=obj.offsetHeight;"
					."obj.style.overflow=\"hidden\";"
					."obj.style.display=\"none\";";
	
	$open = $visibility ? 'desc' : 'asc';
        $over = "this.style.border=\"1px solid #AAAAAA\";opacity(\"mlink.$m.$a\", 60, 100, 100);";
        $out  = "this.style.border=\"1px solid transparent\";opacity(\"mlink.$m.$a\", 100, 60, 100);";
		
	return "<div class='sub-module' onmouseover='$over' onmouseout='$out' "
		."onclick='javascript:ShowHideSubModule(\"".$m.".".$a."\");'>"
		.$language[$a]
		."<div style='position:relative;top:-10px;text-align:right'>"
		."<img id='mlink.$m.$a' hspace='10' width='10' height='10' src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/open.gif'>"
		."</div>"
		."<input type='hidden' name='".$a."_visibility' value='".$visible1."'>"
		."</div>\n"
		."<div id='".$m.".".$a."'>\n"
		."<script>setTimeout('var obj=document.getElementById(\"".$m.".".$a."\");$visible2',20);opacity(\"mlink.$m.$a\", 100, 60, 100);</script>";
}


/**
 * Enter description here...
 *
 * @param unknown_type $module
 * @param unknown_type $action
 * @param unknown_type $window
 * @param unknown_type $link
 * @return unknown
 */
function sumo_get_action_icon($module='', $action='', $window='', $link='')
{
	GLOBAL $SUMO, $language;

	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];
	$w = $window ? $window : $m;

	$link1 = $link ? "onclick='javascript:sumo_ajax_get(\"$w\",\"$link\");'" : "";
	
	$active = $a == $_SESSION['action'] || !$link ? "style='color: #999999'" : "";
	$hover  = $a != $_SESSION['action'] || !$link ? "onmouseover='this.style.border=\"1px solid #999999\";this.style.background=\"#FFFFFF\"' "
    	 				 		   ."onmouseout='this.style.border=\"1px solid transparent\";this.style.background=\"\"'" : "";
	
	return "<div class='sub-module-icon' $active $hover $link1>"
		."<img src='themes/".$SUMO['page']['theme']."/images/modules/".$m."/".$a.".png' vspace='4'><br>"
		.$language[$a]
		."</div>";
}


/**
 * Enter description here...
 *
 * @param unknown_type $module
 * @param unknown_type $action
 * @param unknown_type $message
 * @param unknown_type $level
 * @param unknown_type $form
 * @param unknown_type $confirm
 * @param unknown_type $autoclose
 * @param unknown_type $b1
 * @param unknown_type $b2
 * @param unknown_type $b3
 * @return unknown
 */
function sumo_get_message_icon($module='', $action='', $message='', $level='h', $form='', $confirm='', $autoclose=0, $b1='', $b2='', $b3='')
{
    GLOBAL $SUMO, $language;

    $name      = sumo_get_simple_rand_string();
    $message   = htmlspecialchars($message);
    $level     = strtolower($level);
    $autoclose = intval($autoclose);
    $forms     = base64_encode($form);
    $button1   = base64_encode($b1);
    $button2   = $b2 ? base64_encode($b2) : base64_encode("<input type='button' value='".$language['Cancel']."' onclick='javascript:sumo_remove_window(\"$name\");' class='button'>");
    $button3   = $b3 ? base64_encode($b3) : base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"$name\");' class='button'>");
    
    $link = "onclick=\"javascript:sumo_show_message('$name', '$message', '$level', $autoclose, '$form', '$button1', '$button2', '$button3');\"";
    
    $m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];
		
	$active = $a == $_SESSION['action'] || !$message ? "style='color: #999999'" : "";
	$hover  = $a != $_SESSION['action'] || !$message ? "onmouseover='this.style.outline=\"1px solid #999999\";this.style.background=\"#FFFFFF\"' "
    	  								 		      ."onmouseout='this.style.outline=\"\";this.style.background=\"\"'" : "";
	
    return "<div class='sub-module-icon' $active $hover $link>"
    	  ."<img src='themes/".$SUMO['page']['theme']."/images/modules/".$m."/".$a.".png' vspace='4'><br>"
	      .$language[$a]
	      ."</div>";
}



/**
 * Put accesspoint name
 *
 * @param unknown_type $names
 * @return unknown
 */
function sumo_put_accesspoint_name($form='0', $names='')
{
	GLOBAL $SUMO;

	$languages = sumo_get_available_languages();
	$flags     = "";
	
	for($l=0; $l<count($languages); $l++)
	{
		$flags .= "<input type='hidden' name='name[".$languages[$l]."]' value='".$names[$languages[$l]]."'>"
				 ."&nbsp;<a onclick='document.forms[\"$form\"].elements[\"ap_name\"].value=document.forms[\"$form\"].elements[\"name[".$languages[$l]."]\"].value;document.forms[\"$form\"].elements[\"ap_name_id\"].value=\"".$languages[$l]."\";' href='#'>"
				 ."<img src='themes/".$SUMO['page']['theme']."/images/flags/".$languages[$l].".png'>"				
				 ."</a>";		
	}
		
	return  "<input type='text' size='33' name='ap_name' "
		   ."onChange='document.forms[\"$form\"].elements[\"name[\"+document.forms[\"$form\"].elements[\"ap_name_id\"].value+\"]\"].value=document.forms[\"$form\"].elements[\"ap_name\"].value' "
		   ."value='".$names[$_COOKIE['language']]."'>"
		   ."<input type='hidden' name='ap_name_id' value='".$_COOKIE['language']."'>"
		   .$flags."<br>\n";
}



/**
 * Create selection box of available themes
 */
function sumo_put_themes($default='', $name='theme')
{
	$themes 	= sumo_get_available_themes();
	$num_themes = count($themes);

	$list = "<select name='$name'>\n";

	if($default) $list .= "<option value='$default'>".ucwords($default)."</option>\n";

	for($t=0; $t<$num_themes; $t++)
	{
		if($themes[$t] != $default) $list .= "<option value='".$themes[$t]."'>".ucwords($themes[$t])."</option>\n";
	}

	$list .= "</select>";

	return $list;
}


/**
 *  Get http contents
 *
 *  Note: replace function file_get_contents() to set
 *  also timeout connection
 */
function sumo_get_http_contents($host='', $path='/', $port=80, $protocol='http', $timeout=5, $size=128)
{
	$hostname = $protocol=='https' ? 'ssl://'.$host : $host;
	$path 	  = str_replace("//", "/", $path);

	$fp = fsockopen($hostname, $port, $errno, $errstr, $timeout);

	if (!is_resource($fp))
		$content = strip_tags($errstr)." (RC ".$errno.")<br>\n";
	else
	{
	   $header = "GET $path HTTP/1.1\r\n"
	   		."Accept: */*\r\n"
	   		."Host: $host\r\n"
	   		."Connection: Keep-Alive\r\n"
	   		."User-Agent: Socket-PHP-browser 1.0\r\n"
	   		."Connection: close\r\n\r\n";
		
	   fputs($fp, $header);

	   while(!feof($fp))
	   {
	       $content = fgets($fp, $size);
	   }

	   fclose($fp);
	}

	return $content;
}


/**
 * Create export data button
 */
function sumo_get_export_data($module=NULL, $action=NULL, $export=NULL)
{
	GLOBAL $language;

	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];
	$e = $export ? $export : 'export';

	return "<div id='GetData".$m.$a."'>\n"
		."<input type='button' class='button' value='".$language['Export']."' "
		."onclick='javascript:ShowHideElement(\"Export".$m.$a."\");"
		."HideElement(\"GetData".$m.$a."\");'>"
		."</div>\n"
		."<div id='Export".$m.$a."' style='visibility:hidden;position:absolute'>\n"
		."<table style='white-space:nowrap;'><tr><td>"
		.$language['Export'].":&nbsp;"
		."</td><td>"
		."<form name='Export' "
		."action='?module=$m&submodule=$a&action=$e' "
		."method='POST'>\n"
		."<select name='type' onchange='if(this.value!=\"\"){submit();}'>\n"
		."<option value=''></option>\n"
		."<option value='xls'>".$language['xls']."</option>\n"
		."<option value='csv'>".$language['csv']."</option>\n"
		."<option value='csvdump'>".$language['csvdump']."</option>\n"
		."</select>\n"
		."</form>\n"
		."</td></tr></table>"
		."</div>";
}


/**
 * Get module link
 */
function sumo_get_module_link($module=NULL, $action=NULL, $name='', $icon=true, $icon_name='')
{
	GLOBAL $SUMO;

	$m = $module    ? $module    : $_SESSION['module'];
	$a = $action    ? $action    : $_SESSION['action'];
	$i = $icon_name ? $icon_name : 'icon.window.png';

	$name = $name ? $name : $m;
	$img  = "<img src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/modules/$m/$i' "
	       ."align='left' class='menu-top-icon' alt='&bull;'>";

	if(!$icon) $img = '';

	return "<a href=\"javascript:sumo_ajax_get('$m','?module=$m&action=$a');\">".$img.$name."</a>";
}


/**
 * Get module icon
 */
function sumo_get_module_icon($module=NULL, $action=NULL, $name='', $on_desktop=TRUE, $icon_name='', $decoration=true)
{
	global $SUMO, $console, $desktop;
	
	$m = $module     ? $module    : $_SESSION['module'];
	$a = $action     ? $action    : $_SESSION['action'];
	$i = $icon_name  ? $icon_name : 'icon.desktop';
	$d = $decoration ? 'true'     : 'false';

	$name = $name ? $name : $m;
	$img  = $SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/modules/".$m."/".$i.".png";
	$img2 = $SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/icon_background.png";

	list($width, $height) = getimagesize($img);

	if($on_desktop)
	{
        if(empty($desktop['settings'][$m]['xi']))
        {
        	static $xi, $yi;

        	$title_w = intval(strlen($name)*3.5/2.2);

        	if($yi > 400)
        	{
        		$yi = 0;
        		$xi = $xi*2 + $width - $title_w;
        	}

       		$xi = $xi>(40 - $title_w) ? $xi : 40 - $title_w;
			$yi = $yi==0 ? 60 : $yi + $height + 30;
        }
        else
        {
        	$xi = $desktop['settings'][$m]['xi'];
        	$yi = $desktop['settings'][$m]['yi'];
        }

        // 
        $xi = $xi < 0  ? 100 : $xi;
        $yi = $yi < 25 ? 100 : $yi;
        
        $opacity = !$_SESSION['splashscreen'] ? "document.getElementById('Icon$m').style.visibility='hidden';"
						."setTimeout(\"document.getElementById('Icon$m').style.visibility='visible';\", 5000);\n" : "";
        
	return "\n<!-- ICON: ".$m." -->\n"
		."<div id='Icon$m'\n\t"
		."onmouseover='this.style.backgroundImage=\"url($img2)\";' \n\t"
		."onmouseout='this.style.backgroundImage=\"\";' \n\t"
        	."onmouseup='javascript:sumo_save_icon_settings(\"{$SUMO['user']['user']}\", \"$m\");' \n\t"
		."ondblclick=\"javascript:sumo_ajax_get('$m','?module=$m&action=$a&decoration=$d');\" "
        	."style='left:".$xi."px;top:".$yi."px;' class='IconDesktop'>\n\t"
		."<img src='$img' "
		."onmouseover=\"Tip('<b>".ucwords($name)."</b>: ".$console['language']['TOOLTIP:'.$m]."'"
		.",DELAY,1200,WIDTH,200,SHADOW,true,FADEIN,300,FADEOUT,200,BORDERCOLOR,'#FFFF99',"
		."SHADOWCOLOR,'#947C52',FONTCOLOR,'#000000',BGCOLOR,'#FFFF99',OPACITY,80,SHADOWWIDTH,3)\" "
		."onmouseout=\"UnTip()\" "
		."height='$height' width='$width' alt='$m'>"
		."<br><div>".$name."</div>\n"
		."</div>\n"
		."<script type='text/javascript'>\n"
		."\tADD_DHTML('Icon$m'+TRANSPARENT+MAXOFFTOP+".($yi-25)."+MAXOFFLEFT+".$xi."+RESET_Z);\n"
		.$opacity
		."</script>\n";
	}
	else
	{	// on cascade menu
		return "<center>\n"
			."<div class='IconWindow' "
			."onmouseover='this.style.outline=\"1px solid #E9E9E9\";this.style.background=\"#EFEFEF\";' \n\t"
			."onmouseout='this.style.outline=\"none\";this.style.background=\"transparent\";' \n\t"
			."ondblclick=\"javascript:sumo_ajax_get('$m','?module=$m&action=$a&decoration=$d');\">"
			."<img src='$img' height='$height' width='$width' alt='$m'>"
			."<br><div>".$name."</div>\n"
			."</div>\n"
			."</center>";
	}
}


/**
 * Reload last window opened
 */
function sumo_get_module_start($module=NULL, $action=NULL)
{
	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];

	$_SESSION['wincontent'][$m] = FALSE;

	$timeout = isset($_SESSION['splashscreen']) ? 1000 : 3000;
	
	return "<script language='javascript' type='text/javascript'>"
		  ."setTimeout('sumo_ajax_get(\"$m\",\"?module=$m&action=$a\");', $timeout);"
		  ."</script>\n";
}


/**
 * xmlize() is by Hans Anderson, www.hansanderson.com/contact/
 *
 * Ye Ole "Feel Free To Use it However" License [PHP, BSD, GPL].
 * some code in xml_depth is based on code written by other PHPers
 * as well as one Perl script.  Poor programming practice and organization
 * on my part is to blame for the credit these people aren't receiving.
 * None of the code was copyrighted, though.
 *
 * This is a stable release, 1.0.  I don't foresee any changes, but you
 * might check http://www.hansanderson.com/php/xml/ to see
 *
 * usage: $xml = xmlize($xml_data);
 *
 * See the function traverse_xmlize() for information about the
 * structure of the array, it's much easier to explain by showing you.
 * Be aware that the array is very complex.  I use xmlize all the time,
 * but still need to use traverse_xmlize or print_r() quite often to
 * show me the structure!
 *
 */
function sumo_xmlize($data, $white=1) 
{
    $data   = trim($data);
    $vals   = $index = $array = array();
    $parser = xml_parser_create();

    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, $white);

    if(!xml_parse_into_struct($parser, $data, $vals, $index))
    {
		die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($parser)),
                    xml_get_current_line_number($parser)));
    }

    xml_parser_free($parser);

    $i = 0;

    $tagname = $vals[$i]['tag'];

    $array[$tagname]['@'] = isset($vals[$i]['attributes']) ? $vals[$i]['attributes'] : array();
    $array[$tagname]["#"] = sumo_xml_depth($vals, $i);

    return $array;
}


/**
 * You don't need to do anything with this function, it's called by
 * xmlize.  It's a recursive function, calling itself as it goes deeper
 * into the xml levels.  If you make any improvements, please let me know.
 */
function sumo_xml_depth($vals, &$i) 
{
    $children = array();

    if ( isset($vals[$i]['value']) )
    {
        array_push($children, $vals[$i]['value']);
    }

    while (++$i < count($vals))
    {
        switch ($vals[$i]['type'])
        {
           case 'open':

           		$tagname = isset($vals[$i]['tag'])    ? $vals[$i]['tag'] 			: '';
           		$size    = isset($children[$tagname]) ? sizeof($children[$tagname]) : 0;

                if(isset($vals[$i]['attributes']))
                {
                    $children[$tagname][$size]['@'] = $vals[$i]["attributes"];
                }

                $children[$tagname][$size]['#'] = sumo_xml_depth($vals, $i);

            	break;


            case 'cdata':
                array_push($children, $vals[$i]['value']);
            	break;

            case 'complete':
                $tagname = $vals[$i]['tag'];
		$size    = isset($children[$tagname]) ? sizeof($children[$tagname]) : 0;
		$children[$tagname][$size]["#"] = isset($vals[$i]['value']) ? $vals[$i]['value'] : '';

                if(isset($vals[$i]['attributes']))
                {
                    $children[$tagname][$size]['@'] = $vals[$i]['attributes'];
                }

            	break;

            case 'close':
                return $children;
            	break;
        }

    }

	return $children;
}


/**
 * Get menu tab for module
 */
function sumo_get_module_menu($tabs=array(), $current_tab='', $module='')
{
	if(!empty($tabs[$current_tab])) $tabs = $tabs[$current_tab];

	$m = $module ? $module : $_SESSION['module'];

	$num_tabs = count($tabs);

	$menu = "<div id='menu-module'>\n<ul>\n";

	for($t=0; $t<$num_tabs; $t++)
	{
		$m = $tabs[$t]['module']  ? $tabs[$t]['module']    : $m;
		$a = $tabs[$t]['action']  ? $tabs[$t]['action']    : '';
		$s = $tabs[$t]['actions'] ? $tabs[$t]['actions']   : '';
		$o = $tabs[$t]['query']   ? "&".$tabs[$t]['query'] : '';
		$c = $m != 'manpages'     ? '.content' 		   : '';
		$d = $m != 'manpages'     ? '&decoration=false'    : '';

		$onclick = "javascript:sumo_ajax_get(\"$m".$c."\",\"?module=$m".$d."&action=".$a.$o."\");";
		$style   = (($current_tab == $a && $m != 'manpages') || in_array($current_tab, $s)) ? "id='current-tab'" : "onmouseover='this.id=\"current-tab\"' onmouseout='this.id=\"\"'";
		$style2	 = $m == 'manpages' ? " style='cursor:help'" : '';
		
		$menu   .= "<li $style onclick='$onclick'>"
				  ."<a href='#'$style2>".$tabs[$t]['name']."</a>"
				  ."</li>\n";
	}

	$menu .= "</ul>\n</div>";

	return $menu;
}


/**
 * Get info of group
 *
 * @author Alberto Basso
 */
function sumo_get_group_info($value=FALSE, $record='id', $cache=FALSE)
{
	GLOBAL $SUMO;

	if($value)
	{
		switch(strtolower($record))
		{
			case '':
			case 'id':
				$record = "id";
				$value  = intval($value);
				break;
			case 'usergroup':
				$record = "usergroup";
				$value  = "'".$value."'";
				break;
		}

		$query = "SELECT * FROM ".SUMO_TABLE_GROUPS."
				  WHERE ".$record."=".$value;

		if($cache)
			$rs = $SUMO['DB']->CacheExecute(60, $query);
		else
			$rs = $SUMO['DB']->Execute($query);

		$group_data = $rs->FetchRow();

		return $group_data;
	}
}


/**
 * Get html list of group_level
 */
function sumo_get_user_grouplevel($group_level=FALSE)
{
	if($group_level)
	{
		$num_groups = count($group_level);
		$group 	    = array_keys($group_level);
		$value	    = array_values($group_level);
		$list 	    = '';

		for($g=0; $g<$num_groups; $g++)
		{
			$style = sumo_alternate_str('tab-row-on', 'tab-row-off');

			if($group[$g])
			{
				$list .= "<tr>\n"
					." <td class='$style'>".$group[$g]."</td>\n"
					." <td class='$style'>".sumo_get_group_description($group[$g])."</td>\n"
					." <td class='$style'>".intval($value[$g])."</td>\n"
					."</tr>\n";
			}
		}

		return $list;
	}
	else return FALSE;
}


/**
 * Splitting query results in multiple pages
 *
 * @author Alberto Basso
 */
function sumo_paging_results($num_rows, $visible_rows_from_query, $visible_rows, $num_links=10, $start, $start_title="start", $action="")
{
	GLOBAL $SUMO, $language;

	$result = '';

	// Generate pages if necessary
	if ($num_rows >= $visible_rows_from_query && $num_rows>0)
	{
		// Calculate number of pages to view rows
		$pages = round(($num_rows / $visible_rows), 0);

		if($pages >= 1) $result .= "<table cellpadding='0' class='paging'>\n"
						." <tr class='paging'>\n";

		$uri = sumo_array_filter(explode("&", $_SERVER['REQUEST_URI']), 'GET');
		
		// PATCH 
		if($action)
		{
			for($u=0; $u<count($uri); $u++)
			{
				if(substr($uri[$u], 0, 7) == 'action=') $uri[$u] = "action=$action";
			}
		}
		
		$uri = ereg_replace('&amp;+('.$start_title.'=[0-9]+)', '', implode("&amp;", $uri));

		// To remember past query into request url
		$query_string = $_SERVER['QUERY_STRING'] ? $uri."&amp;" : "?";


   		// Display back link if necessary
	    if($start > 0)
	    {
		$result .= "<td class='paging'>"
				."<a href='javascript:sumo_ajax_get(\"".$_SESSION['module'].".content\",\"".$query_string.$start_title."=0&decoration=false\");'>"
				."<img src='themes/".$SUMO['page']['theme']."/images/paging-first.gif' alt='".$language['First']."'>"
				."</a>"
				."</td>\n"
				."<td class='paging'>"
				."<a href='javascript:sumo_ajax_get(\"".$_SESSION['module'].".content\",\"".$query_string.$start_title."=".((($start/$visible_rows)-1)*$visible_rows)."&decoration=false\");'>"
				."<img src='themes/".$SUMO['page']['theme']."/images/paging-back.gif' style='padding-right:5px;text-align:middle' alt='".$language['Back']."'>"
				.$language['Back']
				."</a>"
				."</td>\n";
	    }

		$page_start = $start > 0 ? $start/$visible_rows : 1;

		// Print page numbers
		for($p=$page_start; $p<=$num_links+$page_start; $p++)
		{
			if(($p * $visible_rows) < $num_rows)
			{
				if($start/$visible_rows == $p)
				{
					// if it's current page
					$result .= "<td class='paging-on'>".($p+1)."</td>\n";
				}
				else
				{
					$result .= "<td class='paging'>"
							  ."<a href='javascript:sumo_ajax_get(\"".$_SESSION['module'].".content\",\""
							  .$query_string.$start_title."=".($p*$visible_rows)."&decoration=false\");'>".($p+1)."</a>"
							  ."</td>\n";
				}
			}
		}
   		// to correct last message number
   		if($start < ($num_rows - $visible_rows))
   		{
			$result .= "<td class='paging'>"
					  ."<a href='javascript:sumo_ajax_get(\"".$_SESSION['module'].".content\",\"".$query_string.$start_title."=".($start + $visible_rows)."&decoration=false\");'>"
					  .$language['Next']."<img src='themes/".$SUMO['page']['theme']."/images/paging-next.gif' style='padding-left:5px;text-align:middle' alt='".$language['Next']."'>"
					  ."</a>"
					  ."</td>\n"
					  ."<td class='paging'>"
					  ."<a href='javascript:sumo_ajax_get(\"".$_SESSION['module'].".content\",\"".$query_string.$start_title."=".(($pages-1)*$visible_rows)."&decoration=false\");'>"
					  ."<img src='themes/".$SUMO['page']['theme']."/images/paging-last.gif' alt='".$language['Last']."'>"
					  ."</a>"
					  ."</td>\n";
		}

		if($pages >= 1) $result .= "</tr>\n</table>";
	}

	return $result;
}


/**
 *  Function to colorize words that macth with a string
 */
function sumo_color_match_string($word, $string, $bgcolor='#FEFE99')
{
	if(!is_array($word)) $word = array($word);

	for($w=0; $w<count($word); $w++)
	{
		$word2[$w] = str_replace("/", "\/", $word[$w]);

		if(strlen($word2[$w]) > 1)
		{
			$string = preg_replace("/$word2[$w]/i", "<font style='background-color:$bgcolor;'>".$word[$w]."</font>", $string);
		}
	}

	return $string;
}


/**
 *  Show module window
 */
function sumo_show_window($name='main', $title='', $tpl_file='', $tpl_array=array(), $decoration=true, $icon='', $minwin='', $maxwin='')
{
	GLOBAL $SUMO;

	$m      = $_SESSION['module'];
	$name   = str_replace('-', '', $name);
	$icon   = $icon   ? $icon : 'icon.window.png';
	$minwin = $minwin ? 'ShowElement("minwin'.$m.'");' : '';
	$maxwin = $maxwin ? 'ShowElement("maxwin'.$m.'");' : '';

	$tpl1 = SUMO_PATH.'/themes/'.$SUMO['page']['theme'].'/'.$m.'.'.$tpl_file.'.tpl';
	$tpl2 = SUMO_PATH.'/themes/'.$SUMO['page']['theme'].'/'.$tpl_file.'.tpl';
	$tpl3 = SUMO_PATH.'/modules/'.$m.'/templates/'.$tpl_file.'.tpl';
	$tplW = SUMO_PATH.'/themes/'.$SUMO['page']['theme'].'/window.tpl';

	$tpl = $tpl1;

	if(!file_exists($tpl1))
	{
		$tpl = $tpl2;

		if(!file_exists($tpl2))
		{
			$tpl = $tpl3;

			if(!file_exists($tpl3)) die("FATAL ERROR: Template ".$tpl." not found!");
		}
	}

	$tpl_module = implode('', file($tpl));

	// decoration
	if($decoration)
	{
		if(!file_exists($tplW)) die("FATAL ERROR: Template ".$tplW." not found!");

		$tpl_window = implode('', file($tplW));

		$tpl_array2 = array(
					'GET:WindowModule'   => $tpl_array['GET:WindowModule'] ? $tpl_array['GET:WindowModule'] : $m,
					'GET:WindowTitle'    => $tpl_array['GET:WindowTitle'] ? $tpl_array['GET:WindowTitle'] : $title,
					'GET:WindowContent'  => sumo_process_template($tpl_module, $tpl_array),
					'GET:WindowElement'  => $tpl_array['GET:WindowElement'] ? $tpl_array['GET:WindowElement'] : $m.$name,
					'GET:PagePath'	     => $SUMO['page']['web_path'],
					'GET:PageTheme'	     => $SUMO['page']['theme'],
					'GET:WindowMinimize' => "sumo_minimize_window(\"$m\");",
					'GET:WindowMaximize' => "sumo_maximize_window(\"$m\");",
					'GET:WindowClose'    => $tpl_array['GET:WindowClose'] ? $tpl_array['GET:WindowClose'] : "sumo_remove_window(\"$m\");",
					'GET:SaveWindowSettings' => $tpl_array['GET:SaveWindowSettings'] ? $tpl_array['GET:SaveWindowSettings'] : "sumo_save_window_settings(\"".$SUMO['user']['user']."\", \"$m\", 1);",
					'IMG:WindowIcon'    	 => "<img src='themes/".$SUMO['page']['theme']."/images/modules/$m/".$icon."' "
						  		   ."alt='&bull;' align='middle' hspace='3'>"
				);

		$win = sumo_process_template($tpl_window, $tpl_array2);
	}
	else
	{
		$win = sumo_process_template($tpl_module, $tpl_array);
	}	

	echo $win
		."<script>"
		."setTimeout('windowFocus(\"$m\");".$minwin.$maxwin."', 100);"
		.$tpl_array['GET:WindowScripts']
		.$tpl_array['MESSAGE']
		."</script>";
}


/**
 *  Save window module position
 */
function sumo_save_window_settings($username='', $module='', $x=0, $y=0, $status=0)
{
	$m = $module ? $module : $_SESSION['module'];
	$x = intval($x);
	$y = intval($y);
	$c = false;
	
	$user_data = sumo_get_console_settings($username);
	
	// window position
	if($x > 0 && $user_data[$m]['xw'] != $x) 
	{ 
		$user_data[$m]['xw'] = $x; 
		$c = true; 
	}
	if($y > 0 && $user_data[$m]['yw'] != $y) 
	{ 
		$user_data[$m]['yw'] = $y; 
		$c = true; 
	}
	
	// 
	if($status != $user_data[$m]['s'])
	{
		$user_data[$m]['s'] = $status ? 1 : 0;
		
		$c = true;
	}
	
	if($c) 
	{
		sumo_write_ini_file(SUMO_PATH.'/tmp/profiles/'.$username.'.ini', $user_data);
	}
}


/**
 *  Save icon position
 */
function sumo_save_icon_settings($username='', $module='', $x=0, $y=0)
{
	$m = $module ? $module : $_SESSION['module'];
	$x = intval($x);
	$y = intval($y);
	
	$user_data = sumo_get_console_settings($username);
	
	if($x > 0 && $y > 0 && ($user_data[$m]['xi'] != $x || $user_data[$m]['yi'] != $y))
	{	
		$user_data[$m]['xi'] = $x;
		$user_data[$m]['yi'] = $y;

		sumo_write_ini_file(SUMO_PATH.'/tmp/profiles/'.$username.'.ini', $user_data);
	}
}


/**
 * Update application settings
 */
function sumo_update_config($name='', $data=array())
{		
	if(!empty($data)) 
	{		
		GLOBAL $SUMO;

		// Server
		if($name == 'server') 
		{
			$xml['config'] = array_merge($SUMO['config'], $data);
			
			$xml['config']['server']['version'] = SUMO_VERSION;
			$xml['config']['server']['updated'] = $SUMO['config']['server']['updated'];
			$xml['config']['server']['charset'] = $SUMO['config']['server']['charset'];
		}
				
		// Create XML
		$xml_data = sumo_array_toxml($xml, $SUMO['config']['server']['charset'], FALSE);
		
		// Fix: prevent database optimization hits too low
		if($name == 'server') 
		{
			if($xml['config']['database']['optimize_hits'] < 1000) $xml['config']['database']['optimize_hits'] = 1000;
		}
		
				
		$query = "UPDATE ".SUMO_TABLE_CONFIGS." 
			  SET data='".addcslashes($xml_data, "'")."'
		  	  WHERE name='".$name."'";
				
		$SUMO['DB']->Execute($query);
		$SUMO['DB']->CacheFlush();
		
		sumo_write_log('I06001X', array($name, $SUMO['user']['user']), 3, 3);		
		
		return TRUE;
	}
	else return FALSE;
}


/**
 *  Get console settings (windows and icons positions)
 */
function sumo_get_console_settings($profile='')
{
	GLOBAL $SUMO;

	$p = $profile ? $profile : $SUMO['user']['user'];
	
	$profile   = SUMO_PATH.'/tmp/profiles/'.$p.'.ini';
	$user_data = file_exists($profile) ? parse_ini_file($profile, true) : false;

	return $user_data;
}


/**
 * Write ini file
 *
 * @param unknown_type $file
 * @param unknown_type $assoc_array
 * @return unknown
 */
function sumo_write_ini_file($file, $assoc_array)
{
   $content = $sections = '';

   foreach ($assoc_array as $key => $item)
   {
       if (is_array($item))
       {
           $sections .= "\n[{$key}]\n";

           foreach ($item as $key2 => $item2)
           {
               if (is_numeric($item2) || is_bool($item2))
                   $sections .= "{$key2}={$item2}\n";
               else
                   $sections .= "{$key2}=\"{$item2}\"\n";
           }
       }
       else
       {
           if(is_numeric($item) || is_bool($item))
               $content .= "{$key}={$item}\n";
           else
               $content .= "{$key}=\"{$item}\"\n";
       }
   }

   $content .= $sections;

   if(!$handle = fopen($file, 'w')) return false;
   if(!fwrite($handle, $content))   return false;

   fclose($handle);

   return true;
}


/**
 *  Get splashscreen
 */
function sumo_get_splashscreen($timeout=4000)
{
	GLOBAL $SUMO;

	$timeout = intval($timeout) < 800 ? 4000 : intval($timeout);

	$splashscreen = '';

	if(!isset($_SESSION['splashscreen']))
	{
		$splashscreen = "<div id='splashscreen' style='position:absolute;left:35%;top:20%;z-index:10000000'>\n"
				."<img src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/splashscreen.png'"
				." id='splashscreen' alt='Loading...'>\n"
				."</div>\n"
				."<script type='text/javascript'>\n"
				."var oldcolor = document.bgColor;\n"
				."document.body.style.backgroundColor='black';\n"
				."opacity('desktop', 100, 50, 1);\n"
				."setTimeout(\"opacity('splashscreen', 100, 0, 2000);"
				."document.getElementById('splashscreen').style.zIndex=-1;"
				."opacity('desktop', 50, 100, 2000);"
				."document.body.style.backgroundColor=oldcolor;\", $timeout);\n"
				."</script>\n";

		$_SESSION['splashscreen'] = TRUE;
	}

	return $splashscreen;
}


/**
 * Get form name
 *
 * @param unknown_type $module
 * @param unknown_type $action
 * @return unknown
 */
function sumo_get_form_name($module='', $action='')
{
	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];
	
	return ucfirst($a).ucfirst($m);
}


/**
 *  Create form tag with correct module call
 */
function sumo_get_form_req($module='', $action='', $parameters='', $method='POST', $options='')
{
    $m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];

    switch(strtoupper($method))
    {
    	case '':
        case 'POST': $method = 'POST'; break;
        case 'GET':  $method = 'GET';  break;
    }

    if(preg_match("/onsubmit\=/i", $options))
	{
		$end = substr($options, strlen($options)-1, 1);
		$sep = $end == "'" ? "\"" : "'";

		$onsubmit = substr($options, 0, strlen($options)-1).";sumo_ajax_post(".$sep.$m.".content".$sep.",this);return false;".$end;
		$options  = "";
	}
	else
	{
		$onsubmit = "onSubmit=\"javascript:sumo_ajax_post('$m.content',this);return false;\"";
	}

    $form = "<form method='$method' "
           ."action='?module=$m&action=$a&$parameters&decoration=false' "
           .$onsubmit
           ." name='".sumo_get_form_name($m, $a)."' $options>\n";

    return $form;
}


/**
 *  Generate available languages list and flag
 */
function sumo_get_flags()
{
	GLOBAL $SUMO;

    $languages = sumo_get_available_languages();

    $flags = "<div id='menuLanguages' class='menu'>\n";

    for($l=0; $l<count($languages); $l++)
    {
        if($languages[$l] != $_COOKIE['language'])
        {
        	$lang   = sumo_get_string_languages($languages[$l]);
        	$flags .= "<a href='?sumo_lang=".$languages[$l]."'>"
        		     ."<img src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/flags/".$languages[$l].".png'"
                	 ." class='flag' alt='".ucwords($lang)."'>&nbsp;".ucwords($lang)
                 	 ."</a>\n";
        }
    }

    $flags .= "</div>"
    		 ."<div onmouseover='dropdownmenu(this, event, \"menuLanguages\")'>"
    		 ."<img src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/flags/".$_COOKIE['language'].".png' "
             ."alt='".ucwords(sumo_get_string_languages($_COOKIE['language']))."' "
             ."class='flag'>"
             ."</div>";

    return $flags;
}


/**
 * Create Search form
 */
function sumo_get_form_search($searched='', $module=NULL, $action=NULL)
{
	GLOBAL $language;

	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];

	$name	  = "search_".$m."_".$a;
	$searched = !$searched ? $language['Search'] : htmlspecialchars($searched, ENT_QUOTES);
	$style	  = $searched==$language['Search'] ? 'search' : 'searching';
	$reset	  = $searched==$language['Search'] ? '' : "<td class='search-repeat'><img src='themes/sumo/images/reset.gif' onclick='javascript:document.getElementById(\"".$name."\").value=\"\";document.getElementById(\"".$name."\").focus();this.style.visibility=\"hidden\";'></td>";
	
	return sumo_get_form_req($m, $a)
		  ."<input type='hidden' value='true' name='reset_".$name."' />\n"
		  ."<table><tr><td><div class='search-left'></div></td>"
		  ."<td class='search-repeat'>"		  		  
		  ."<input type='text' class='$style' size='30' "
		  ."name='".$name."' "
		  ."id='".$name."' "
		  ."onclick='if(this.value==\"".$language['Search']."\"){this.value=\"\"}this.style.color=\"#333333\"' "
		  ."value='$searched' autocomplete='off' />"		  
		  ."</td>"
		  .$reset
		  ."<td><div class='search-right'></div></td></tr></table>"		  
		  ."</form>";
}


/**
 * Set table settings
 */
function sumo_set_table_settings($module=NULL, $action=NULL)
{
	GLOBAL $_GET, $_POST, $table;

	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];
	$t = $table['settings'][$a] ? $table['settings'][$a] : $table['settings'];

	$t['mode'] = strtoupper($t['mode'])=='DESC' ? 1 : 0;

	$_SESSION[$m][$a]['oc']	  	  = isset($_SESSION[$m][$a]['oc']) ? $_SESSION[$m][$a]['oc'] : $t['col'];
	$_SESSION[$m][$a]['om']	  	  = isset($_SESSION[$m][$a]['om']) ? $_SESSION[$m][$a]['om'] : $t['mode'];
	$_SESSION[$m][$a]['oc'] 	  = isset($_GET['oc']) ? intval($_GET['oc']) : $_SESSION[$m][$a]['oc'];
	$_SESSION[$m][$a]['om'] 	  = isset($_GET['om']) ? intval($_GET['om']) : $_SESSION[$m][$a]['om'];
	$_SESSION[$m][$a]['col_sql']  = $_SESSION[$m][$a]['oc'];
	$_SESSION[$m][$a]['mode_sql'] = $_SESSION[$m][$a]['om'] ? 'DESC' : 'ASC';
	$_SESSION['start_'.$m.'_'.$a] = isset($_GET['start_'.$m.'_'.$a]) ? intval($_GET['start_'.$m.'_'.$a]) : $_SESSION['start_'.$m.'_'.$a];
	$_SESSION['rows_'.$m.'_'.$a]  = isset($_POST['rows_'.$m.'_'.$a]) ? intval($_POST['rows_'.$m.'_'.$a]) : $_SESSION['rows_'.$m.'_'.$a];

	if(isset($_POST['search_'.$m.'_'.$a]) || $_POST['reset_'.$m.'_'.$a] || !isset($_SESSION['start_'.$m.'_'.$a]))
	{
    	if(!preg_match('/^[a-z0-9\-\_\':\.\/\ '.SUMO_REGEXP_ALLOWED_CHARS.']{2,150}$/i', $_POST['search_'.$m.'_'.$a]))
		{
			$_POST['search_'.$m.'_'.$a] = '';
		}

		$_SESSION['search_'.$m.'_'.$a] = $_POST['search_'.$m.'_'.$a];
		$_SESSION['start_'.$m.'_'.$a]  = 0;
	}

	if(!$_SESSION['rows_'.$m.'_'.$a])   $_SESSION['rows_'.$m.'_'.$a]  = $t['rows'];
	if($_POST['reset_'.$m.'_rows_'.$a]) $_SESSION['start_'.$m.'_'.$a] = 0;
}


/**
 *  Get form to change table settings
 */
function sumo_get_table_settings($columns=array(), $module=NULL, $action=NULL)
{
	GLOBAL $language;

	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];

	$settings = "<div id='ChangeTableSettings".$m.$a."'>\n"
		   ."<input type='button' class='button' value='".$language['TableSettings']."' "
		   ."onclick='javascript:ShowHideElement(\"TableSettings".$m.$a."\");"
		   ."HideElement(\"ChangeTableSettings".$m.$a."\");sumo_unrefresh_window(\"$m\");'>"
		   ."</div>\n"
		   ."<div id='TableSettings".$m.$a."' style='visibility:hidden;position:absolute'>\n"
		   ."<table style='white-space:nowrap;'>\n"
		   ."<tr><td>"
		   .sumo_get_form_req($m, $a)
		   .$language['View'].":&nbsp;"
		   ."<input type='hidden' value='true' name='reset_".$m."_".$a."' />\n"
		   ."<input type='text' class='small' size='3' name='rows_".$m."_".$a."' "
		   ."value='".$_SESSION['rows_'.$m.'_'.$a]."' />&nbsp;"
		   ."<select name='".$m."_".$a."_view_col' />\n"
		   ." <option class='comment'>".$language['SelectCols']."</option>\n"
		   ." <option value='999'>&nbsp;&nbsp;&nbsp;&nbsp;".$language['SelectAllCols']."</option>\n"
		   ." <option class='comment'>------------------</option>\n";

	for($c=0; $c<count($columns); $c++)
	{
		$id	 = $columns[$c]['id'];
		$name    = strip_tags($columns[$c]['name']) ? $columns[$c]['name'] : $language['UNDEFINED'];
		$enabled = '&nbsp;&nbsp;';
		$change  = 1;

		if(isset($_SESSION[$m][$a]['col'][$id]))
		{
			$enabled = $_SESSION[$m][$a]['col'][$id] ? '&bull;' : '&nbsp;&nbsp;';
			$change  = $_SESSION[$m][$a]['col'][$id] ? 0 : 1;
		}

		$settings .= " <option value='".$id.".$change'>"
				.$enabled."&nbsp;&nbsp;".$name
				."</option>\n";
	}

	$settings .= "</select>&nbsp;<input type='submit' value='".$language['Ok']."' class='button'>"
			."</form>\n</td></tr></table>\n</div>";

	return $settings;
}


/**
 * Get Table header
 *
 * @param unknown_type $columns
 * @param unknown_type $module
 * @param unknown_type $action
 * @return unknown
 */
function sumo_get_table_header($columns=array(), $module=NULL, $action=NULL)
{
	global $SUMO;
	
	$m = $module ? $module : $_SESSION['module'];
	$a = $action ? $action : $_SESSION['action'];

	$header = "<table>\n<tr>\n";

	// Set default column
	if(!empty($_REQUEST[$m.'_'.$a.'_view_col']))
	{
		if ($_REQUEST[$m.'_'.$a.'_view_col'] == 999)
		{
			// All columns
			for($cl=1; $cl<20; $cl++) $_SESSION[$m][$a]['col'][$cl] = 1;
		}
		else
		{
			$col = explode(".", $_REQUEST[$m.'_'.$a.'_view_col']);

			$_SESSION[$m][$a]['col'][$col[0]] = $col[1] ? 1 : 0;
		}
	}

	for($c=0; $c<count($columns); $c++)
	{
		$column   = $columns[$c]['id'];
		$view     = $columns[$c]['visible'];
		$name     = $columns[$c]['name']	? $columns[$c]['name'] : '&nbsp;';
		$attrib   = $columns[$c]['attributes']	? $columns[$c]['attributes'] : '';
		$sortable = $columns[$c]['sortable']	? true : false;

		// Get if column is defined, if not verify if exist and set to view
		if(!isset($view))
		{
			$view = $column ? 1 : 0;
		}
		// View column (set default)
		if(!isset($_SESSION[$m][$a]['col'][$column]))
		{
			$_SESSION[$m][$a]['col'][$column] = $view;
		}
		// Set default session column
		if(!isset($_SESSION[$m][$a]['col'][$column]))
		{
			$_SESSION[$m][$a]['col'][$column] = $order;
		}

		if($_SESSION[$m][$a]['col'][$column])
		{
			$style[$column] = $_SESSION[$m][$a]['col_sql']==$column ? 'tab-title-on' : 'tab-title';
						
			if($sortable)
			{				
				$mode  = strtolower($_SESSION[$m][$a]['mode_sql']);
				$order = $mode == 'desc' ? 0 : 1;
				$over  = $icon = '';
			
				if($_SESSION[$m][$a]['col_sql'] == $column)
				{
					$mode2  = $mode=='asc' ? 'desc' : 'asc';
					$over   = " onmouseover=\"document.getElementById('order.$m.$a').src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/order_".$mode2.".gif';\""
						 ." onmouseout=\"document.getElementById('order.$m.$a').src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/order_".$mode.".gif';\"";
					$icon   = "<img src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/order_".$mode.".gif' id='order.$m.$a' class='order'>";
				}
			
				$url = "javascript:sumo_ajax_get(\"".$m.".content\",\"index.php?start_".$m."_".$a."=0&module=".$m."&action=".$a."&om=".$order."&oc=".$column."&decoration=false\");";
 
				$header .= "<td class='".$style[$column]."' ".$attrib." style='cursor:pointer' onclick='$url' "
					  ." onmouseover='this.className=\"tab-title-on\"' onmouseout='this.className=\"".$style[$column]."\"'>"
					  ."<table><tr><td class='order' $over>".$name."</td><td>".$icon."</td></tr></table>";
			}
			else 
			{
				$header .= " <td class='".$style[$column]."' ".$attrib.">".$name;
			}
			
			$header .= "</td>\n";
		}
	}

	$header .= "</tr>\n";

	return $header;
}


/**
 * Enable banned IP
 * 
 * @author Alberto Basso
 */
function sumo_enable_bannedip($id=0)
{
	$id = intval($id);
	
	if($id > 0) 
	{		
		GLOBAL $SUMO;
		
		$query = "DELETE FROM ".SUMO_TABLE_BANNED." 
			  WHERE id=".$id;
		
		$SUMO['DB']->Execute($query);
		$SUMO['DB']->CacheFlush();
	}
}

?>