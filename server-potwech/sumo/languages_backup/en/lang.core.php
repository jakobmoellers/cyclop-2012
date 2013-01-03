<?php

define ('SUMO_CHARSET', 'UTF-8');

$sumo_lang_core = array(

"PoweredBy" => "Powered by",

// Informations
"I00001M" => "SUMO notice: {{DATA}} [NOT REPLY]",
"I00002X" => "New account created. User:{{DATA0}}, email:{{DATA1}}, group:{{DATA2}}, days:{{DATA3}}",
"I00003X" => "User \"{{DATA0}}\" (ID:{{DATA1}}) deleted by {{DATA2}}",
"I00004X" => "Password changed for user: {{DATA0}}, email:{{DATA1}}",
"I00013X" => "Node ON",
"I00200X" => "User {{DATA0}} from IP {{DATA1}} ({{DATA2}}) logged in to \"{{DATA3}}\" ({{DATA4}})",
"I00201X" => "User {{DATA0}} from IP {{DATA1}} ({{DATA2}}) logged out from \"{{DATA3}}\" ({{DATA4}})",
"I00202X" => "Deleted old logs from database (accesses {{DATA0}}dd, errors {{DATA1}}dd, system {{DATA2}}dd)",

// Warnings
"W00034X" => "Illegal file limit in {{DATA}} function!",
"W00035X" => "Undefined log message in {{DATA}}!",
"W00036X" => "Illegal log priority in {{DATA}} function, assumed MAX priority!",
"W00037X" => "Illegal log type in {{DATA}}, assumed value 0 (write to file)!",
"W00038X" => "Illegal log name in {{DATA}}, write to system log!",
"W00039X" => "Undefined IP number in {{DATA}} function!",
"W00040X" => "Invalid IP range definition: {{DATA}}!",
"W00041X" => "Invalid IP number ({{DATA0}}) in {{DATA1}} function!",
"W00042X" => "User {{DATA0}} from IP {{DATA1}} ({{DATA2}}) not exist but try to access at {{DATA3}}",
"W00043X" => "User {{DATA0}} is disabled but try to access at {{DATA1}} \"{{DATA2}}\"",
"W00044X" => "Password error: user {{DATA0}} from IP {{DATA1}} ({{DATA2}}) at {{DATA3}}",
"W00045X" => "IP denied ({{DATA0}}) for user {{DATA1}} ({{DATA2}}) at {{DATA3}}",
"W00046X" => "User {{DATA0}} (group {{DATA1}}) cannot access at {{DATA2}} (group {{DATA3}})",
"W00047X" => "Invalid LDAP directory server for datasource \"{{DATA}}\"!",
"W00048X" => "Unable to connect to LDAP server (datasource:  {{DATA}})!",
"W00049X" => "Node OFF",
"W00050X" => "Client Node OFF",
"W00051X" => "Unable to connect to MySQL server (datasource: \"{{DATA}}\")!",
"W00052X" => "Unable to connect to Postgres server (datasource: \"{{DATA}}\")!",
"W00053X" => "Unable to connect to Oracle server (datasource: \"{{DATA}}\")!",
"W00054X" => "Unable to manage local Unix/Linux users!",
"W00056X" => "Unable to connect to Joomla database (datasource: \"{{DATA}}\")!",
"W00100X" => "Cannot authenticate user on AccessPoint with encrypted password: AP {{DATA0}} user {{DATA1}} from IP {{DATA2}} ({{DATA3}})",

// Errors
"E00103X" => "Undefined file name in function: {{DATA}}!",
"E00104X" => "File {{DATA}} not exist!",
"E00105X" => "Cannot read file: {{DATA}}! Verify if you have read/write permission, if error persists you can remove this file and retry",
"E00106X" => "Cannot access/write to {{DATA}}! Verify if you have read/write permission, if error persists you can remove this file and retry",
"E00107X" => "Detected malicious code ({{DATA0}}) from IP {{DATA1}} ({{DATA2}}) at page {{DATA3}}",
"E00107M" => "ALERT: Attack detected!",
"E00108X" => "Detected probable CSS attack",
"E00109X" => "Detected SQL Injection meta-characters",
"E00110X" => "Detected modified SQL meta-characters",
"E00111X" => "Detected typical SQL Injection",
"E00112X" => "Detected SQL Injection with Sql command",
"E00113X" => "Detected SQL Injection attacks on a MSSQL Server",
"E00114X" => "Detected simple CSS attack",
"E00115X" => "Detected \"<img src\" CSS attack",
"E00116X" => "Detected CSS attack with paranoic rule",
"E00117X" => "Detected multiple CSS attack", 
"E00118X" => "Banned IP {{DATA0}} ({{DATA1}}) try to login at page \"{{DATA3}}\" ({{DATA2}})",
"E00119X" => "LDAP extension not loaded!",
"E00120X" => "Attempt to connect to Node: {{DATA0}}, from \"{{DATA1}}\" ({{DATA2}})",
"E00121X" => "Unknow command!",
"E00122X" => "Permission denied! user {{DATA0}} - Required: {{DATA1}}",
"E00123X" => "Can't connect to node {{DATA0}} ({{DATA1}})!",
"E00124X" => "Undefined Access Point for {{DATA}}!",
"E00125X" => "Unknow datasource {{DATA}}!",
"E06000X" => "Unable to send email message: undefined administrator email address!"

);

?>