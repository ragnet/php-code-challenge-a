<?php

/**
 * Class FreeGeoIp
 *
 * Gives support to freegeoip.net IP API
 */

class FreeGeoIp extends Provider {

    function FreeGeoIp(){

        $this->setTag( "freegeoip" );
        $this->setUrl( "http://freegeoip.net/json/{ip_address}" );

    }

    function getFormattedResponse( $data ){

        $response = [
            "ip" => $data->ip,
            "geo" => [
                "service" => $this->getTag(),
                "country" => $data->country_name,
                "city" => $data->city,
                "region" => $data->region_code
            ]
        ];

        return $response;

    }

}