<?php

$module['language'] = array(

"Settings"           => "Param&egrave;tres",
"GenericOptions"     => "Generic Options",
"SecurityOptions"    => "S&eacute;curit&eacute;",
"ConsoleOptions"     => "Console",
"SessionsOptions"	 => "Sessions et connexions",
"LoggingOptions"     => "Log Manager",
"AccountsOptions"    => "Accounts",
"EditSettings"       => "Modifier les param&egrave;tres",
"AccessPointOptions" => "Points d&rsquo;Acc&egrave;s",
"DesktopOptions"	 => "Desktop",
"SettingsUpdated"	 => "Settings updated",
"SettingsNotUpdated" => "Settings not updated",
"Enabled"			 => "Enabled",
"Disabled"			 => "Disabled",

"iptocountry.enabled"    => "IP to Country database",
"iptocountry.updater"    => "Reload IP2C Database",
"database.optimize_hits" => "Auto-optimize database (hits)",
"database.optimize_hits_desc" => "Effectue l&rsquo;optimisation automatique de la base de donn&eacute;es lorsque le compteur atteint le nombre de r&eacute;sultats fix&eacute;s (minimum 1000).<br>"
                                ."<b>Current value counter</b>: {{GET:database.optimize_hits.counter}} hits",

"accesspoints.stats"     => "Statistiques",
"accesspoints.def_name"  => "Default name",
"accesspoints.def_group" => "Default group",
"accesspoints.def_theme" => "Default theme",

"server.date"			=> "Date",
"server.language"		=> "Langue",
"server.admin.name"		=> "Nom de l&rsquo;administrateur",
"server.admin.email"    => "E-mail administrator",
"server.date_format"    => "Date format (<a href='http://www.php.net/manual/en/function.date.php' target='_new'>exemples</a>)",
"server.time_format"    => "Hours format (<a href='http://www.php.net/manual/en/function.date.php' target='_new'>exemples</a>)",

"console.tip"           => "Afficher info-bulle d&rsquo;aide",

"accounts.life"           	    	 => "Accounts life (days)",
"accounts.registration.enabled" 	 => "Users registration",
"accounts.registration.life"    	 => "Registrations waiting (hours)",
"accounts.registration.notify.reg"   => "Send notify for new users registrations",
"accounts.registration.notify.unreg" => "Send notify for unregistered users",
"accounts.password.life"        	 => "Password life (days)",
"accounts.notify.status"   			 => "Notify accounts state",
"accounts.notify.expired" 			 => "Notify expired users",
"accounts.notify.updates"   		 => "Notify updated accounts",

"security.max_login_attempts"  => "Max login attempts (0 = no limit)",
"security.banned_time"         => "Banned time",
"security.access_violations"   => "Enable permissions control log",

"connections.timeout"       => "Connections client timeout",
"connections.timeout_desc"  => "Est le temps d&rsquo;une connexion valide côt&eacute; client (lorsque l&rsquo;utilisateur n&rsquo;est pas encore entr&eacute; dans le syst&egrave;me)."
                                ."Pass&eacute; ce d&eacute;lai, un nouveau jeton d&rsquo;authentification seront r&eacute;g&eacute;n&eacute;r&eacute;s",
"sessions.timeout"             => "Sessions user timeout",
"sessions.timeout_desc"        => "Indique le d&eacute;lai <b>d&rsquo;inactivit&eacute;</b> apr&egrave;s laquelle l&rsquo;utilisateur met fin &agrave; sa session",
"sessions.auto_regenerate_id"  => "Auto regeneration of session id",
"sessions.auto_regenerate_id_desc"  => "Plus sûr, plus &quot;session hijacking&quot;.<br>"
                                        ."<b>Note</b>: pourrait avoir des probl&egrave;mes sur le syst&egrave;me avec la charge lourde."
                                        ."Ne fonctionne pas si R&eacute;plique de session est activ&eacute;e",
                                      
"logs.system" 			=> "System Log",
"logs.errors" 			=> "Errors Log",
"logs.access" 			=> "Access Log",
"logs.database.enabled" => "Log to database:",
"logs.database.life"    => "Life (days):",
"logs.file.enabled"     => "Log on file:",
"logs.file.life"        => "Life (days):",
"logs.file.size"        => "Size (Kb):",
"logs.email.enabled"    => "Log via e-mail:",

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