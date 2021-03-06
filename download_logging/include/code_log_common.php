<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$report="time: $t1";
$report.="\nmethod: {$_SERVER['REQUEST_METHOD']}";
$report.="\nfile: $file_name_with_path";
if(!file_exists($file)) $report.=" (404 Not Found)";
if(is_dir($file)) $report.=" (Directory)";

$report.="\nip: {$_SERVER['REMOTE_ADDR']}";
if(isset($_SERVER['HTTP_CLIENT_IP'])) $report.="\nHTTP_CLIENT_IP: {$_SERVER['HTTP_CLIENT_IP']}";
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $report.="\nHTTP_X_FORWARDED_FOR: {$_SERVER['HTTP_X_FORWARDED_FOR']}";
if(isset($_SERVER['HTTP_X_FORWARDED'])) $report.="\nHTTP_X_FORWARDED: {$_SERVER['HTTP_X_FORWARDED']}";
if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) $report.="\nHTTP_X_CLUSTER_CLIENT_IP: {$_SERVER['HTTP_X_CLUSTER_CLIENT_IP']}";

if(isset($_SERVER['HTTP_REFERER'])) {
 if($_SERVER['HTTP_REFERER']!=='') $referrer=$_SERVER['HTTP_REFERER'];
 else $referrer='Empty';
}
else $referrer='NA';
$report.="\nreferrer: $referrer";

$report.="\nuser agent: ";
if(isset($_SERVER['HTTP_USER_AGENT'])) $report.=$_SERVER['HTTP_USER_AGENT'];
else $report.="NA";

?>