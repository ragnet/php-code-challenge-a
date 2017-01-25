<?php
require( "./enums/enums.php" );

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Lxrco\Enums\ProviderTypes;

$di = new FactoryDefault();

$di->set( "utils", function(){

    return new Utils();

} );

$di->set( "providers", function(){

    $availableProviders[ProviderTypes::IP] = ["IpApi"];

    //$availableProviders[ProviderTypes::IP] = ["IpApi", "FreeGeoIp"];
    //$availableProviders[ProviderTypes::Weather] = ["OpenWeatherMap"];

    $providerFactory = new ProviderFactory();
    $providerFactory->loadAll( $availableProviders );

    return $providerFactory;

} );

$app = new Micro();

$app->setDI($di);

include( "services/ip/routes.php" );
include( "services/weather/routes.php" );

$app->handle();


// Classes

class ProviderFactory{

    private $providers = [];

    function loadAll( $allProviders ){

        // Loads provider superclass

        require_once "providers/Provider.php";

        // Loads all providers available

        foreach( $allProviders as $type => $providers ){

            $this->providers[$type] = [];

            foreach( $providers as $provider ) {

                $classFile = "providers/" . $type . "/" . $provider . ".php";

                if( file_exists( $classFile ) ){

                    require_once $classFile;

                    $this->providers[$type][] = new $provider();

                } else{

                    // Unable load provider: $provider

                }

            }

        }

    }

    function getIPProvider( $tag ){

        // @todo Find provider by tag or default

        return $this->providers[ProviderTypes::IP][0] ?
               $this->providers[ProviderTypes::IP][0] : null;

    }

    function getWeatherProvider( $tag ){

        // @todo Find provider by tag or default

        return $this->providers[ProviderTypes::Weather][0] ?
               $this->providers[ProviderTypes::Weather][0] : null;

    }

}

class Utils{

    function externalRequest( $provider, $params = [] ){

        if( $provider ) {

            echo("externalRequest to " . $provider->getUrl());

        } else{

            // Invalid provider

        }

    }

}