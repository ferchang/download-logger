<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$report.="\n\n";
$fp=fopen('download_log.txt', 'a');
flock($fp, LOCK_EX);
fwrite($fp, $report);
fclose($fp);

?>