<?php

class OpenWeatherMap extends Provider {

    function OpenWeatherMap(){

        $this->setTag( "openweathermap" );
        $this->setKey( "6103b0f582e78c7382bc6b0cdc06deb8" );
        $this->setUrl( "http://api.openweathermap.org/data/2.5/weather?q={city}&units=metric&appid=" . $this->getKey() );

    }

    function getFormattedResponse( $data ){

        $response = [
            "ip" => "----",
            "city" => "----",
            "temperature" => [
                "current" => $data->main->temp,
                "low" => $data->main->temp_min,
                "high" => $data->main->temp_max
            ],
            "wind" => [
                "speed" => $data->wind->speed,
                "direction" => $data->wind->deg // @fixme Is this right wind direction property?
            ]
        ];

        return $response;

    }

}