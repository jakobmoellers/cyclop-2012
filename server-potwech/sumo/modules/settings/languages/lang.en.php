<?php

$module['language'] = array(

"Settings"           => "Settings",
"GenericOptions"     => "Generic Options",
"SecurityOptions"    => "Security",
"ConsoleOptions"     => "Console",
"SessionsOptions"    => "Sessions and Connections",
"LoggingOptions"     => "Log Manager",
"AccountsOptions"    => "Accounts",
"EditSettings"       => "Edit Settings",
"AccessPointOptions" => "AccessPoints",
"DesktopOptions"     => "Desktop",
"SettingsUpdated"    => "Settings updated",
"SettingsNotUpdated" => "Settings not updated",
"Enabled"	     => "Enabled",
"Disabled"	     => "Disabled",

"iptocountry.enabled"    => "IP to Country database",
"iptocountry.updater"    => "Reload IP2C Database",
"database.optimize_hits" => "Auto-optimize database (hits)",
"database.optimize_hits_desc" => "Performs the automatic optimization of the database when the counter reaches the number of hits set (minimum 1000).<br>"
                                ."<b>Current value counter</b>: {{GET:database.optimize_hits.counter}} hits",

"accesspoints.stats"     => "Statistics",
"accesspoints.def_name"  => "Default name",
"accesspoints.def_group" => "Default group",
"accesspoints.def_theme" => "Default theme",

"server.date"		=> "Date",
"server.language"	=> "Language",
"server.admin.name"	=> "Administrator name",
"server.admin.email"    => "E-mail administrator",
"server.date_format"    => "Date format (<a href='http://www.php.net/manual/en/function.date.php' target='_new'>examples</a>)",
"server.time_format"    => "Hours format (<a href='http://www.php.net/manual/en/function.date.php' target='_new'>examples</a>)",

"console.tip"           => "Show tooltip help",

"accounts.life"           	    	=> "Accounts life (days)",
"accounts.registration.enabled" 	=> "Users registration",
"accounts.registration.life"    	=> "Registrations waiting (hours)",
"accounts.registration.notify.reg"   => "Send notify for new users registrations",
"accounts.registration.notify.unreg" => "Send notify for unregistered users",
"accounts.password.life"             => "Password life (days)",
"accounts.notify.status"   	     => "Notify accounts state",
"accounts.notify.expired" 	     => "Notify expired users",
"accounts.notify.updates"   	     => "Notify updated accounts",

"security.max_login_attempts"  => "Max login attempts (0 = no limit)",
"security.banned_time"         => "Banned time",
"security.access_violations"   => "Enable permissions control log",

"connections.timeout"       => "Connections client timeout",
"connections.timeout_desc"  => "Is the time of a valid client side connection (when the user  isn&rsquo;t yet logged into the system).<br>"
                                ."After this time a new token for authentication will be regenerated.",
"sessions.timeout"             => "Sessions user timeout",
"sessions.timeout_desc"        => "Indicates the <b>idle</b> time after which the user ends his session",
"sessions.auto_regenerate_id"  => "Auto regeneration of session id",
"sessions.auto_regenerate_id_desc"  => "More secure, obstacle &quot;session hijacking&quot;.<br>"
                                      ."<b>Note</b>: Could have problems on system with heavy load.<br>"
                                      ."Don&rsquo;t work if Session replica is enabled",

"logs.system" 		=> "System Log",
"logs.errors" 		=> "Errors Log",
"logs.access" 		=> "Access Log",
"logs.database.enabled" => "Log to database:",
"logs.database.life"    => "Life (days):",
"logs.file.enabled"     => "Log to file:",
"logs.file.life"        => "Life (days):",
"logs.file.size"        => "Size (Kb):",
"logs.email.enabled"    => "Send log via e-mail:",

// Logs
"I06001X" => "Configuration \"{{DATA0}}\" modified by user {{DATA1}}",

"W06001C" => "Invalid account life!",
"W06002C" => "Invalid banned time!",
"W06003C" => "Invalid log life!",
"W06004C" => "Invalid max login attempts!",
"W06005C" => "Invalid connections client timeout!",
"W06006C" => "Invalid sessions user timeout!",
"W06007C" => "Invalid registrtions waiting!",
"W06008C" => "Invalid database optimization hits number!",
//"W06009C" => "Invalid theme!",
"W06010C" => "Invalid hour format!",
"W06011C" => "Invalid date format!",
"W06012C" => "Invalid administrator name!",

"W07002C" => "Invalid group name!" 

);

?>