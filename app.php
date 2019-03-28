<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>System Info</title>
	<link rel="stylesheet" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

</head>
<body>
<?php

// Load libs
require_once dirname(__FILE__).'/init.php';

// Load settings and language
$linfo = new Linfo;

// Run through /proc or wherever and build our list of settings
$linfo->scan();
$anotherParser = $linfo->getParser();

$names = ["OS", "Kernel", "AccessedIP", "Distro", "RAM", "HD", "Mounts", "Load", "HostName", "UpTime", "CPU", "Model", "CPUArchitecutre", "Network Devices", "Devices", "Temps", "Battery", "Raid", "Wifi", "SoundCards", "processStats", "services", "numLoggedIn", "virtualization", "cpuUsage", "phpVersion", "webService", "contains"];
$parser = $linfo->getInfo();

echo "<table>";
echo "<tr><td class='parameter' colspan='2'>SYSTEM:</td></tr>";
echo "<tr><td>OS:</td><td>$parser[OS]</td></tr>";
// echo "<tr><td>Distribution:</td><td>$parser[Distro]</td></tr>";
echo "<tr><td>Kernel:</td><td>$parser[Kernel]</td></tr>";
echo "<tr><td>Hostname:</td><td>$parser[HostName]</td></tr>";
echo "<tr><td>Architecture:</td><td>$parser[CPUArchitecture]</td></tr>";
foreach ($parser["processStats"] as $key => $value) {
	if($key == 'proc_total') {
		echo "<tr><td>Porcesses:</td><td>$value</td></tr>";
	}
	if($key == 'threads') {
		echo "<tr><td>Threads:</td><td>$value</td></tr>";
	}
}
echo "<tr><td>Load:</td><td>$parser[Load]</td></tr>";
echo "<tr><td>Uptime:</td><td>" . $parser["UpTime"]["text"] . "</td></tr>";
echo "<tr><td>Booted:</td><td>" . date('d/m/Y H:i:s', $parser["UpTime"]["bootedTimestamp"]) . "</td></tr>";

echo "</table>";
// echo '<pre>' . var_export($parser["UpTime"], true) . '</pre>';

echo "<br/>";
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
echo "<br/>";
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
echo "<br/>";
echo "<table>";
echo "<tr><td class='parameter' colspan='9'>Network Devices:</td></tr>";

foreach ($parser["Network Devices"] as $key => $value) {
	echo "<tr>";
	echo "<td>$key</td>";
	foreach ($value as $key2 => $value2) {
		// echo $value2;
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

echo "<br/>";

echo "<table>";
echo "<tr><td class='parameter' colspan='9'>Drives:</td></tr>";
foreach ($parser["HD"] as $key => $value) {
	echo "<tr>";
	echo "<td>Drive $key</td>";
	foreach ($value as $key2 => $value2) {
		if($key2 == 'partitions') {
			echo "<tr>";
			foreach ($value2 as $key3 => $value3) {
				echo "<tr>";
				echo "<td>Partition $key3</td>";
				echo "<td colspan='8'>$value3[size] B</td>";
				echo "</tr>";
			}
			echo "</tr>";
		} else if($key2 == 'reads' || $key2 == 'writes') {
		} else {
			echo "<td class='parameter'>$key2</td>";
			echo "<td>$value2</td>";
		}
		
	}
	echo "</tr>";
}
echo "</table>";
?>
<script src="main.js"></script>
</body>
</html>
