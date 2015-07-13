<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

set_time_limit(60*2);

header('Content-Type: text/html');

$host='';
$file='test.txt';
$method='GET';
$bytes=0;

if(!$method) $method='GET';
if(!$host) $host='localhost';

echo '<pre>';
echo "host: $host\nfile: $file\nmethod: $method\nbytes to get: $bytes\n";
for($i=0; $i<500; $i++) echo '<!-- -->';
ob_flush();
flush();

$service_port = 80;

$address = gethostbyname($host);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$result = socket_connect($socket, $address, $service_port);

$in = "$method /files/$file HTTP/1.1\r\n";
$in .= "Host: $host\r\n";
$in .= "User-Agent: my bot!\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';

socket_write($socket, $in, strlen($in));

echo "Reading response: ";

$fp=fopen('out.txt', 'wb');

$out_bytes=0;
while ($out = socket_read($socket, 1000)) {
	//usleep(20000);
	$len=strlen($out);
	fwrite($fp, $out);
    echo "$len-";
	ob_flush();
	flush();
	if($bytes) {
		$out_bytes+=$len;
		if($out_bytes>=$bytes) {
			socket_close($socket);
			exit;
		}
	}
}

socket_close($socket);

?>
