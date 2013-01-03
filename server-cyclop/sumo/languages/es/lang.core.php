<?php

define ('SUMO_CHARSET',  'UTF-8');

$sumo_lang_core = array(

"PoweredBy" => "Creado por",

// Informations
"I00001M" => "SUMO: {{DATA}} [NOT REPLAY]",
"I00002X" => "Nueva cuenta creada. Usuario:{{DATA0}}, email:{{DATA1}}, grupo:{{DATA2}}, d&iacute;as:{{DATA3}}",
"I00003X" => "Usuario \"{{DATA0}}\" (ID:{{DATA1}}) eliminada por {{DATA2}}",
"I00004X" => "Password cambiado para el usuario: {{DATA0}}, email:{{DATA1}}",
"I00013X" => "Nodo ACTIVO",
"I00200X" => "Usuario {{DATA0}} con IP {{DATA1}} [{{DATA2}}] inici&oacute; sesi&oacute;n en \"{{DATA3}}\" ({{DATA4}})",
"I00201X" => "Usuario {{DATA0}} con IP {{DATA1}} [{{DATA2}}] cerro sesi&oacute;n en \"{{DATA3}}\" ({{DATA4}})",
"I00202X" => "Deleted old logs from database (accesses {{DATA0}}dd, errors {{DATA1}}dd, system {{DATA2}}dd)",

// Warnings
"W00034X" => "Ilegal limite de archivo en la funci&oacute;n {{DATA}}!",
"W00035X" => "Mensaje de log no definido {{DATA}}!",
"W00036X" => "Ilegal prioridad de log en la funci&oacute;n {{DATA}}, asumida prioridad MAX!",
"W00037X" => "Ilegal tipo de log {{DATA}}, asumido valor 0 (grabado en fichero)!",
"W00038X" => "Ilegal nombre de log en {{DATA}}, grabado en el sistema de logs!",
"W00039X" => "N&uacute;mero de IP no definidad en la funci&oacute;n {{DATA}}!",
"W00040X" => "Definici&oacute;n inv&acute;lida del rango de direcciones IP: {{DATA}}!",
"W00041X" => "N&uacute;mero IP no v&acute;lido ({{DATA0}}) en la funci&oacute;n {{DATA1}}!",
"W00042X" => "Usuario {{DATA0}} con IP {{DATA1}} [{{DATA2}}] no existe pero intenta acceder a {{DATA3}}",
"W00043X" => "Usuario {{DATA0}} est&acute; deshabilitado pero intenta acceder a {{DATA1}} \"{{DATA2}}\"",
"W00044X" => "Contrase&ntilde;a incorrecta: usuario {{DATA0}} con IP {{DATA1}} [{{DATA2}}] en {{DATA3}}",
"W00045X" => "IP denegada ({{DATA0}}) para el usuario {{DATA1}} [{{DATA2}}] en {{DATA3}}",
"W00046X" => "Usuario {{DATA0}} (grupo {{DATA1}}) no puede acceder en {{DATA2}} (grupo {{DATA3}})",
"W00047X" => "Directorio de servidor LDAP no v&acute;lido para el origen de datos \"{{DATA}}\"!",
"W00048X" => "Imposible conectar con el servidor LDAP (origen de datos:  {{DATA}})!",
"W00049X" => "Nodo DESACTIVADO",
"W00050X" => "Nodo Cliente DESACTIVADO",
"W00051X" => "Imposible conectar con el servidor MySQL (origen de datos: \"{{DATA}}\")!",
"W00052X" => "Imposible conectar con el servidor Postgres (origen de datos: \"{{DATA}}\")!",
"W00053X" => "Imposible conectar con el servidor Oracle (origen de datos: \"{{DATA}}\")!",
"W00054X" => "Unable to manage local Unix/Linux users!",
"W00056X" => "Imposible conectar con el servidor Joomla (origen de datos: \"{{DATA}}\")!",
"W00100X" => "Cannot authenticate user on AccessPoint with encrypted password: AP {{DATA0}} user {{DATA1}} from IP {{DATA2}} ({{DATA3}})",


// Errors
"E00103X" => "Nombre de funci&oacute;n no definida en: {{DATA}}!",
"E00104X" => "Archivo {{DATA}} no existe!",
"E00105X" => "No se puede leer el archivo: {{DATA}}! Compruebe que tiene permisos de lectura/escritura, si el error persiste, puede borrar este archivo y volverlo a intentar",
"E00106X" => "No se puede acceder/grabar en {{DATA}}! Compruebe que tiene permisos de lectura/escritura, si el error persiste, puede borrar este archivo y volverlo a intentar",
"E00107X" => "Eliminado c&oacute;digo maligno ({{DATA0}}) de la IP {{DATA1}} [{{DATA2}}] en la p&acute;gina {{DATA3}}",
"E00107M" => "ATENCI&Oacute;N: Ataque detectado!",
"E00108X" => "Detectado probable ataque CSS",
"E00109X" => "Detectada inyecci&oacute;n SQL de meta-caracteres",
"E00110X" => "Detectada modificaci&oacute;n SQL de meta-caracteres",
"E00111X" => "Detectada tipica inyecccion SQL",
"E00112X" => "Detectada inyecci&oacute;n SQL mediante comando Sql",
"E00113X" => "Detectado ataque inyecci&oacute;n SQL en el Servidor MSSQL",
"E00114X" => "Detectado simple ataque CSS",
"E00115X" => "Detectado ataque \"<img src\" CSS",
"E00116X" => "Detectado ataque CSS con regla paranoica",
"E00117X" => "Detectado multiple ataque CSS", 
"E00118X" => "IP baneada {{DATA0}} [{{DATA1}}] intenta acceder a \"{{DATA3}}\" ({{DATA2}})",
"E00119X" => "Extensi&oacute;n LDAP no cargada!",
"E00120X" => "Intento de conexi&oacute;n al Nodo: {{DATA0}}, desde \"{{DATA1}}\" ({{DATA2}})",
"E00121X" => "Comando desconocido!",
"E00122X" => "Permiso denegado! usuario {{DATA0}} - Requerido: {{DATA1}}",
"E00123X" => "Imposible conectar al nodo {{DATA0}} ({{DATA1}})!",
"E00124X" => "Punto de Acceso no definido {{DATA}}!",
"E00125X" => "DataSource Unknow {{DATA}}!",
"E06000X" => "No se puede enviar un mensaje de correo electr&oacute;nico: Indefinido direcci&oacute;n de correo electr&oacute;nico de administrador!"

);

?>