<?php
	$sockServ = stream_socket_server('udp://127.0.0.1:49002', $errno, $errstr, STREAM_SERVER_BIND);
	$formatBase = '@5/Isentence/@9/f8data 1 - '; // The first dataset
	//define('FORMAT', '@5/Isentence/@9/f8'); // One Sentence
	//define('FORMAT', '@5/Isentence1/@9/f8data #1 - /Isentence2/f8data #2 - '); // Two Sentences
	//define('FORMAT', '@5/Isentence1/@9/f8data #1 - /Isentence2/f8data #2 - /Isent3/f8data #3 - '); // Testing
	
	if (!$sockServ) 
	{
    	die("$errstr ($errno)");
	}
	else
	{
		while(TRUE)
		{
			$pkg = stream_socket_recvfrom($sockServ, 65500);
			$sentenceCount = (strlen($pkg) - 5) / 36;

			if($sentenceCount > 1)
			{
				$format = $formatBase;
				$i = 2;
				for($i = 2; $i <= $sentenceCount; $i++)
				{
					$format .= '/Isentence' . $i . '/f8data ' . $i . ' - ';
				}
			}
			else
			{
				$format = $formatBase;
			}
			//echo $pkg . "\n"; // Debugging

			$infoArray = unpack($format, $pkg);
			print_r($infoArray); // Debugging
			
			echo "Sentence Count should be: $sentenceCount \n";

			//echo "Ground Speed kts: " . $infoArray[5] . "\n";
			
		}
	}
?>
