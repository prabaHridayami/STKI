<?php

    function konek(){
        $user = "root";
        $pass = "";
        $db = "peringkasan";
        $host = "localhost";
        $konek = mysqli_connect($host, $user, $pass,$db);

        return $konek;
    }

    function truncate(){
        $sql = "CALL turncate()";

        if(konek()->query($sql)===true){
            $record =  true;
        } else{
            $record = mysqli_error(konek());
        }
        return $record;
    }

?>