<?php

use \Lxrco\Enums\ProviderTypes;

// Routes

$app->get( "/geolocation", function() use( $app ){

    lookup_ip( $app );

} );

$app->get( "/geolocation/{ip_address}", function() use( $app ){

    lookup_ip( $app, $ip_address );

} );

// Router function

function lookup_ip( $app, $ip_address = null ){

    $service = $app->request->get( "service" ) ? $app->request->get( "service" ) : null;

    $provider = $app->providers->getProvider( ProviderTypes::IP, $service );

    $outerResponse = $app->utils->externalRequest( $provider, ["ip_address" => $ip_address] );

    if( $outerResponse["success"] ) {

        $outerResponse = $outerResponse["data"];

        var_dump( $outerResponse );

        echo "<hr />";

        // @todo implement a template response on Provider class

        echo( $outerResponse->country_code . "<br />" );
        echo( $outerResponse->country_name . "<br />" );
        echo( $outerResponse->city . "<br />" );

    } else {

        die( $outerResponse->data );

    }

}