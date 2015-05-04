<?php

/**
 * Class to handle the db connection
 *
 * @author Thomas Blank
 */
class DbConnect {

    private $conn;

    function __construct()
    {
    }

    /**
     * Establishes the connection to the configured database
     *
     * @return pdo
     */
    function connect()
    {
        // -----------------------------------------------------------------------------
        // Connect to mysql database
        // -----------------------------------------------------------------------------
        //$this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        $this->conn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));


        // -----------------------------------------------------------------------------
        // Return the database connection handler
        // -----------------------------------------------------------------------------
        return $this->conn;
    }

}