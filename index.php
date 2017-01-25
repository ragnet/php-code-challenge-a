<?php
require( "enums/enums.php" );

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Lxrco\Enums\ProviderTypes;

$di = new FactoryDefault();

$di->set( "utils", function(){

    return new Utils();

} );

$di->set( "providers", function(){

    $availableProviders[ProviderTypes::IP] = ["IpApi", "FreeGeoIp"];
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

    function getProvider( $type, $tag ){

        if( !empty( $tag ) ){

            // Find by tag

            foreach( $this->providers[$type] as $provider ){

                if( $tag == $provider->getTag() ) return $provider;

            }

        } else {

            // Returns default provider

            return $this->providers[$type][0] ? $this->providers[$type][0] : null;

        }

        return null;

    }

}

class Utils{

    /**
     * Makes a curl GET call to an external service
     *
     * @param $provider
     * @param array $params
     * @throws Exception
     */

    function externalRequest( $provider, $params = [] ){

        if( $provider ) {

            try{

                // Replace params on the URL

                $endpoint = $provider->getUrl();

                foreach( $params as $key => $value ){

                    $endpoint = str_replace( "{" . $key . "}", $value, $endpoint );

                }

                // Clear empty params

                $endpoint = str_replace( "{}", "", $endpoint );

                // Do a CURL call

                $ch = curl_init();

                curl_setopt( $ch, CURLOPT_URL,  $endpoint );
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET" );
                curl_setopt( $ch, CURLOPT_PORT, 80 );

                curl_setopt( $ch, CURLOPT_NOBODY, false );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, false );

                $ret = curl_exec( $ch );

                curl_close( $ch );

                return [ "success" => true, "data" => json_decode( $ret ) ];

            } catch( Exception $e ){

                return [ "success" => false, "data" => $e->getMessage() ];

            }

        } else{

            return [ "success" => false, "data" => "Invalid provider" ];

        }

    }

}