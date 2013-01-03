<?php
/**
 * SUMO: Setup php.ini parameters
 *
 * @version    0.2.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

// ...to stop PHPSESSID from appearing in the url
ini_set('url_rewriter.tags',	'');
ini_set('session.use_trans_sid', 0);
ini_set('session.use_cookies',   1);
ini_set('magic_quotes_gpc',      0);

// Unset magic_quotes_runtime - do not change!
set_magic_quotes_runtime(0);

?>