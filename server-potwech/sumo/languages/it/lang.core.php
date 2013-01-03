<?php

define ('SUMO_CHARSET',	'UTF-8');

$sumo_lang_core = array(

"PoweredBy" => "Powered by",

// Informations
"I00001M" => "Notifica SUMO: {{DATA}} [NON RISPONDERE]",
"I00002X" => "Nuovo account creato. Utente:{{DATA0}}, email:{{DATA1}}, gruppo:{{DATA2}}, giorni:{{DATA3}}",
"I00003X" => "Utente \"{{DATA0}}\" (ID:{{DATA1}}) eliminato da {{DATA2}}",
"I00004X" => "Password modificata per l'utente: {{DATA0}}, email:{{DATA1}}",
"I00013X" => "Nodo ON",
"I00200X" => "Login utente {{DATA0}} da IP {{DATA1}} ({{DATA2}}) su \"{{DATA3}}\" ({{DATA4}})",
"I00201X" => "Logout utente {{DATA0}} da IP {{DATA1}} ({{DATA2}}) su \"{{DATA3}}\" ({{DATA4}})",
"I00202X" => "Eliminati vecchi log dal database (accessi {{DATA0}}gg, errori {{DATA1}}gg, sistema {{DATA2}}gg)",

// Warnings
"W00034X" => "Dimensione limite file non valida (funzione: {{DATA}})!",
"W00035X" => "Messaggio Log indefinito in {{DATA}}!",
"W00036X" => "Priorita' log nella funzione {{DATA}} non valida, attivo priorita' massima!",
"W00037X" => "Tipologia log in {{DATA}} non valida, imposto valore 0 (log su file)!",
"W00038X" => "Nome log non valido in {{DATA}}, scrivo sul system log!",
"W00039X" => "Indirizzo IP non definito nella funzione {{DATA}}.",
"W00040X" => "Range indirizzi IP non valido: {{DATA}}!",
"W00041X" => "Indirizzo IP non valido ({{DATA0}}) nella funzione {{DATA1}}!",
"W00042X" => "Tentativo di accesso a {{DATA3}} da IP {{DATA1}} ({{DATA2}}), utente inesistente {{DATA0}}.",
"W00043X" => "Tentativo di accesso a {{DATA1}} \"{{DATA2}}\", utente {{DATA0}} disabilitato.",
"W00044X" => "Password errata: utente {{DATA0}} da IP {{DATA1}} ({{DATA2}}) a {{DATA3}}",
"W00045X" => "IP negato ({{DATA0}}) per utenza {{DATA1}} ({{DATA2}}) a {{DATA3}}",
"W00046X" => "Utenza {{DATA0}} (gruppo {{DATA1}}) non puo' accedere a {{DATA2}} (gruppo {{DATA3}})",
"W00047X" => "Directory Server LDAP errato per datasource \"{{DATA}}\"!",
"W00048X" => "Connessione LDAP server non disponibile (datasource:  {{DATA}})!",
"W00049X" => "Nodo OFF",
"W00050X" => "Nodo Client OFF",
"W00051X" => "Connessione MySQL server non disponibile (datasource: \"{{DATA}}\")!",
"W00052X" => "Connessione Postgres server non disponibile (datasource: \"{{DATA}}\")!",
"W00053X" => "Connessione Oracle server non disponibile (datasource: \"{{DATA}}\")!",
"W00054X" => "Impossibile gestire gli utenti Unix/Linux locali!",
"W00056X" => "Connessione database Joomla non disponibile (datasource: \"{{DATA}}\")!",
"W00100X" => "Impossibile autenticare utente su AccessPoint con password cifrata: AP {{DATA0}} utente {{DATA1}} da IP {{DATA2}} ({{DATA3}})",

// Errors
"E00103X" => "Nome file non definito nella funzione: {{DATA}}!",
"E00104X" => "Il file {{DATA}} non esiste!",
"E00105X" => "Impossibile leggere il file: {{DATA}}! Verificare permessi in lettura, se l'errore persiste puoi eliminare il file e riprovare",
"E00106X" => "Impossibile scrivere nel file {{DATA}}! Verificare permessi in scrittura, se l'errore persiste puoi eliminare il file e riprovare",
"E00107X" => "Rilevato potenziale codice maligno ({{DATA0}}) da IP {{DATA1}} [{{DATA2}}] alla pagina {{DATA3}}",
"E00107M" => "ATTENZIONE: Individuato attacco!",
"E00108X" => "Individuato probabile attacco CSS",
"E00109X" => "Individuato SQL Injection meta-characters",
"E00110X" => "Individuato SQL meta-characters modificato",
"E00111X" => "Individuato SQL Injection",
"E00112X" => "Individuato SQL Injection con comando SQL",
"E00113X" => "Individuato SQL Injection per MSSQL Server",
"E00114X" => "Individuato attacco semplice CSS",
"E00115X" => "Individuato attacco \"<img src\" CSS",
"E00116X" => "Individuato attacco CSS con regole paranoiche",
"E00117X" => "Individuato attacco CSS multiplo", 
"E00118X" => "Tentativo di accesso a \"{{DATA3}}\" ({{DATA2}}) da indirizzo IP disabilitato {{DATA0}} (area {{DATA1}})",
"E00119X" => "Estensione LDAP non caricata!",
"E00120X" => "Tentata connessione Nodo: {{DATA0}}, da \"{{DATA1}}\" ({{DATA2}})",
"E00121X" => "Comando sconosciuto!",
"E00122X" => "Permessi insufficienti utente {{DATA0}} - Richiesto: {{DATA1}}",
"E00123X" => "Nodo {{DATA0}} non raggiungibile ({{DATA1}})!",
"E00124X" => "Access Point non trovato per {{DATA}}!",
"E00125X" => "Datasource {{DATA}} non riconosciuto!",
"E06000X" => "Impossibile recapitare messaggi email: indirizzo amministratore on impostato!"

);

?>