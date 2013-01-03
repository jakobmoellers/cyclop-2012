<?
require 'sumo/sumo.php';
?>

<?
$result = $SUMO;
unset($result['DB']);
unset($result['config']);
unset($result['server']);
unset($result['page']);
unset($result['connection']);
echo (json_encode($result));
?>