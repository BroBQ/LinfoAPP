<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>System Info</title>
	<link rel="stylesheet" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link rel="icon" href="favicon.png" type="image/x-con">
</head>
<body>
<?php
//connecting database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "linfoapp";
$databaseConnection = new mysqli($servername, $username, $password, $dbname);
if ($databaseConnection->connect_error) {
    die("Connection failed: " . $databaseConnection->connect_error);
} 



// Load libs
require_once dirname(__FILE__).'/init.php';

// Load settings and language
$linfo = new Linfo;

// Run through /proc or wherever and build our list of settings
$linfo->scan();
$anotherParser = $linfo->getParser();

$names = ["OS", "Kernel", "AccessedIP", "Distro", "RAM", "HD", "Mounts", "Load", "HostName", "UpTime", "CPU", "Model", "CPUArchitecutre", "Network Devices", "Devices", "Temps", "Battery", "Raid", "Wifi", "SoundCards", "processStats", "services", "numLoggedIn", "virtualization", "cpuUsage", "phpVersion", "webService", "contains"];
$parser = $linfo->getInfo();

// Table for system specs
echo "<table>";
echo "<tr><td class='parameter' colspan='2'>SYSTEM:</td></tr>";
echo "<tr><td>OS:</td><td>$parser[OS]</td></tr>";
// echo "<tr><td>Distribution:</td><td>$parser[Distro]</td></tr>";
echo "<tr><td>Kernel:</td><td>$parser[Kernel]</td></tr>";
echo "<tr><td>Hostname:</td><td>$parser[HostName]</td></tr>";
echo "<tr><td>Architecture:</td><td>$parser[CPUArchitecture]</td></tr>";
echo "<tr><td>Porcesses:</td><td>" . $parser["processStats"]["proc_total"] . "</td></tr>";
echo "<tr><td>Threads:</td><td>" . $parser["processStats"]["threads"] . "</td></tr>";
echo "<tr><td>Load:</td><td>$parser[Load]</td></tr>";
echo "<tr><td>CPU Usage:</td><td>" . $parser["cpuUsage"] . "</td></tr>";
echo "<tr><td>Uptime:</td><td>" . $parser["UpTime"]["text"] . "</td></tr>";
echo "<tr><td>Booted:</td><td>" . date('d/m/Y H:i:s', $parser["UpTime"]["bootedTimestamp"]) . "</td></tr>";
echo "</table>";
// echo '<pre>' . var_export($parser["UpTime"], true) . '</pre>';

//creating sql for system table
$sqlSystem = 'INSERT INTO `system` (`Hostname`, `Processes`, `Threads`, `SystemLoad`, `Uptime`, `SystemDate`) VALUES ("' . $parser['HostName'] . '","' . $parser['processStats']['proc_total'] . '","' . $parser['processStats']['threads'] . '","' . $parser['Load'] . '","' . $parser['UpTime']['text'] . '", NOW())';

echo "<br/>";

// Table for CPU
echo "<table>";
echo "<tr><td class='parameter' colspan='2'>CPU:</td></tr>";
foreach ($parser["CPU"] as $key => $value) {
	// echo $key . " " . $value;
	echo "<tr>";
	echo "<td>Core $key</td>";
	echo "<td>";
	foreach ($value as $key2 => $value2) {
		if ($key2 == "usage_percentage") {
			echo "Usage Percentage" . " " . $value2 . " ";
		} else {
		echo $key2 . " " . $value2 . " ";
		}
	}
	echo "</td>";
	echo "</tr>";
}
echo "</table>";

$MHzSum = 0;
$usageSum = 0;
for ($i=0; $i < count($parser["CPU"]); $i++) { 
	$MHzSum += $parser["CPU"][$i]["MHz"];
	$usageSum += $parser["CPU"][$i]["usage_percentage"];
}
$avgMHz = $MHzSum / count($parser["CPU"]);
$avgUsage = $usageSum / count($parser["CPU"]);
$sqlCPU = 'INSERT INTO `cpuinfo` (`Model`, `MHz`, `UsagePercentage`, `CPUDate`) VALUES ("' . $parser["CPU"][0]["Model"] . '","' . $avgMHz . '","' . $avgUsage . '", NOW())';

echo "<br/>";

// Table for RAM
echo "<table>";
echo "<tr><td class='parameter' colspan='2'>RAM:</td></tr>";
foreach ($parser["RAM"] as $key => $value) {
	if ($value == "Physical") {
		echo "<tr><td>$key</td><td>$value</td></tr>";
	} else {
		echo "<tr><td>$key</td><td>$value B</td></tr>";
	}
}
echo "</table>";

$sqlRAM = "INSERT INTO `ram` (`Total`, `Free`, `RAMDate`) VALUES (" . $parser["RAM"]["total"] . "," . $parser["RAM"]["free"] . ", NOW())";

echo "<br/>";

// Table for Network Devices
echo "<table>";
echo "<tr><td class='parameter' colspan='9'>Network Devices:</td></tr>";
foreach ($parser["Network Devices"] as $key => $value) {
	echo "<tr>";
	echo "<td>$key</td>";
	foreach ($value as $key2 => $value2) {
		if($key2=="recieved" || $key2=="sent")
		{
			echo "<td class='parameter'>$key2</td>";
			echo "<td>";
			foreach ($value2 as $key3 => $value3) {
				echo "$key3 $value3 ";
			}
			echo "</td>";
		}
		else
		{
			echo "<td class='parameter'>$key2</td><td>$value2</td>";
		}
	}
	echo "</tr>";
}
echo "</table>";

$netDevicesDataSQL = array();
foreach ($parser["Network Devices"] as $key => $value) {
	$a = array($key, $value["recieved"]["bytes"], $value["sent"]["bytes"], $value["state"], $value["type"]);
	$netSQL = 'INSERT INTO `networkdevices` (`Name`, `ReceivedBytes`, `SentBytes`, `Status`, `DeviceType`, `NetworkDate`) VALUES ("' . $a[0] . '","' . $a[1] . '","' . $a[2] . '","' . $a[3] . '","' . $a[4] . '", NOW())';

	array_push($netDevicesDataSQL, $netSQL);
}

echo "<br/>";

// Table for Hard Disks
echo "<table>";
echo "<tr><td class='parameter' colspan='9'>Drives:</td></tr>";
foreach ($parser["HD"] as $key => $value) {
	echo "<tr>";
	echo "<td>Drive $key</td>";
	foreach ($value as $key2 => $value2) {
		if($key2 == 'partitions') {			
			foreach ($value2 as $key3 => $value3) {
				echo "<tr>";
				echo "<td>Partition $key3</td>";
				echo "<td colspan='8'>$value3[size] B</td>";
				echo "</tr>";
			}			
		} else if($key2 == 'reads' || $key2 == 'writes') {
		} else {
			echo "<td class='parameter'>$key2</td>";
			echo "<td>$value2</td>";
		}		
	}
	echo "</tr>";
}
echo "</table>";
// echo '<pre>' . var_export($parser["Mounts"], true) . '</pre>';
echo "<br/>";

// Table for Mounted Drives
echo "<table>";
echo "<tr><td class='parameter' colspan='7'>Mounted Drives</td></tr>";
echo "<tr id='devtypestyle'><td>Type</td><td>Mount Point</td><td>Label</td><td>Filesystem</td><td>Size</td><td>Used</td><td>Free</td></tr>";
foreach ($parser["Mounts"] as $key => $value) {	
	echo "<tr><td id='devtype'>" . $value["devtype"] . "</td><td>" . $value["mount"] . "</td><td>" . $value["label"] . "</td><td>" . $value["type"] . "</td><td>" . $value["size"] . "</td><td>" . $value["used"] . "</td><td>" . $value["free"] . "</td></tr>";	
}
echo "</table>";

// $databaseConnection->query($sqlSystem);
// if ($databaseConnection->query($sqlCPU) === TRUE) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $sqlSystem . "<br>" . $databaseConnection->error;
// }

// $databaseConnection->close();
// echo '<pre>' . var_export($parser["Network Devices"], true) . '</pre>';
// echo '<pre>' . var_export($parser["Mounts"], true) . '</pre>';
// echo $parser["CPU"][0]["Model"];
// echo '</br>';

// $MHzSum = 0;
// $usageSum = 0;
// for ($i=0; $i < count($parser["CPU"]); $i++) { 
// 	$MHzSum += $parser["CPU"][$i]["MHz"];
// 	$usageSum += $parser["CPU"][$i]["usage_percentage"];
// }
// $avgMHz = $MHzSum / count($parser["CPU"]);
// $avgUsage = $usageSum / count($parser["CPU"]);
// echo $avgMHz;
// echo $avgUsage;

$databaseConnection->query($sqlSystem);
$databaseConnection->query($sqlCPU);
$databaseConnection->query($sqlRAM);
foreach ($netDevicesDataSQL as $key => $value) {
	$databaseConnection->query($value);
}


?>
<script src="main.js"></script>
<script src="bubbles.js"></script>
</body>
</html>
