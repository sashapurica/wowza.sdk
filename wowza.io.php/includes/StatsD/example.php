<?php


require('statsd.php');

$statsd = new StatsD();

$statsd->set("Int0.DevOps.tickets",5);


$statsd->increment("Int0.DevOps.tickets");
$statsd->timing("Int0.DevOps.tickets",455);


$statsd->gauge("Int0.DevOps.issues",777);


