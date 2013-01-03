<?php
/**
 * SERVICE: Relationship
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * 
 */

switch ($_GET['cmd']) 
{	
	//
	case 'GET_ACCESSPOINT2USERS':
		
		if($SUMO['DB']->IsConnected()) 
		{	
			$tab  = sumo_get_accesspoint_info($_GET['id'], 'id');
			$name = sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']);
		
			$groups = explode(";", $tab['usergroup']);
			$group  = array();
			
			for($i=0; $i<count($groups); $i++)
			{
				$group[] = " usergroup LIKE '%".$groups[$i]."%' ";
			}
			
			$groups = implode(" OR ", $group);
			
			$query = "SELECT username,firstname,lastname
					  FROM ".SUMO_TABLE_USERS."
					  WHERE ($groups
					  		 OR usergroup LIKE 'sumo:%'
					  		 OR usergroup LIKE '%;sumo:%')
					  AND active=1
					  AND username<>'sumo'
					  ORDER BY username,lastname,firstname";
			/*
			$groups = sumo_get_group_query(false, );
			$groups = str_replace(")", " ", $groups);
			
			$query = "SELECT username,firstname,lastname
					  FROM ".SUMO_TABLE_USERS."
					  	$groups
					  	OR usergroup LIKE 'sumo:%' OR usergroup LIKE '%;sumo:%'
					  )
					  AND active=1
					  AND username<>'sumo'
					  ORDER BY username,lastname, firstname
					  ";
			*/
			$rs = $SUMO['DB']->Execute($query);

			/**
			 *	phpTreeGraph
			 *	Species hierarchy demo with images
			 * 	@author Mathias Herrmann
			 **/
			
			//include GD rendering class
			require_once SUMO_PATH_MODULE.'/classes/class.gdrender.php';
			
			//create new GD renderer, optinal parameters: LevelSeparation,  SiblingSeparation, SubtreeSeparation, defaultNodeWidth, defaultNodeHeight
			$objTree = new GDRenderer(180, 10, 30, 100, 13);
			
			//add nodes to the tree, parameters: id, parentid optional title, text, width, height, image(path)
			$objTree->add(1, 0, $name);
			
			$g = 2;
			
			while($tab = $rs->FetchRow()) 
			{
				$name = $tab['lastname']." ".$tab['firstname'];
				
				if($name == " ") $name = $tab['username'];
				
				$objTree->add($g++, 1, $name);
			}
			
			$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);
			$objTree->setNodeTitleColor(array(245, 240, 220));
			$objTree->setLinkColor(array(150, 150, 200));
			
			$objTree->setTextTitleColor(array(0, 0, 0));
			$objTree->setFTFont(SUMO_PATH.'/applications/fonts/verdana.ttf', 7);
			
			$objTree->stream();
			
		}
		
		break;
		
	//
	case 'GET_ACCESSPOINT2GROUPS':
		
		if($SUMO['DB']->IsConnected()) 
		{	
			$tab  = sumo_get_accesspoint_info($_GET['id'], 'id');
			$name = sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']);
			
			/**
			 *	phpTreeGraph
			 *	Species hierarchy demo with images
			 * 	@author Mathias Herrmann
			 **/
			
			//include GD rendering class
			require_once SUMO_PATH_MODULE.'/classes/class.gdrender.php';
			
			//create new GD renderer, optinal parameters: LevelSeparation,  SiblingSeparation, SubtreeSeparation, defaultNodeWidth, defaultNodeHeight
			$objTree = new GDRenderer(100, 10, 30, 100, 13);
			
			//add nodes to the tree, parameters: id, parentid optional title, text, width, height, image(path)
			$objTree->add(1, 0, $name);
			
			$g = 2;

			for($i=0; $i<count($tab['usergroup']); $i++)
			{	
				$objTree->add($g++, 1, $tab['usergroup'][$i]);
			}
			
			$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);
			$objTree->setNodeTitleColor(array(245, 240, 220));
			$objTree->setLinkColor(array(150, 150, 200));
			
			$objTree->setTextTitleColor(array(0, 0, 0));
			$objTree->setFTFont(SUMO_PATH.'/applications/fonts/verdana.ttf', 7);
			
			$objTree->stream();
			
		}
		
		break;
		
	// 
	case 'GET_GROUP2USERS':
	
		$group = sumo_get_group_info($_GET['id']);
		
		if($SUMO['DB']->IsConnected()) 
		{
			$query = "SELECT username,firstname,lastname
					  FROM ".SUMO_TABLE_USERS."
					  WHERE (usergroup LIKE '".$group['usergroup'].":%' 
					  		 OR usergroup LIKE '%;".$group['usergroup'].":%'
					  		 OR usergroup LIKE 'sumo:%'
					  		 OR usergroup LIKE '%;sumo:%')
					  AND active=1
					  AND username<>'sumo'
					  ORDER BY username,lastname,firstname";

			$rs = $SUMO['DB']->Execute($query);

			/**
			 *	phpTreeGraph
			 *	Species hierarchy demo with images
			 * 	@author Mathias Herrmann
			 **/
			
			//include GD rendering class
			require_once SUMO_PATH_MODULE.'/classes/class.gdrender.php';
			
			//create new GD renderer, optinal parameters: LevelSeparation,  SiblingSeparation, SubtreeSeparation, defaultNodeWidth, defaultNodeHeight
			$objTree = new GDRenderer(180, 10, 30, 100, 13);
			
			//add nodes to the tree, parameters: id, parentid optional title, text, width, height, image(path)
			$objTree->add(1, 0, $group['usergroup']);
			
			$g = 2;
			
			while($tab = $rs->FetchRow()) 
			{
				$name = $tab['lastname']." ".$tab['firstname'];
				
				if($name == " ") $name = $tab['username'];
				
				$objTree->add($g++, 1, $name);
			}
			
			$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);
			$objTree->setNodeTitleColor(array(245, 240, 220));
			$objTree->setLinkColor(array(150, 150, 200));
			
			$objTree->setTextTitleColor(array(0, 0, 0));
			$objTree->setFTFont(SUMO_PATH.'/applications/fonts/verdana.ttf', 7);
			
			$objTree->stream();
			
		}
		
		break;
		
	// 
	case 'GET_GROUP2ACCESSPOINTS':
	
		$group = sumo_get_group_info($_GET['id']);
		
		if($SUMO['DB']->IsConnected()) 
		{
			$query = "SELECT id,node,path,name FROM ".SUMO_TABLE_ACCESSPOINTS."
			  WHERE (
			  		 usergroup LIKE '".$group['usergroup']."' 
					 OR usergroup LIKE '".$group['usergroup'].";%'
					 OR usergroup LIKE '%;".$group['usergroup']."'
					 OR usergroup LIKE '%;".$group['usergroup'].";%'
					 )
			  ORDER BY node,name,path";

			$rs = $SUMO['DB']->Execute($query);

			/**
			 *	phpTreeGraph
			 *	Species hierarchy demo with images
			 * 	@author Mathias Herrmann
			 **/
			
			//include GD rendering class
			require_once SUMO_PATH_MODULE.'/classes/class.gdrender.php';
			
			//create new GD renderer, optinal parameters: LevelSeparation,  SiblingSeparation, SubtreeSeparation, defaultNodeWidth, defaultNodeHeight
			$objTree = new GDRenderer(100, 10, 30, 100, 13);
			
			//add nodes to the tree, parameters: id, parentid optional title, text, width, height, image(path)
			$objTree->add(1, 0, $group['usergroup']);
			
			$g = 2;
			
			while($tab = $rs->FetchRow()) 
			{
				$tab2['name'] = sumo_get_accesspoint_name($tab2['name'], $_COOKIE['language']);
				
				$objTree->add($g++, 1, $tab2['name']);
			}
			
			$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);
			$objTree->setNodeTitleColor(array(245, 240, 220));
			$objTree->setLinkColor(array(150, 150, 200));
			
			$objTree->setTextTitleColor(array(0, 0, 0));
			$objTree->setFTFont(SUMO_PATH.'/applications/fonts/verdana.ttf', 7);
			
			$objTree->stream();
			
		}
		
		break;
		
	// Unknow command
	default:		
		echo "E00121X";
		break;
}

exit;

?>