<?php

$app->get( "/weather", "lookup_weather" );
$app->get( "/weather/{ip_address}", "lookup_weather" );


function lookup_weather( $ip_address = null ){

    //die("find weather by IPs: $ip_address");

}