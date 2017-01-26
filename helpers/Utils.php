<?php

/**
 * Class Utils
 *
 * Utility functions to be used all over the application
 */

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