<?php
	/**
	 *
	 * X-Plane 10 UDP Parsing for dummies (or something)
	 * @author Jason Gillman Jr. jason@rrfaae.com
	 *
	 */

	$listenAddr = '127.0.0.1';
	$listenPort = 49002;

	$sockServ = stream_socket_server('udp://' . $listenAddr . ':' . $listenPort, $errno, $errstr, STREAM_SERVER_BIND);
	define('FORMAT', '@5/Isentence/@9/f*');
	
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
