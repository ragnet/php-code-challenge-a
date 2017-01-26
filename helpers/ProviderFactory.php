<?php

/**
 * Class ProviderFactory
 *
 * Manages all providers keeping them fresh on the memory and
 * allowing a search by tag (name)
 */

class ProviderFactory{

    private $providers = [];

    /**
     * Loads all providers in memory
     *
     * @param $allProviders
     */

    function loadAll( $allProviders ){

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
                    // Ignored because invalid provider will be handled on the request

                }

            }

        }

    }

    /**
     * Gets one provider based on the tag (name) or a default one (first)
     *
     * @param $type
     * @param $tag
     * @return null
     */

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