<?php

use \Phalcon\Http\Response;
use \Lxrco\Enums\ProviderTypes;

/**
 *
 * Geolocation service
 *
 */

$app->get( "/geolocation", function() use( $app ){

    $geoInfo = lookup_ip( $app );

    sendResponse( $geoInfo );

} );

$app->get( "/geolocation/{ip_address}", function( $ip_address ) use( $app ){

    $geoInfo = lookup_ip( $app, $ip_address );

    sendResponse( $geoInfo );

} );

function lookup_ip( $app, $ip_address = null ){

    $service = $app->request->get( "service" ) ? $app->request->get( "service" ) : null;

    // Finds the provider for the request

    $provider = $app->providers->getProvider( ProviderTypes::IP, $service );

    if( $provider ) {

        // Do an external -curl- call based on the provider

        $outerResponse = $app->utils->externalRequest( $provider, ["ip_address" => $ip_address] );

        if( $outerResponse["success"] ){

            $data = $provider->getFormattedResponse( $outerResponse["data"] );

        } else {

            $data = ["error" => $outerResponse->data];

        }

    } else{

        $data = ["error" => "Invalid provider"];

    }

    return $data;

}

/**
 *
 * Weather service
 *
 */

$app->get( "/weather", function() use( $app ){

    // Finds first the city by IP (default)

    $geoInfo = lookup_ip( $app );

    $city = $geoInfo["geo"]["city"] . "," . $geoInfo["geo"]["region"];

    // Finds weather info on that city

    $weatherInfo = lookup_weather( $app, urlencode( $city ) );

    $weatherInfo["ip"] = $geoInfo["ip"];
    $weatherInfo["city"] = $city;

    sendResponse( $weatherInfo );

} );

$app->get( "/weather/{ip_address}", function( $ip_address ) use( $app ){

    // Finds first the city by IP

    $geoInfo = lookup_ip( $app, $ip_address );

    $city = $geoInfo["geo"]["city"] . "," . $geoInfo["geo"]["region"];

    // Finds weather info on that city

    $weatherInfo = lookup_weather( $app, urlencode( $city ) );

    $weatherInfo["ip"] = $geoInfo["ip"];
    $weatherInfo["city"] = $city;

    sendResponse( $weatherInfo );

} );

// Router function

function lookup_weather( $app, $city ){

    $service = $app->request->get( "service" ) ? $app->request->get( "service" ) : null;

    // Finds the provider for the request

    $provider = $app->providers->getProvider( ProviderTypes::Weather, $service );

    if( $provider ) {

        // Do an external -curl- call based on the provider

        $outerResponse = $app->utils->externalRequest( $provider, ["city" => $city] );

        if( $outerResponse["success"] ){

            $data = $provider->getFormattedResponse( $outerResponse["data"] );

        } else {

            $data = ["error" => $outerResponse->data];

        }

    } else{

        $data = ["error" => "Invalid provider"];

    }

    return $data;

}


/**
 *
 * Auxiliar functions
 *
 */

function sendResponse( $data ){

    $response = new Response();

    $response->setStatusCode( 200 );
    $response->setHeader( "Content-Type", "application/json" );
    $response->setJsonContent( $data );

    $response->send();

}