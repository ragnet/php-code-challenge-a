<?php

class Provider {

    private $tag = null;
    private $url = null;
    private $key = null;

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

}