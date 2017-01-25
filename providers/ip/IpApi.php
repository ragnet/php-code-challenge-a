<?php

class IpApi extends Provider {

    function IpApi(){

        $this->setTag( "ip-api" );
        $this->setUrl( "http://ip-api.com/json" );

    }

}