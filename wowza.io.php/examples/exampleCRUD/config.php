<?php 
//    site: www.wowza.io
//    author: Carlos Camacho
//    email: carloscamachoucv@gmail.com
//    created: November 2015


define("STREAMING_SERVER_IP", getenv("WOWZA_SERVER_ADDRESS"));
define("STREAMING_SERVER_PORT", getenv("WOWZA_SERVER_PORT"));
define("STREAMING_SERVER_DELIVERY_PORT", getenv("WOWZA_SERVER_DELIVERY_PORT"));
define("STATSD_SERVER", getenv("STATSD_SERVER_ADDRESS"));

?>

