<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

//--------- config ----------->

error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

$min_duration=5;

$chunk_size=500;

set_time_limit(60*30);

//--------- config -----------<

ini_set('display_errors', '0');

ignore_user_abort(true);

define('ROOT', str_replace('\\', '/', __DIR__).'/');

$t1=time();

$path_parts=pathinfo($_GET['file']);
$file_name=$path_parts['basename'];
$file='../files/'.$file_name;
if(!file_exists($file)) require ROOT.'include/404.php';
$size=filesize($file);
if($_SERVER['REQUEST_METHOD']==='HEAD') require ROOT.'include/http_head.php';

require ROOT.'include/func_my_flush.php';

header('X-download-logger: true');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: $size");

//header('Content-Type: text/plain');

my_flush();

$fp=fopen($file, 'rb');

$output_bytes=0;

while(!feof($fp)) {
	$out=fread($fp, $chunk_size);
	if(feof($fp)) {
		$duration=time()-$t1;
		$delta=$min_duration-$duration;
		if($delta<=0) {
			echo $out;
			my_flush();
			if(connection_aborted()) break;
			$duration=time()-$t1;
			$output_bytes+=strlen($out);
			break;
		}
		if(strlen($out)>$delta) {
			$tmp=substr($out, 0, strlen($out)-$delta);
			echo $tmp;
			my_flush();
			if(connection_aborted()) break;
			$output_bytes+=strlen($tmp);
			$duration=time()-$t1;
			$out=substr($out, strlen($tmp));
		}
		$delay=($delta/strlen($out))*1000*1000;
		for($i=0; $i<strlen($out); $i++) {
			usleep($delay);
			echo substr($out, $i, 1);
			my_flush();
			if(connection_aborted()) break;
			$output_bytes++;
		}
	}
	else {
		echo $out;
		my_flush();
		if(connection_aborted()) break;
		$output_bytes+=strlen($out);
	}
}
fclose($fp);

$total_duration=time()-$t1;
if(!isset($duration)) $duration=$total_duration;

if(!$duration) {
	$speed='NA';
	$speed_unit='';
}
else {
	$speed=($output_bytes/$duration)*8;
	if($speed>1000*1000) {
		$speed/=1000*1000;
		$speed_unit='Mbps';
	}
	else if($speed>1000) {
		$speed/=1000;
		$speed_unit='Kbps';
	}
	$speed=round($speed, 2);
}

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

$report="time: $t1";
$report.="\nmethod: {$_SERVER['REQUEST_METHOD']}";
$report.="\nfile: $file_name";
$report.="\nsize: $size2 $size_unit";
$report.="\nduration: $duration";
$report.="\ntotal duration: $total_duration";
$report.="\nspeed: $speed $speed_unit";
$report.="\nip: {$_SERVER['REMOTE_ADDR']}";
$report.="\nuser agent: {$_SERVER['HTTP_USER_AGENT']}";
$report.="\noutput_bytes: $output_bytes";
if(connection_aborted()) $report.="\naborted at ".round(($output_bytes/$size)*100, 2)."% ($output_bytes/$size)";
$report.="\n\n";

$fp=fopen('download_log.txt', 'a');
flock($fp, LOCK_EX);
fwrite($fp, $report);
fclose($fp);

?>
