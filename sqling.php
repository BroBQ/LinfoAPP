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
		$this->databaseConnection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		if ($this->databaseConnection->connect_error) {
			die("Connection failed: " . $this->databaseConnection->connect_error);
		}
	}
	
	function createSQL($parser) {
		$this->sqlSystem = 'INSERT INTO `system` (`Hostname`, `Processes`, `Threads`, `SystemLoad`, `Uptime`, `SystemDate`) VALUES ("' . $parser['HostName'] . '","' . $parser['processStats']['proc_total'] . '","' . $parser['processStats']['threads'] . '","' . $parser['Load'] . '","' . $parser['UpTime']['text'] . '", NOW())';
	
	
		$MHzSum = 0;
		$usageSum = 0;
		for ($i=0; $i < count($parser["CPU"]); $i++) { 
			$MHzSum += $parser["CPU"][$i]["MHz"];
			$usageSum += $parser["CPU"][$i]["usage_percentage"];
		}
		$avgMHz = $MHzSum / count($parser["CPU"]);
		$avgUsage = $usageSum / count($parser["CPU"]);
		$this->sqlCPU = 'INSERT INTO `cpuinfo` (`Model`, `MHz`, `UsagePercentage`, `CPUDate`) VALUES ("' . $parser["CPU"][0]["Model"] . '","' . $avgMHz . '","' . $avgUsage . '", NOW())';
		
	
		$this->sqlRAM = "INSERT INTO `ram` (`Total`, `Free`, `RAMDate`) VALUES (" . $parser["RAM"]["total"] . "," . $parser["RAM"]["free"] . ", NOW())";
		
		
		foreach ($parser["Network Devices"] as $key => $value) {
			$a = array($key, $value["recieved"]["bytes"], $value["sent"]["bytes"], $value["state"], $value["type"]);
			$netSQL = 'INSERT INTO `networkdevices` (`Name`, `ReceivedBytes`, `SentBytes`, `Status`, `DeviceType`, `NetworkDate`) VALUES ("' . $a[0] . '","' . $a[1] . '","' . $a[2] . '","' . $a[3] . '","' . $a[4] . '", NOW())';
		
			array_push($this->netDevicesDataSQL, $netSQL);
		}		
	}
	
	function send() {
		$this->databaseConnection->query($this->sqlSystem);
		$this->databaseConnection->query($this->sqlCPU);
		$this->databaseConnection->query($this->sqlRAM);
		foreach ($this->netDevicesDataSQL as $key => $value) {
			$this->databaseConnection->query($value);
		}
	}
	
	function closeConnection() {
		$this->databaseConnection->close();
	}
}




?>