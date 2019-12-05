<?php

namespace Vaimo\NovaPoshta\Controller\Adminhtml\Index;

class Delete
{

    private static $user = "root";
    private static $password = "KalaMaja987";
    private static $host = "devbox.vaimo.test";
    private static $database= "firstproject";

    public static function clearDB()
    {
        $connection= mysqli_connect(self::$host, self::$user, self::$password);
        if (!$connection)
        {
            die ('Could not connect:' . mysqli_connect_error());
        }
        mysqli_select_db($connection, self::$database);

        $truncatetable = mysqli_query($connection,"TRUNCATE TABLE vaimo_novaposhta_cities" );

        if($truncatetable !== FALSE)
        {
            echo("All rows have been deleted.");
        }
        else
        {
            echo("No rows have been deleted.");
        }

    }
}
