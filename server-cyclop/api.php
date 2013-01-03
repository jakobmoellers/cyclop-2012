<?
require 'sumo/sumo.php';
?>

<?
$result = $SUMO;
/*
unset($result['DB']);
unset($result['config']);
unset($result['server']);
unset($result['page']);
unset($result['connection']);
unset($result['client']);
*/
//unset($result['user']['4']);
//unset($resutl['user']['password']);
$output = array(
"id" => $result['user']['id'],
"username" => $result['user']['username'],
"email" => $result['user']['email'],
"firstname" => $result['user']['firstname'],
"lastname" => $result['user']['lastname']
);
echo (json_encode($output));
?>

