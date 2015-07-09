<?php

header('HTTP/1.1 404 Not Found');
echo "<h1>404 Not Found</h1>";

$report="time: ".time();
$report.="\nmethod: {$_SERVER['REQUEST_METHOD']}";
$report.="\nfile: $file_name (404 Not Found)";
$report.="\nip: {$_SERVER['REMOTE_ADDR']}";
$report.="\nuser agent: {$_SERVER['HTTP_USER_AGENT']}";
$report.="\n\n";

$fp=fopen('download_log.txt', 'a');
flock($fp, LOCK_EX);
fwrite($fp, $report);
fclose($fp);

exit;

?>