<?php

class DB_class {
    function konek() {
        static $konek;
        $konek = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if (mysqli_connect_errno()){
            die('Oops connection error -> ' . mysqli_connect_error());
        }
        return $konek;
        
    }
}