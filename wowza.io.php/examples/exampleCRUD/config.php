<?php 
/**
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

//In windows: add the vars to advanced properties
//In linux:   add the vars to /etc/apache2/envvars


define("STREAMING_SERVER_IP", getenv("WOWZA_SERVER_ADDRESS"));
define("STREAMING_SERVER_PORT", getenv("WOWZA_SERVER_PORT"));
define("STREAMING_SERVER_DELIVERY_PORT", getenv("WOWZA_SERVER_DELIVERY_PORT"));
define("STATSD_SERVER", getenv("STATSD_SERVER_ADDRESS"));

?>

