<?php
// Loads libs and files

require( "enums/enums.php" );
require( "providers/Provider.php" );
require( "helpers/ProviderFactory.php" );
require( "helpers/Utils.php" );

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Lxrco\Enums\ProviderTypes;

// Injects dependencies on main object

$di = new FactoryDefault();

$di->set( "utils", function(){

    return new Utils();

} );

$di->set( "providers", function(){

    // List all providers to be available

    $availableProviders[ProviderTypes::IP] = ["IpApi", "FreeGeoIp"];
    $availableProviders[ProviderTypes::Weather] = ["OpenWeatherMap"];

    $providerFactory = new ProviderFactory();
    $providerFactory->loadAll( $availableProviders );

    return $providerFactory;

} );

// Creates the main container with dependencies

$app = new Micro();

$app->setDI( $di );

// Add routes and router functions

include( "services/api.php" );

$app->handle();