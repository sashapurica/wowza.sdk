<?php
/*
 * Copyright by wowza.io at http://www.wowza.io
 *
 * This file is part of wowza.io SDK
 * 
 * Some open source application is free software: you can redistribute 
 * it and/or modify it under the terms of the GNU General Public 
 * License as published by the Free Software Foundation, either 
 * version 3 of the License, or (at your option) any later version.
 * 
 * Some open source application is distributed in the hope that it will 
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license GPL-3.0+ <http://spdx.org/licenses/GPL-3.0+>
 */

require('../libs/wowza.php');

$wow = new Wowza("127.0.0.1:8087");

$wow->deleteAllApplications();
//print ($wow->getApplications());
//print ($wow->createNonSecuredApplication("sssss"));
//print ($wow->createSecuredApplication("rugabuga"));
//print ($wow->deleteApplication("wakawaka"));
//$wow->dumpApplicationConfig("wakawaka");




?>

