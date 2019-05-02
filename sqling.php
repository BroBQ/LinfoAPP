<?php

class Sqling {
	private $servername = "localhost";
	private $username = "root";
	private $password = "";
	private $dbname = "linfoapp";
	
	private $databaseConnection = NULL;
	
	private $sqlSystem = '';
	private $sqlCPU = '';
	private $sqlRAM = '';
	private $netDevicesDataSQL = array();

	function connect() {
		$databaseConnection = new mysqli($servername, $username, $password, $dbname);
		if ($databaseConnection->connect_error) {
			die("Connection failed: " . $databaseConnection->connect_error);
		}
	}
	
	function createSQL($parser) {
		$sqlSystem = 'INSERT INTO `system` (`Hostname`, `Processes`, `Threads`, `SystemLoad`, `Uptime`, `SystemDate`) VALUES ("' . $parser['HostName'] . '","' . $parser['processStats']['proc_total'] . '","' . $parser['processStats']['threads'] . '","' . $parser['Load'] . '","' . $parser['UpTime']['text'] . '", NOW())';
	
	
		$MHzSum = 0;
		$usageSum = 0;
		for ($i=0; $i < count($parser["CPU"]); $i++) { 
			$MHzSum += $parser["CPU"][$i]["MHz"];
			$usageSum += $parser["CPU"][$i]["usage_percentage"];
		}
		$avgMHz = $MHzSum / count($parser["CPU"]);
		$avgUsage = $usageSum / count($parser["CPU"]);
		$sqlCPU = 'INSERT INTO `cpuinfo` (`Model`, `MHz`, `UsagePercentage`, `CPUDate`) VALUES ("' . $parser["CPU"][0]["Model"] . '","' . $avgMHz . '","' . $avgUsage . '", NOW())';
		
	
		$sqlRAM = "INSERT INTO `ram` (`Total`, `Free`, `RAMDate`) VALUES (" . $parser["RAM"]["total"] . "," . $parser["RAM"]["free"] . ", NOW())";
		
		
		foreach ($parser["Network Devices"] as $key => $value) {
			$a = array($key, $value["recieved"]["bytes"], $value["sent"]["bytes"], $value["state"], $value["type"]);
			$netSQL = 'INSERT INTO `networkdevices` (`Name`, `ReceivedBytes`, `SentBytes`, `Status`, `DeviceType`, `NetworkDate`) VALUES ("' . $a[0] . '","' . $a[1] . '","' . $a[2] . '","' . $a[3] . '","' . $a[4] . '", NOW())';
		
			array_push($netDevicesDataSQL, $netSQL);
		}		
	}
	
	function send() {
		$databaseConnection->query($sqlSystem);
		$databaseConnection->query($sqlCPU);
		$databaseConnection->query($sqlRAM);
		foreach ($netDevicesDataSQL as $key => $value) {
			$databaseConnection->query($value);
		}
	}
	
	function closeConnection() {
		$databaseConnection->close();
	}
}




?>