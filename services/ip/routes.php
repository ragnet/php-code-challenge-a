<?php

use \Phalcon\Http\Response;
use \Lxrco\Enums\ProviderTypes;

// Routes

$app->get( "/geolocation", function() use( $app ){

    lookup_ip( $app );

} );

$app->get( "/geolocation/{ip_address}", function( $ip_address ) use( $app ){

    lookup_ip( $app, $ip_address );

} );

// Router function

function lookup_ip( $app, $ip_address = null ){

    $response = new Response();
    $response->setStatusCode( 500 ); // Defaults error
    $response->setHeader( "Content-Type", "application/json" );

    $service = $app->request->get( "service" ) ? $app->request->get( "service" ) : null;

    // Finds the provider for the request

    $provider = $app->providers->getProvider( ProviderTypes::IP, $service );

    if( $provider ) {

        // Do an external -curl- call based on the provider

        $outerResponse = $app->utils->externalRequest( $provider, ["ip_address" => $ip_address] );

        if( $outerResponse["success"] ){

            $formattedData = $provider->getFormattedResponse( $outerResponse["data"] );

            // Sends a good response

            $response->setStatusCode( 200 );
            $response->setJsonContent( $formattedData );

        } else {

            $response->setJsonContent( $outerResponse->data );

        }

    } else{

        $response->setJsonContent( ["error" => "Invalid provider"] );

    }

    $response->send();

}