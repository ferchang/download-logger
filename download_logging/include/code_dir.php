<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

echo "<h1>There is no directory listing!</h1>";

require ROOT.'include/code_log_common.php';

require ROOT.'include/code_write2log.php';

exit;

?>