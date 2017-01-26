<?php

/**
 * Class Provider
 *
 * Abstract class to be inherit by all provider classes
 */

abstract class Provider {

    // Attributes

    private $tag = null;
    private $url = null;
    private $key = null;

    // Getters and Setters

    public function getUrl(){

        return $this->url;

    }

    public function setUrl( $url ){

        $this->url = $url;

    }

    public function getTag(){

        return $this->tag;

    }

    public function setTag( $tag ){

        $this->tag = $tag;

    }

    public function getKey(){

        return $this->key;

    }

    public function setKey( $key ){

        $this->key = $key;

    }

    /**
     * Function to be implemented on each subclass
     *
     * @param $data
     * @return mixed
     */

    abstract function getFormattedResponse( $data );

}