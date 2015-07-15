<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

header('X-download-logger: true');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: $size");

$size2=$size;
if($size2>1000*1000) {
	$size2/=1000*1000;
	$size_unit='MB';
}
else if($size2>1000) {
	$size2/=1000;
	$size_unit='KB';
}
else $size_unit='Bytes';
$size2=round($size2, 2);

//touch('fff');

$report="time: $t1";
$report.="\nmethod: {$_SERVER['REQUEST_METHOD']}";
$report.="\nfile: $file_name";
$report.="\nsize: $size2 $size_unit";
$report.="\nip: {$_SERVER['REMOTE_ADDR']}";
$report.="\nuser agent: {$_SERVER['HTTP_USER_AGENT']}";
$report.="\n\n";

$fp=fopen('download_log.txt', 'a');
flock($fp, LOCK_EX);
fwrite($fp, $report);
fclose($fp);

exit;

?>