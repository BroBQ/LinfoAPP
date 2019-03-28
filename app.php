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
/*
 * Adaptation of Linfo by Joseph Gillotti 2010-2015 <joe@u13.net>
 *
 *
 * Linfo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Linfo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Linfo. If not, see <http://www.gnu.org/licenses/>.
*/

// Load libs
require_once dirname(__FILE__).'/init.php';

// Load settings and language
$linfo = new Linfo;

// Run through /proc or wherever and build our list of settings
$linfo->scan();
$anotherParser = $linfo->getParser();
// var_dump($anotherParser->getCpu());
// $cpu = $anotherParser->getCPU();
// echo("Model: $cpu[0][Model]");
$names = ["OS", "Kernel", "AccessedIP", "Distro", "RAM", "HD", "Mounts", "Load", "HostName", "UpTime", "CPU", "Model", "CPUArchitecutre", "Network Devices", "Devices", "Temps", "Battery", "Raid", "Wifi", "SoundCards", "processStats", "services", "numLoggedIn", "virtualization", "cpuUsage", "phpVersion", "webService", "contains"];
$parser = $linfo->getInfo();
// $cpu = $linfo->getCPU();
echo "<table>";
echo "<tr><td colspan='2'>SYSTEM:</td></tr>";
echo "<tr>";
echo "<td>OS:</td>";
echo "<td>$parser[OS]</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Kernel:</td>";
echo "<td>$parser[Kernel]</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Hostname:</td>";
echo "<td>$parser[HostName]</td>";
echo "</tr>";
echo "</table>";
// echo("OS: $parser[OS]<br/>");
// echo("Kernel: $parser[Kernel]<br/>");
// echo("Distro: $parser[Distro]<br/>");
// echo("RAM: $parser[RAM]<br/>");
// echo("HD: $parser[HD]<br/>");
// echo("Mounts: $parser[Mounts]<br/>");
// echo("HostName: $parser[Hostname]<br/>");
// echo("CPU:<br/>");
echo "<br/>";
echo "<table>";
echo "<tr><td colspan='2'>CPU:</td></tr>";
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
	// echo $parser["CPU"][0]["Vendor"];	
}
echo "</table>";
echo "<br/>";
// echo $parser["CPU"][0]["Model"];
// echo "<br/>";
// echo $parser["CPU"][0]["Vendor"];
// echo("CPU: $anotherParser->getCPU()");
// $linfo->output();
// echo("RAM:<br/>");
echo "<table>";
echo "<tr><td colspan='2'>RAM:</td></tr>";
foreach ($parser["RAM"] as $key => $value) {
	if ($value == "Physical") {
		echo "<tr><td>$key</td><td>$value</td></tr>";
	} else {
		echo "<tr><td>$key</td><td>$value B</td></tr>";
	}
}
echo "</table>";
echo "<br/>";
// var_dump($parser["Network Devices"]);
// echo '<pre>' . var_export($parser["Network Devices"], true) . '</pre>';
echo "<table>";
echo "<tr><td colspan='9'>Network Devices:</td></tr>";

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

// echo '<pre>' . var_export($parser["HD"], true) . '</pre>';
echo "<br/>";

echo "<table>";
echo "<tr><td colspan='9'>Drives:</td></tr>";
foreach ($parser["HD"] as $key => $value) {
	echo "<tr>";
	echo "<td>Drive $key</td>";
	foreach ($value as $key2 => $value2) {
		if($key2 == 'partitions') {
			echo "<tr>";
			foreach ($value2 as $key3 => $value3) {
				// echo "<td>$key3</td>";
				echo "<tr>";
				echo "<td>Partition $key3</td>";
				echo "<td>$value3[size] B</td>";
				// foreach ($value3 as $key4 => $value4) {
				// 	// echo "<td>$key4</td><td>$value4</td>";
				// 	echo "<td>$value4 - $key4 B</td>";					
				// }
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
