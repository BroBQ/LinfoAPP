<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>System Info</title>
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
echo("OS: $parser[OS]<br/>");
echo("Kernel: $parser[Kernel]<br/>");
// echo("Distro: $parser[Distro]<br/>");
// echo("RAM: $parser[RAM]<br/>");
// echo("HD: $parser[HD]<br/>");
// echo("Mounts: $parser[Mounts]<br/>");
// echo("HostName: $parser[Hostname]<br/>");
echo("CPU:<br/>");
foreach ($parser["CPU"] as $key => $value) {
	// echo $key . " " . $value;
	echo "$key ";
	foreach ($value as $key2 => $value2) {
		if ($key2 == "usage_percentage") {
			echo "Usage Percentage" . " " . $value2 . " ";
		} else {
		echo $key2 . " " . $value2 . " ";
		}
	}
	echo "<br/>";
	// echo $parser["CPU"][0]["Vendor"];	
}
// echo $parser["CPU"][0]["Model"];
// echo "<br/>";
// echo $parser["CPU"][0]["Vendor"];
// echo("CPU: $anotherParser->getCPU()");
// $linfo->output();
echo("RAM:<br/>");
foreach ($parser["RAM"] as $key => $value) {
	if ($value == "Physical") {
		echo $key . " " . $value;
	} else {
		echo $key . " " . $value . " B";
	}
	echo "<br/>";
}
?>
<script src="main.js"></script>
</body>
</html>
