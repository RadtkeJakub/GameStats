<?php
/**
 * Created by PhpStorm.
 * User: redwe
 * Date: 11.01.2019
 * Time: 16:04
 */

class Pro
{
    private $conn;
    private $playerId;
    private $championInfo;
    private $itemInfo;
    private $role;

    function __construct($name,$role = null)
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

        $getJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
        $this -> championInfo = json_decode($getJson);

        $getItemJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/item.json");
        $this -> itemInfo = json_decode($getItemJson);

        $this -> role = $role;

        $sql = 'SELECT RiotAccountId FROM player WHERE Summonername = "'.$name.'"';
        $result = ($this -> conn) -> query($sql);

        $this -> playerId = $result['RiotAccountId'];

    }

    function getWinRate()
    {
        if ($this -> role) $sql = '';
        else $sql = '';
    }

    function getRoles()
    {
        if ($this -> role) $sql = '';
        else $sql = '';
    }

    function getMatchHistory()
    {
        if ($this -> role) $sql = '';
        else $sql = '';
    }

    function getMostPlayedChampions()
    {
        if ($this -> role) $sql = '';
        else $sql = '';
    }

    function __destruct()
    {
        mysqli_close($this -> conn);
    }
}