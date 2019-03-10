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

// $parser = $linfo->getInfo();
// $parser = $linfo->getParser();
// foreach ($linfo->getInfo() as $key)
// 	{
// 		foreach($key as $anotherKey)
// 		{
// 			print($anotherKey);
// 		}
// 	}
// print($parser->getCPU());
// print($parser);
$names = ["OS", "Kernel", "AccessedIP", "Distro", "RAM", "HD", "Mounts", "Load", "HostName"];
$parser = $linfo->getInfo();
print("OS: $parser[OS]\n");
print("Kernel: $parser[Kernel]");
// print_r(array_values($parser));
// print(count($parser));
// for($i = 0; $i < count($parser); $i++)
// {
// 	print($i);
// 	print($parser[$i]);
// }
?>