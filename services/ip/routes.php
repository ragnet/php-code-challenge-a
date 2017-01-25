<?php

// Routes

$app->get( "/geolocation", function() use( $app ){

    lookup_ip( $app );

});
$app->get( "/geolocation/{ip_address}", function() use( $app ){

    lookup_ip( $app, $ip_address );

});


function lookup_ip( $app, $ip_address = null, $tag = null ){

    $provider = $app->providers->getIPProvider( $tag );

    var_dump( $provider->getUrl() );

    //$app->utils->externalRequest( $provider );

    //echo("find geo by IPs: $ip_address");

}