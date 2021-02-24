<?php

class tests implements JsonSerializable {

    private $id;
    private $userID;
    private $startTime;
    private $endTime;
    private $testTime;
    private $score;
    private $url;

    function __get($name) {
        return $this->$name;
    }

    function __set($name, $value) {
        $this->$name = $value;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
