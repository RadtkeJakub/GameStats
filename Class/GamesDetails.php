<?php
/**
 * Created by PhpStorm.
 * User: redwe
 * Date: 20.01.2019
 * Time: 21:14
 */

class GamesDetails
{
    private $conn;
    private $championId;
    private $gameId;
    private $role;

    function __construct($championId,$gameId,$role = null)
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "inzynierka";

        //CREATE CONNECTION
        $this -> conn = new mysqli($servername,$username,$password,$dbname);

        //CHECK CONNECTION
        if(($this->conn)->connect_error) {
            die("Connection failed: ". ($this->conn)->connect_error);
        }

        $this -> championId = $championId;
        $this -> gameId = $gameId;
        $this -> role = $role;
    }

    function getMatchHistory()
    {

    }

    function __destruct()
    {
        mysqli_close($this -> conn);
    }
}

