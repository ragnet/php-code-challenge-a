<?php

class FreeGeoIp extends Provider {

    function FreeGeoIp(){

        $this->setTag( "freegeoip" );
        $this->setUrl( "http://freegeoip.net/json/" );

    }

}