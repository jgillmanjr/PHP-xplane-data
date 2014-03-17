<?php
namespace FreedomForged\XPlaneData;
	/**
	 *
	 * X-Plane 10 UDP Parsing class for dummies (or something)
	 * @author Jason Gillman Jr. jason@rrfaae.com
	 *
	 */

	class retrieve
	{
		/**
		 *
		 * @var string $listenAddr The address you want to listen on (0.0.0.0 for all available, 127.0.0.1 default)
		 *
		 */
		private $listenAddr;

		/**
		 *
		 * @var integer $listenPort The port you want to listen on (49002 default)
		 *
		 */
		private $listenPort;

		/**
		 *
		 * @var resource $sockServ The created socket resource
		 *
		 */
		private $sockServ;

		/**
		 *
		 * Construct
		 *
		 */
		public function __construct($listenAddr = '127.0.0.1', $listenPort = 49002)
		{
			$this->listenAddr = $listenAddr;
			$this->listenPort = $listenPort;

			$this->sockServ = stream_socket_server('udp://' . $this->listenAddr . ':' . $this->listenPort, $errno, $errstr, STREAM_SERVER_BIND);
			if (!$this->sockServ) 
			{
				die("$errstr ($errno)");
			}
		}

		/**
		 *
		 * @return array The unpacked data
		 * Pull all the available dataz
		 *
		 */
		public function getRawData()
		{
			$pkg = stream_socket_recvfrom($this->sockServ, 65500);
			$sentenceCount = (strlen($pkg) - 5) / 36;
			$formatBase = '@5/Isentence 1/@9/f8data 1 - ';

			if($sentenceCount > 1)
			{
				$format = $formatBase;
				$i = 2;
				for($i = 2; $i <= $sentenceCount; $i++)
				{
					$format .= '/Isentence ' . $i . '/f8data ' . $i . ' - ';
				}
			}
			else
			{
				$format = $formatBase;
			}
			$dataArray = unpack($format, $pkg);

			return $dataArray;
			$this->sockServ = NULL;
		}
	}
?>
