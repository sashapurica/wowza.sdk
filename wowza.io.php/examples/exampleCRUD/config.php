<?php 
//    site: www.wowza.io
//    author: Carlos Camacho
//    email: carloscamachoucv@gmail.com
//    created: November 2015


//In windows: add the vars to advanced properties
//In linux:   add the vars to /etc/apache2/envvars


define("STREAMING_SERVER_IP", getenv("WOWZA_SERVER_ADDRESS"));
define("STREAMING_SERVER_PORT", getenv("WOWZA_SERVER_PORT"));
define("STREAMING_SERVER_DELIVERY_PORT", getenv("WOWZA_SERVER_DELIVERY_PORT"));
define("STATSD_SERVER", getenv("STATSD_SERVER_ADDRESS"));

?>

