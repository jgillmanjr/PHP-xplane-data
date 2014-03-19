<?php
namespace FreedomForged\XPlaneData;
require('dataLabels.php');

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
		 * @var resource $sock The created socket resource
		 *
		 */
		private $sock;

		/**
		 *
		 * Construct
		 *
		 */
		public function __construct($listenAddr = '127.0.0.1', $listenPort = 49002)
		{
			$this->listenAddr = $listenAddr;
			$this->listenPort = $listenPort;
		}

		/**
		 *
		 * @return array The unpacked data
		 * Pull all the available dataz
		 *
		 */
		public function getData()
		{
			/**
			 * The creation and closing of the socket at the time of the method call is required.
			 * If this isn't done, the socket queue fills up, so subsequent calls will show stale data.
			 * If someone can figure out a more elegant way of doing this, please let me know!
			 */
			$this->sock = socket_create(AF_INET, SOCK_DGRAM, 17); // 17 == udp
			socket_bind($this->sock, $this->listenAddr, $this->listenPort);
			$pkgSize = socket_recv($this->sock, $pkg, 65500, 0); // Flags param is required. Send it 0 to indicate no flags?

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
			$rawData = unpack($format, $pkg);

			socket_close($this->sock);
			return convertToLabels($rawData);
		}
	}
?>
