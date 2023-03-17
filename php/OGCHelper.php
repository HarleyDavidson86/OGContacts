<?php

class OGCHelper {

    // Removes all non numeric and alphanumeric characters
    // Sets the complete string to lowercase
    public static function toId($string) {
        $res = preg_replace("/[^a-zA-Z0-9]/", "", $string);
        return strtolower($res);
    }
}
