<?php
/**
 * SUMO: Load required extensions 
 *
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */


/**
 * WARNING: the function dl() is not supported on Multithreaded systems like Windows XP. 
 * You need an external library for it, that has to be loaded when your server does. 
 * If not, you will get a fatal error and the script will stop parsing.
 * Use the "extensions" statement in your php.ini when operating under such an environment.
 */

//sumo_dl($sumo_db['type']);  // Database extension
//sumo_dl('ldap');  // removed, see sumo.php
//sumo_dl('curl');  // removed, see sumo.php

?>