<?php

$module['language'] = array(
                  
'Network'         => 'Network',
'DataSources'	  => 'Data Sources',
'LocalNetwork'    => 'Local Network',
'add_node'        => 'Add',
'add_datasource'    => 'Add',
'edit_datasource'   => 'Edit',
'remove_datasource' => 'Remove',
'edit_node'   	  => 'Edit',
'remove_node' 	  => 'Remove',
'AddLocalIP'	  => 'Add IP',
'EditLocalIP'	  => 'Edit IP',
'TestConnection'  => 'Test Connection',
'LocalIP'	  	  => 'Local IP address',
'LocalIPType'	  => 'Local IP type',
'EncType'		  => 'Password encryption',
'NodeName' 		  => 'Node Name',
'Active'		  => 'Active',
'Disabled'		  => 'Disabled',
'Disable'		  => 'Disable',
'Enable'		  => 'Enable',
'Hostname'		  => 'Hostname',
'Host'			  => 'Hostname/IP',
'Port'			  => 'Port',
'Protocol'		  => 'Protocol',
'AccessPoints'	  => 'AccessPoints',
'Password'		  => 'Password',
'RePassword'	  => 'Retype password',
'NetworkMenu'	  => 'Main Menu',
'SumoPath'		  => 'Sumo Path',
'Failed'		  => 'FAILED',
'Ok'			  => 'OK',
'DataSourceName'  => 'Data source name',
'DataSourceType'  => 'Password authentication type',
'DBName'		  => 'Database name',
'DBTable'		  => 'Users table',
'DBFieldUser'	  => 'User filed name',
'DBFieldPassword' => 'Password filed name',
'LDAPBase'		  => 'DN base',
'Type'			  => 'Type',
'Proxy'			  => 'Proxy',
'Locale'		  => 'Local address',
'DatabaseOptions' => 'Database Options',
'LDAPOptions'	  => 'LDAP/Active Directory options',

'Unknow'		  			 => 'Unknow',
'DataSourceAdded'			 => 'Data Source added',
'DataSourceNotAdded'		 => 'Data Source not added',
'DataSourceUpdated'			 => 'Data Source updated',
'DataSourceNotUpdated'		 => 'Data Source not updated',
'CannotModifyDataSource'	 => 'Cannot modify this data source',
'CannotDeleteDataSource'	 => 'Cannot delete this data source',
'CannotDeleteNode'			 => 'Cannot delete this node',
'NodeDeleted'	 	   		 => 'Node "{{DATA}}" deleted!',
'NodeNotDeleted' 	   	  	 => 'Node "{{DATA}}" not deleted!',
'NodeUpdated'	 	   	 	 => 'Node updated',
'NodeNotUpdated' 	   	 	 => 'Node not updated',
'NodeAdded'				 	 => 'Node added',
'NodeNotAdded'			 	 => 'Node not added',
'DataSourceDeleted'	   		 => 'Data Source "{{DATA}}" deleted!',
'DataSourceNotDeleted' 		 => 'Data Source "{{DATA}}" not deleted!',
'LocalIPDeleted'	 	   	 => 'IP address "{{DATA}}" deleted!',
'LocalIPNotDeleted' 	   	 => 'IP address "{{DATA}}" not deleted!',
'LocalIPUpdated'	 	   	 => 'IP address updated',
'LocalIPNotUpdated' 	   	 => 'IP address not updated',
'LocalIPAdded'				 => 'IP address added',
'LocalIPNotAdded'			 => 'IP address not added',
'AreYouSureDeleteNode' 	     => 'Are you sure erase this node: "{{DATA}}"?<br><br>'
							   .'<font color=yellow><b><blink>WARNING:</blink></b></font> Will be deleted all associated access points!',
							   
'AreYouSureDeleteDataSource' => 'Are you sure erase this data source: "{{DATA}}"?',
'AreYouSureDeleteLocalIP'    => 'Are you sure erase this IP address: {{DATA0}} ({{DATA1}})?',
'NodeWarning'				 => 'WARNING: if you modify thist node options could not be possible connect to this console!',

'IMG:Status'				 => "<img src='themes/".$SUMO['page']['theme']."/images/modules/network/status.gif' align='middle' alt='&bull;'>",

"E09000X" => "Invalid Datasource ID!",

"W09000X" => "Node \"{{DATA0}}\" (ID:{{DATA1}}) deleted by {{DATA2}}",
"W09001C" => "Invalid data source name!",
"W09002C" => "Invalid data source type!",
"W09002X" => "Invalid data source type!",
"W09003C" => "Invalid hostname!",
"W09004C" => "Invalid port number!",
"W09005C" => "Invalid user name!",
"W09006C" => "Invalid user password!",
"W09007C" => "Invalid database name!",
"W09008C" => "Invalid table name!",
"W09009C" => "Insert a valid range of IP addresses!",
"W09010C" => "Invalid IP address type!",
"W09011C" => "Invalid Hostname or IP address!",
"W09012C" => "Invalid state!",
"W09013C" => "Invalid console type!",
"W09014C" => "Invalid Sumo Path!",
"W09015C" => "Invalid node name!",
"W09016C" => "This node already exist!",
"W09017C" => "Invalid protocol! Use http or https",
"W09018C" => "Invalid encryption type",

"I09001X" => "Data source \"{{DATA0}}\" (ID:{{DATA1}}) deleted by {{DATA2}}",
"I09002C" => "Data source: \"{{DATA}}\" already exist!",
"I09003X" => "Data source added: \"{{DATA0}}\" by user {{DATA1}}",
"I09004C" => "Insert all parameters for database connection {{DATA}}",
"I09005C" => "Insert \"LDAP Base\" path!",
"I09006X" => "Data source updated, ID:{{DATA0}} \"{{DATA1}}\" by user {{DATA2}}",
"I09007X" => "Local IP address updated: \"{{DATA0}}\" by user {{DATA1}}",
"I09008X" => "Local IP address added: \"{{DATA0}}\" by user {{DATA1}}",
"I09009X" => "Node added: \"{{DATA0}}\" IP:{{DATA1}} by user {{DATA2}}",
"I09010X" => "Node updated: \"{{DATA0}}\" IP:{{DATA1}} by user {{DATA2}}",
"I09011X" => "IP address \"{{DATA0}}\" (ID:{{DATA1}}) deleted by {{DATA2}}"

);

?>