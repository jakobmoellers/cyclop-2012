<?php

$module['language'] = array(

"Settings"           => "Impostazioni",
"GenericOptions"     => "Impostazioni Generiche",
"ConsoleOptions"     => "Console",
"SecurityOptions"    => "Sicurezza",
"SessionsOptions"    => "Sessioni e Connessioni",
"LoggingOptions"     => "Log Manager",
"AccountsOptions"    => "Accounts",
"EditSettings"       => "Modifica Impostazioni",
"AccessPointOptions" => "AccessPoints",
"DesktopOptions"     => "Desktop",
"SettingsUpdated"    => "Impostazioni aggiornate",
"SettingsNotUpdated" => "Impostazioni non aggiornate",
"Enabled"	     => "Abilitato",
"Disabled"	     => "Disabilitato",

"iptocountry.enabled"    => "IP to Country database",
"iptocountry.updater"    => "Ricarica il database IP2C",
"database.optimize_hits" => "Auto-ottimizza database (hits)",
"database.optimize_hits_desc" => "Esegue l&rsquo;ottimizzazione automatica del database quando il contatore raggiunge il numero di hits impostato (minimo 1000).<br>"
                                ."<b>Valore attuale contatore</b>: {{GET:database.optimize_hits.counter}} hits",

"accesspoints.stats"     => "Statistiche",
"accesspoints.def_name"  => "Nome di default",
"accesspoints.def_group" => "Gruppo di default",
"accesspoints.def_theme" => "Tema di default",

"server.date"		=> "Data",
"server.language"	=> "Lingua",
"server.admin.name"	=> "Nome amministratore",
"server.admin.email"    => "E-mail amministratore",
"server.date_format"    => "Formato Data (<a href='http://www.php.net/manual/en/function.date.php' target='_new'>esempi</a>)",
"server.time_format"    => "Formato ora (<a href='http://www.php.net/manual/en/function.date.php' target='_new'>esempi</a>)",

"console.tip"           => "Mostra tooltip di aiuto",

"accounts.life"                      => "Durata Account (giorni)",
"accounts.registration.enabled"      => "Registrazione Utenti",
"accounts.registration.life"         => "Attesa registrazione (ore)",
"accounts.registration.notify.reg"   => "Invia notifiche per utenze registrate",
"accounts.registration.notify.unreg" => "Invia notifiche per utenze cancellate",
"accounts.password.life"             => "Durata Password (giorni)",
"accounts.notify.status"   	     => "Notifica stato accounts",
"accounts.notify.expired" 	     => "Notifica utenze scadute",
"accounts.notify.updates"   	     => "Notifica aggiornamenti accounts",

"security.max_login_attempts"  => "Tentativi di accesso (0 = no limite)",
"security.banned_time"         => "Durata sospensione accesso",
"security.access_violations"   => "Abilita log controllo permessi",


"connections.timeout"       => "Timeout connessione client",
"connections.timeout_desc"  => "&Eacute; il tempo entro il quale una connessione lato client, che non ha ancora effettuato il login, viene considerata valida.<br>"
                                ."Oltre questo tempo viene rigenerato un nuovo token per l'autenticazione",
"sessions.timeout"          => "Timeout sessione utente",
"sessions.timeout_desc"     => "Indica il tempo di <b>inattivit&aacute;</b> oltre il quale scade la sessione attiva dell&rsquo;utente",
"sessions.auto_regenerate_id"  => "Rigenera automaticamente l'ID di sessione",
"sessions.auto_regenerate_id_desc"  => "Aumenta la sicurezza impedendo il &quot;session hijacking&quot;.<br>"
                                      ."<b>Nota</b>: pu&oacute; presentare problemi in caso di server molto carico.<br>"
                                      ."Non funziona con la replica della sessione abilitata",

"logs.system" 	        => "Log di Sistema",
"logs.errors" 	        => "Log Errori",
"logs.access" 	        => "Log degli Accessi",
"logs.database.enabled" => "Log su database:",
"logs.database.life"    => "Durata (giorni):",
"logs.file.enabled"     => "Log su file:",
"logs.file.life"        => "Durata (giorni):",
"logs.file.size"        => "Dimensione (Kb):",
"logs.email.enabled"    => "Log via e-mail:",



// Logs
"I06001X" => "Modificata la configurazione \"{{DATA0}}\" dall'utente {{DATA1}}",

"W06001C" => "Durata account non valida!",
"W06002C" => "Durata sospensione account non valida!",
"W06003C" => "Durata log non valida!",
"W06004C" => "Numero massimo tentativi non valido!",
"W06005C" => "Timeout connessione non valido!",
"W06006C" => "Timeout sessione non valido!",
"W06007C" => "Timeout registrazione account non valido!",
"W06008C" => "Numero hits per ottimizzazione database non valido!",
//"W06009C" => "Tema non valido!",
"W06010C" => "Formato ora non valido!",
"W06011C" => "Formato data non valido!",
"W06012C" => "Nome amministratore non valido!",

"W07002C" => "Invalid group name!"

);

?>