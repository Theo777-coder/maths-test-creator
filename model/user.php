<?php

class user {

    private $id;
    private $username;
    private $password;
    private $score;
    private $RegisterDate;

    function __get($name) {
        return $this->$name;
    }

    function __set($name, $value) {
        $this->$name = $value;
    }

}