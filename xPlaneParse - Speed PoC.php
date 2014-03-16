<?php
	$sockServ = stream_socket_server('udp://127.0.0.1:49002', $errno, $errstr, STREAM_SERVER_BIND);
	define('FORMAT', '@5/f*');
	
	if (!$sockServ) 
	{
    	die("$errstr ($errno)");
	}
	else
	{
		while(TRUE)
		{
			$pkg = stream_socket_recvfrom($sockServ, 41);
			//echo $pkg . "\n"; // Debugging

			$infoArray = unpack(FORMAT, $pkg);
			//print_r($infoArray); // Debugging

			echo "Ground Speed kts: " . $infoArray[5] . "\n";
		}
	}
?>
