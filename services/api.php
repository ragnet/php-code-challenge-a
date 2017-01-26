<?php

use \Phalcon\Http\Response;
use \Lxrco\Enums\ProviderTypes;

// Default endpoint

$app->get( "/", function(){

    sendResponse( ["error" => "No endpoint specified"] );

} );

// Geolocation service

$app->get( "/geolocation", function() use( $app ){

    $geoInfo = lookup( $app, ProviderTypes::IP, ["ip_address" => null] );

    handleGeolocation( $geoInfo );

} );

$app->get( "/geolocation/{ip_address}", function( $ip_address ) use( $app ){

    $geoInfo = lookup( $app, ProviderTypes::IP, ["ip_address" => $ip_address] );

    handleGeolocation( $geoInfo );

} );

// Weather service

$app->get( "/weather", function() use( $app ){

    // Finds first the city by IP (default)

    $geoInfo = lookup( $app, ProviderTypes::IP, ["ip_address" => null] );

    // Finds weather on that city

    handleWeatherByGeo( $app, $geoInfo );

} );

$app->get( "/weather/{ip_address}", function( $ip_address ) use( $app ){

    // Finds first the city by IP

    $geoInfo = lookup( $app, ProviderTypes::IP, ["ip_address" => $ip_address] );

    // Finds weather on that city

    handleWeatherByGeo( $app, $geoInfo );

} );


/**
 * Handles an IP (geolocation) request
 *
 * @param $geo
 */

function handleGeolocation( $geo ){

    if( $geo["geo"]["city"] ) {

        sendResponse( $geo );

    } else{

        sendResponse( ["error" => "Couldn't find IP information"] );

    }

}

/**
 * Handles a weather request
 *
 * @param $app
 * @param $city
 */

function handleWeatherByGeo( $app, $geo ){

    if( $geo["geo"]["city"] ) {

        // Extracts the city from geo response

        $city = $geo["geo"]["city"] . "," . $geo["geo"]["region"];

        // Do a lookup for weather data

        $weatherInfo = lookup( $app, ProviderTypes::Weather, ["city" => urlencode($city)] );

        // Override info on response

        $weatherInfo["ip"] = $geo["ip"];
        $weatherInfo["city"] = $city;

        sendResponse( $weatherInfo );

    } else{

        sendResponse( ["error" => "The IP provided couldn't be matched with a city"] );

    }

}

/**
 * Lookup function
 *
 * This works as a hub connected to the routes. It receives and extracts info
 * to determine what data is needed, what custom parameter is necessary and
 * also what provider will be requested
 *
 * @param $app
 * @param $type
 * @param $params
 * @return array
 */

function lookup( $app, $type, $params ){

    // Gets a provider from the URL if specified

    $service = $app->request->get( "service" ) ? $app->request->get( "service" ) : null;

    // Finds the provider for the request

    $provider = $app->providers->getProvider( $type, $service );

    if( $provider ) {

        // Do an external -curl- call based on the provider

        $outerResponse = $app->utils->externalRequest( $provider, $params );

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
 * sendResponse
 *
 * Prepares and sends a standard JSON response
 *
 * @param $data
 */

function sendResponse( $data ){

    $response = new Response();

    $response->setStatusCode( isset( $data["error"] ) ? 500 : 200 );
    $response->setHeader( "Content-Type", "application/json" );
    $response->setJsonContent( $data );

    $response->send();

}