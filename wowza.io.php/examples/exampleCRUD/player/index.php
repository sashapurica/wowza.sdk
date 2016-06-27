<?php 
/**
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

//Si hay algun problema al visualizar el player, verificar las IPS del servidor y del cliente que se usan para generar el hash...


  require(dirname(dirname(dirname(dirname(__FILE__)))).'/libs/wowza.php');
  include(dirname(dirname(__FILE__)).'/config.php');

  $wowzaServerIP = Conf::STREAMING_SERVER_IP;
  $wow = new Wowza($wowzaServerIP.":".Conf::STREAMING_SERVER_PORT);

  if (isset($_GET['secured']) && $_GET['secured'] == 1 ){
    $isSecured = true;
  }else{
    $isSecured = false;   
  }

  if (isset($_GET['appName'])){
    $name = $_GET['appName'];
  }

    if ($isSecured) {

      $wowzaContentURL = 'http://'.$wowzaServerIP.':1935/'.$name.'/mp4:myStream_aac/playlist.m3u8';
      $wowzaContentPath = $name.'/mp4:myStream_aac';
      $wowzaSecureToken = 'mySharedSecret';
      $wowzaTokenPrefix = 'wowzatoken';
      $wowzaCustomParameter = $wowzaTokenPrefix . "CustomParameter=myParameter";
      $wowzaSecureTokenStartTime = $wowzaTokenPrefix  ."starttime=". time() ;
      $wowzaSecureTokenEndTime = $wowzaTokenPrefix  ."endtime=". (time() + (7 * 24 * 60 * 60) );
      //usar $_SERVER['REMOTE_ADDR'] en vez de la 9.20
      //usa luego get_ip() y prueba.. si la ip no coincide no engancha.... xD
      echo ("testip".get_ip()."----\n");
      echo ("testip2".Conf::CLIENT_TEST_IP."----\n");  
          
      $auxIP=get_ip();
      //if ($auxIP=="127.0.0.1"){
      //  $viewer_ip = "127.0.0.1";
        //$viewer_ip = Conf::CLIENT_TEST_IP;
      //}else{
      //  $viewer_ip = "127.0.0.1";
      $viewer_ip = $auxIP;
        //$viewer_ip = Conf::CLIENT_TEST_IP;
      //}

      $hashstr = $wowzaContentPath ."?". $viewer_ip ."&". $wowzaSecureToken ."&". $wowzaCustomParameter ."&". $wowzaSecureTokenEndTime ."&". $wowzaSecureTokenStartTime;

      $hash = hash('sha256', $hashstr ,1);
      $usableHash=strtr(base64_encode($hash), '+/', '-_');
      $url = $wowzaContentURL ."?". $wowzaSecureTokenStartTime ."&". $wowzaSecureTokenEndTime ."&". $wowzaCustomParameter ."&".  $wowzaTokenPrefix ."hash=".$usableHash;
      $streamerUrl = "rtmp://".$wowzaServerIP.":".Conf::STREAMING_SERVER_DELIVERY_PORT."/".$name;
      $playerUrl = $url;
    }else{
      $streamerUrl = "rtmp://".$wowzaServerIP.":".Conf::STREAMING_SERVER_DELIVERY_PORT."/".$name;
      $playerUrl = "http://".$wowzaServerIP.":".Conf::STREAMING_SERVER_DELIVERY_PORT."/".$name."/mp4:myStream_aac/playlist.m3u8";
    }





    function get_ip() {

        //Just get the headers if we can or else use the SERVER global
        if ( function_exists( 'apache_request_headers' ) ) {

          $headers = apache_request_headers();

        } else {

          $headers = $_SERVER;

        }

        //Get the forwarded IP if it exists
        if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {

          $the_ip = $headers['X-Forwarded-For'];

        } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
        ) {

          $the_ip = $headers['HTTP_X_FORWARDED_FOR'];

        } else {
          
          $the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );

        }

        return $the_ip;

      }







?>


<!doctype html>

<head>

   <!-- player skin -->
   <link rel="stylesheet" href="skin/functional.css">

   <!-- site specific styling -->
   <style>
   body { font: 12px "Myriad Pro", "Lucida Grande", sans-serif; text-align: center; padding-top: 5%; }
   .flowplayer { width: 80%; }
   </style>

   <!-- for video tag based installs flowplayer depends on jQuery 1.7.2+ -->
   <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>

   <!-- include flowplayer -->
   <script src="flowplayer.min.js"></script>

</head>

<body>



<div data-live="true"
     data-ratio="0.5625"
     class="flowplayer fixed-controls">
 
   <video data-title="Live stream">
<source type="application/x-mpegurl"
        src="<?php echo $playerUrl;?>">
   </video>
 
</div>



</body>
