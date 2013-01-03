<?php

$module['language'] = array(

"Settings"           => "Configuraci&oacute;n",
"GenericOptions"     => "Opciones Generales",
"SecurityOptions"    => "Seguridad",
"SessionsOptions"    => "Sesiones i Conexiones",
"ConsoleOptions"     => "Consola",
"LoggingOptions"     => "Administraci&oacute;n del Log",
"AccountsOptions"    => "Cuentas",
"EditSettings"       => "Editar Configuraci&oacute;n",
"AccessPointOptions" => "Puntos de Acceso",
"DesktopOptions"	 => "Escritorio",
"SettingsUpdated"	 => "Configuraci&oacute;n actualizada",
"SettingsNotUpdated" => "Configuraci&oacute;n no actualizada",
"Enabled"	     => "Habilitado",
"Disabled"	     => "Deshabilitado",
"Counter"	     => "Valor actual contador",

"iptocountry.enabled"    => "base de datos IP/Pa&iacute;s",
"iptocountry.updater"    => "Recargue Base de Datos IP2C",
"database.optimize_hits" => "Auto-optimizar Base Datos (veces)",
"database.optimize_hits_desc" => "Realiza la optimizaci&oacute;n autom&aacute;tica de la base de datos cuando el contador alcanza el n&uacute;mero de visitas establecido (m&iacute;nimo de 1000).<br>"
                                ."<b>Valor actual contador</b>: {{GET:database.optimize_hits.counter}} hits",

"accesspoints.stats"     => "Estad&iacute;sticas",
"accesspoints.def_name"  => "Nombre por defecto",
"accesspoints.def_group" => "Grupo por defecto",
"accesspoints.def_theme" => "Tema por defecto",

"server.date"		=> "Fecha",
"server.language"	=> "Idioma",
"server.admin.name"	=> "Nombre del administrador",
"server.admin.email"    => "E-mail administrador",
"server.date_format"    => "Formato fecha (<a href='http://www.php.net/manual/en/function.date.php' target='_new'>ejemplos</a>)",
"server.time_format"    => "Formato hora (<a href='http://www.php.net/manual/en/function.date.php' target='_new'>ejemplos</a>)",

"console.tip"           => "Ayuda show tooltip",

"accounts.life"           	    	 => "Duraci&oacute;n de la cuenta (d&iacute;as)",
"accounts.registration.enabled" 	 => "Registro de usuarios",
"accounts.registration.life"    	 => "Espera de registro (horas)",
"accounts.registration.notify.reg"   => "Enviar notificaci&oacute;n a los nuevos usuarios",
"accounts.registration.notify.unreg" => "Enviar notificaci&oacute;n a los usuarios no registrados",
"accounts.password.life"        	 => "Duraci&oacute;n de la Contrase&ntilde;a (d&iacute;as)",
"accounts.notify.status"   			 => "Notificar estado de las cuentas",
"accounts.notify.expired" 			 => "Notificar la espiraci&oacute;n de las cuentas",
"accounts.notify.updates"   		 => "Notificar la actualizaci&oacute;n de las cuentas",

"security.max_login_attempts"  => "M&aacute;ximo n&uacute;mero de intentos de acceso (0 = sin l&iacute;mite)",
"security.banned_time"         => "Tiempo de bloqueo (segundos)",

"connections.timeout"       => "M&aacute;ximo tiempo de espera para el cliente (segundos)",
"connections.timeout_desc"  => "Es el momento de una conexi&oacute;n v&aacute;lida con el cliente (cuando el usuario no est&aacute; conectado en el sistema).<br>"
                                ."Despu&eacute;s de este tiempo un nuevo token para la autenticaci&oacute;n se volver&aacute;n.",
"sessions.timeout"             => "M&aacute;ximo tiempo de espera para el usuario (segundos)",
"sessions.timeout_desc"        => "Indica el tiempo de <b>inactividad</b> despu&eacute;s del cual el usuario termina su per&iacute;odo de sesiones",
"sessions.auto_regenerate_id"  => "Auto regeneration of session id",
"sessions.auto_regenerate_id_desc"  => "M&aacute;s seguro, obst&aacute;culo &quot;session hijacking&quot;.<br>"
                                      ."<b>Nota: Puede tener problemas en el sistema con la carga pesada."
                                        ."No trabaje si est&aacute; habilitada la r&eacute;plica de sesi&oacute;n",
                                      
"logs.system" 			=> "Logs del Sistema",
"logs.errors" 			=> "Log de Errores",
"logs.access" 			=> "Log de Accesos",
"logs.database.enabled" => "Logs en la base da datos:",
"logs.database.life"    => "Duraci&oacute;n (d&iacute;as):",
"logs.file.enabled"     => "Log en archivo:",
"logs.file.life"        => "Duraci&oacute;n (d&iacute;as):",
"logs.file.size"        => "Tama&ntilde;o (Kb):",
"logs.email.enabled"    => "Log v&iacute;a e-mail:",

// Logs
"I06001X" => "Configuration \"{{DATA0}}\" modified by user {{DATA1}}",

"W06001C" => "Duraci&oacute;n de la cuenta no v&aacute;lida!",
"W06002C" => "Tiempo de bloqueo no v&aacute;lido!",
"W06003C" => "Duraci&oacute;n del log no v&aacute;lido!",
"W06004C" => "M&aacute;ximo n&uacute;mero de intentos no v&aacute;lidos!",
"W06005C" => "M&aacute;ximo tiempo de espera para el cliente no v&aacute;lido!",
"W06006C" => "M&aacute;ximo tiempo de espera para el usuario no v&aacute;lido!",
"W06007C" => "Espera de registro no v&aacute;lido!",
"W06008C" => "N&uacute;mero de veces de optimizaci&oacute; de la base datos no v&aacute;lido!",
//"W06009C" => "Tema no v&aacute;lido!",
"W06010C" => "Formato de hora no v&aacute;lido!",
"W06011C" => "Formato de fecha no v&aacute;lido!",
"W06012C" => "Nombre de Administrador no v&aacute;lido!",

"W07002C" => "Nombre de grupo no v&aacute;lido!" 

);

?>