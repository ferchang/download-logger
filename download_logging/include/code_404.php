<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

header('HTTP/1.1 404 Not Found');
echo "<h1>404 Not Found</h1>";

require ROOT.'include/code_log_common.php';

require ROOT.'include/code_write2log.php';

exit;

?>