<?php

define ('SUMO_CHARSET', 'UTF-8');

$sumo_lang_core = array(

"PoweredBy" => "Fonctionne sous",

// Informations
"I00001M" => "SUMO: {{DATA}} [NE PAS REJOUER]",
"I00002X" => "Le nouveau compte a cr&eacute;&eacute;. Utilisateur:{{DATA0}}, email:{{DATA1}}, groupe:{{DATA2}}, jours:{{DATA3}}",
"I00003X" => "Utilisateur \"{{DATA0}}\" (ID:{{DATA1}}) supprim&eacute; pr&egrave;s {{DATA2}}",
"I00004X" => "Le mot de passe a chang&eacute; pour l&rsquo;utilisateur : {{DATA0}}, email:{{DATA1}}",
"I00013X" => "Noeud ON",
"I00200X" => "Utilisateur {{DATA0}} de l&rsquo;IP {{DATA1}} ({{DATA2}} secteur) entr&eacute; &agrave; \"{{DATA3}}\" ({{DATA4}})",
"I00201X" => "Utilisateur {{DATA0}} de l&rsquo;IP {{DATA1}} ({{DATA2}} secteur) d&eacute;connect&eacute; de \"{{DATA3}}\" ({{DATA4}})",
"I00202X" => "Deleted old logs from database (accesses {{DATA0}}dd, errors {{DATA1}}dd, system {{DATA2}}dd)",

// Warnings
"W00034X" => "Limite ill&eacute;gale de dossier dedans {{DATA}} fonction!",
"W00035X" => "Message non d&eacute;fini de notation dedans {{DATA}}!",
"W00036X" => "Priorit&eacute; ill&eacute;gale de notation dans la fonction {{DATA}}, priorit&eacute; MAXIMUM assum&eacute;e!",
"W00037X" => "La notation ill&eacute;gale saisissent {{DATA}}, la valeur assum&eacute;e 0 (&eacute;crire au dossier) !",
"W00038X" => "Le nom ill&eacute;gal de notation dedans {{DATA}}, &eacute;crivent au journal syst&egrave;me !",
"W00039X" => "IP Number fonction non d&eacute;finie dedans {{DATA}} !",
"W00040X" => "D&eacute;finition inadmissible de chaîne d&rsquo;IP: {{DATA}}!",
"W00041X" => "Inadmissible IP Number ({{DATA0}}) dedans la fonction {{DATA1}}!",
"W00042X" => "Utilisateur {{DATA0}} de r&eacute;gion d'IP {{DATA1}} ({{DATA2}}) ne pas exister mais essayer d'acc&eacute;der &agrave; {{DATA3}}",
"W00043X" => "Utilisateur {{DATA0}} est pas permis mais essai pour acc&eacute;der &agrave; {{DATA1}} \"{{DATA2}}\"",
"W00044X" => "Erreur de mot de passe: utilisateur {{DATA0}} de l&rsquo;IP {{DATA1}} ({{DATA2}} secteur) &agrave; {{DATA3}}",
"W00045X" => "L&rsquo;IP a ni&eacute;  ({{DATA0}}) pour l&rsquo;utilisateur {{DATA1}} ({{DATA2}} secteur) &agrave; {{DATA3}}",
"W00046X" => "L&rsquo;utilisateur {{DATA0}} (groupe {{DATA1}}) ne peut pas acc&eacute;der &agrave; {{DATA2}} (groupe {{DATA3}})",
"W00047X" => "Serveur inadmissible d'annuaire de LDAP pour le datasource \"{{DATA}}\"!",
"W00048X" => "Incapable de se relier au serveur de LDAP (datasource: {{DATA}})!",
"W00049X" => "Noeud OFF",
"W00050X" => "Noeud de client OFF",
"W00051X" => "Incapable de se relier au serveur de MySQL (datasource: \"{{DATA}}\")!",
"W00052X" => "Unable to connect to Postgres server (datasource: \"{{DATA}}\")!",
"W00053X" => "Unable to connect to Oracle server (datasource: \"{{DATA}}\")!",
"W00054X" => "Unable to manage local Unix/Linux users!",
"W00056X" => "Unable to connect to Joomla database (datasource: \"{{DATA}}\")!",
"W00100X" => "Cannot authenticate user on AccessPoint with encrypted password: AP {{DATA0}} user {{DATA1}} from IP {{DATA2}} area {{DATA3}}",

// Errors
"E00103X" => "Nom de fichier non d&eacute;fini dans la fonction: {{DATA}}!",
"E00104X" => "Dossier {{DATA}} ne pas exister!",
"E00105X" => "Ne peut pas lire le dossier : {{DATA}} ! V&eacute;rifier si vous avez la permission lecture/&eacute;criture, si l'erreur persiste vous pouvez enlever ce dossier et r&eacute;essayer",
"E00106X" => "Ne peut pas acc&eacute;der/&eacute;crit &agrave; {{DATA}} ! V&eacute;rifier si vous avez la permission lecture/&eacute;criture, si l'erreur persiste vous pouvez enlever ce dossier et r&eacute;essayer",
"E00107X" => "Mauvais code d&eacute;tect&eacute; ({{DATA0}}) de r&eacute;gion d'IP {{DATA1}} ({{DATA2}}) &agrave; la page {{DATA3}}",
"E00107M" => "ALERTE: Attaque d&eacute;tect&eacute;e!",
"E00108X" => "Attaque probable CSS d&eacute;tect&eacute;e",
"E00109X" => "M&eacute;tacaract&egrave;res d&eacute;tect&eacute;s d'injection de SQL",
"E00110X" => "M&eacute;tacaract&egrave;res modifi&eacute;s d&eacute;tect&eacute;s de SQL",
"E00111X" => "Injection typique d&eacute;tect&eacute;e de SQL",
"E00112X" => "Injection d&eacute;tect&eacute;e de SQL avec la commande de SQL",
"E00113X" => "Attaques d&eacute;tect&eacute;es d'injection de SQL sur un serveur de MSSQL",
"E00114X" => "Attaque simple CSS d&eacute;tect&eacute;e",
"E00115X" => "D&eacute;tect&eacute;e \"<img src\" CSS attack",
"E00116X" => "Attaque d&eacute;tect&eacute;e de CSS avec la r&egrave;gle paranoïaque",
"E00117X" => "Attaque multiple CSS d&eacute;tect&eacute;e", 
"E00118X" => "Interdit IP {{DATA0}} ({{DATA1}} secteur) essai &agrave; ouverture &agrave; page \"{{DATA3}}\" ({{DATA2}})",
"E00119X" => "Module de LDAP non charg&eacute;!",
"E00120X" => "Tentative de se relier au noeud: {{DATA0}}, de \"{{DATA1}}\" ({{DATA2}})",
"E00121X" => "Ne pas savoir la commande!",
"E00122X" => "Permission denied! user {{DATA0}} - Required: {{DATA1}}",
"E00123X" => "Ne peut pas se relier au noeud {{DATA0}} ({{DATA1}})!",
"E00124X" => "Undefined Access Point for {{DATA}}!",
"E00125X" => "Unknow datasource {{DATA}}!",
"E06000X" => "Impossible d'envoyer le message e-mail: undefined administrateur adresse e-mail!"

);

?>