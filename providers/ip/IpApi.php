<?php

/**
 * Class IpApi
 *
 * Gives support to ip-api.com IP API
 */

class IpApi extends Provider {

    function IpApi(){

        $this->setTag( "ip-api" );
        $this->setUrl( "http://ip-api.com/json/{ip_address}" );

    }

    function getFormattedResponse( $data ){

        $response = [
            "ip" => $data->query,
            "geo" => [
                "service" => $this->getTag(),
                "country" => $data->country,
                "city" => $data->city,
                "region" => $data->region
            ]
        ];

        return $response;

    }

}