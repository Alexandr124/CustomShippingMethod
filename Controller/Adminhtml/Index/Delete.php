<?php

namespace Vaimo\NovaPoshta\Controller\Adminhtml\Index;

/**
 * Class Delete. Clear data from the DB tables before the refresh.
 * @package Vaimo\NovaPoshta\Controller\Adminhtml\Index
 */
class Delete
{

    /**
     * @var string
     */
    private static $user = "root";
    /**
     * @var string
     */
    private static $password = "KalaMaja987";
    /**
     * @var string
     */
    private static $host = "devbox.vaimo.test";
    /**
     * @var string
     */
    private static $database= "firstproject";

    /**
     * Clear table "vaimo_novaposhta_cities"
     */
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
            echo __("All rows have been deleted");
        }
        else
        {
            echo __("No rows have been deleted");
        }

    }

    /**
     *Clear table "vaimo_novaposhta_warehouses"
     */
    public static function clearWarehouseDB()
    {
        $connection= mysqli_connect(self::$host, self::$user, self::$password);
        if (!$connection)
        {
            die ('Could not connect:' . mysqli_connect_error());
        }
        mysqli_select_db($connection, self::$database);

        $truncatetable = mysqli_query($connection,"TRUNCATE TABLE vaimo_novaposhta_warehouses" );

        if($truncatetable !== FALSE)
        {
            echo __("All rows have been deleted");
        }
        else
        {
            echo __("No rows have been deleted");
        }

    }
}
