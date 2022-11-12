<?php
set_time_limit(50);
$output = array();
$return_val="";
$crln = isset($argc) ? "\r\n" : "<br>";
exec("php /var/www/html/webapps/websocket/php-socket.php", $output, $return_val);
foreach($output as $row) {
    echo $row.$crln;
}
?>