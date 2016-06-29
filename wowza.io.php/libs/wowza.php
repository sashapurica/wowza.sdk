<?php
/*
 *  Copyright by wowza.io at http://www.wowza.io
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

//    Use camelCase for variable names and method names....

require(dirname(dirname(__FILE__)).'/includes/Mustache/Autoloader.php');
Mustache_Autoloader::register();


class Wowza{

	private $wowzaServer;

	function __construct($wowzaServer = "127.0.0.1:8087") {
		$this->wowzaServer = $wowzaServer;
	}

	function getWowzaServer(){
		return $this->wowzaServer;
	}

	function setWowzaServer($wowzaServer = "127.0.0.1:8087"){
		$this->wowzaServer = $wowzaServer;
	}

	function getApplications(){
		$service_url = "http://".$this->wowzaServer."/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications";
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Accept:application/json',
			'charset=utf-8'
		));
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		return $curl_response; //JSON as string
	}

	function getApplication($applicationName){
		$service_url = "http://".$this->wowzaServer."/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/".$applicationName;
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Accept:application/json',
			'charset=utf-8'
		));
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		return $curl_response;

	}

	function deleteApplication($applicationName){
		$service_url = "http://".$this->wowzaServer."/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/".$applicationName;
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Accept:application/json',
			'charset=utf-8'
		));
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		//return var_export($decoded);
		//file_put_contents("asdf.txt", json_encode($decoded, JSON_PRETTY_PRINT));
		//return var_export($decoded, JSON_PRETTY_PRINT);
		return true;
	}

	function deleteAllApplications(){
		$allApplications = $this->getApplications();
		$array = json_decode($allApplications, true);
		$max = sizeof($array['applications']);
		for($i = 0; $i < $max;$i++){
			$this->deleteApplication($array['applications'][$i]['id']);
		}
		return true;
	}

	function dumpApplicationConfig($applicationName){
		$service_url = "http://".$this->wowzaServer."/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/".$applicationName;
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Accept:application/json',
			'charset=utf-8'
		));
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		//return var_export($decoded);
		file_put_contents($applicationName.".json", json_encode($decoded, JSON_PRETTY_PRINT));
		return true;
	}

	function createNonSecuredApplication($applicationName){
		$m = new Mustache_Engine;
		$data = array('applicationName' => $applicationName);
		$service_url = "http://".$this->wowzaServer."/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/".$applicationName;
		$curl = curl_init($service_url);
		$config_file = file_get_contents(dirname(dirname(dirname(__FILE__))).'/wowza.io.templates/defaultNonSecuredApplication.json', FILE_USE_INCLUDE_PATH);
		$configData = $m->render($config_file,$data);
		$headers = array(
		'Content-Type: application/json; charset=utf-8',
		'Accept: application/json; charset=utf-8' 
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $configData);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		return var_export($decoded);
	}

	function createSecuredApplication($applicationName, $sharedSecret = "mySharedSecret", $wowzaParameterPrefix = "wowzatoken"){
		$m = new Mustache_Engine;
		$data = array(
			'applicationName' => $applicationName,
			'sharedSecret' => $sharedSecret,
			'wowzaParameterPrefix' => $wowzaParameterPrefix
		);
		$service_url = "http://".$this->wowzaServer."/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/".$applicationName;
		$curl = curl_init($service_url);
		$config_file = file_get_contents(dirname(dirname(dirname(__FILE__))).'/wowza.io.templates/defaultSecuredApplication.json', FILE_USE_INCLUDE_PATH);
		$configData = $m->render($config_file,$data);
		$headers = array(
		'Content-Type: application/json; charset=utf-8',
		'Accept: application/json; charset=utf-8' 
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $configData);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		return var_export($decoded);
	}

	function restartApplication($applicationName){
		$service_url = "http://".$this->wowzaServer."/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/".$applicationName."/actions/restart";
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Accept:application/json',
			'charset=utf-8'
		));
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}
		//return var_export($decoded);
		//file_put_contents("asdf.txt", json_encode($decoded, JSON_PRETTY_PRINT));
		return var_export($decoded, JSON_PRETTY_PRINT);
		//return true;
	}

}

?>

