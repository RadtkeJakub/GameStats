<?php
/**
 * Created by PhpStorm.
 * User: redwe
 * Date: 03.01.2019
 * Time: 13:35
 */

class Champion
{
    private $conn;
    private $championId;
    private $role;
    private $championInfo;
    private $itemInfo;

    function __construct()
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
    }

    function setId($championId,$role = null)
    {
        $this -> championId = $championId;
        $this -> role = $role;
    }

    function getName()
    {
        $championInfo = $this->championInfo;
        $championId = $this->championId;
        $championName = $championInfo -> keys -> $championId;
        echo $championName;
    }

    function getWinRatio()
    {
        if ($this->role) $sql = 'SELECT ROUND(100*(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$this->championId.'AND role ='.$this->role.' AND Win = 1)/(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$this->championId.' AND role = '.$this->role.')) AS percent';
        else $sql = 'SELECT ROUND(100*(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$this->championId.' AND Win = 1)/(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$this->championId.')) AS percent';
        $result = ($this->conn) -> query($sql);

        if($result -> num_rows > 0)
        {
            $row =  $result -> fetch_assoc();
            echo $row['percent']."%";
        }
        else echo "NO DATA";
    }

    function getGames()
    {
        if ($this->role)  $sql = 'SELECT COUNT(*) AS games FROM playergame WHERE RiotChampionId = '.$this->championId.' AND role = '.$this->role;
        else $sql = 'SELECT COUNT(*) AS games FROM playergame WHERE RiotChampionId = '.$this->championId;
        $result = ($this->conn) -> query($sql);

        if($result -> num_rows > 0)
        {
            $row = $result -> fetch_assoc();
            echo $row['games'];
        }
        else echo "NO DATA";
    }

    function getItems()
    {
        if($this->role) $sql = '';
        else $sql = '';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $items = array();

        if($result -> num_rows > 0)
        {
            while($result -> fetch_assoc())
            {
                //TODO: END ITEMS SQL
                $items[$i] = '';
                $i++;
            }

        }
        else echo "NO DATA";
    }

    function getRunes()
    {
        //TODO: END getRunes
    }

    function getSummoners()
    {
        //TODO: END getSummoners
    }

    function getBestTeamMatchups()
    {
        //TODO: GetBestWinRationInTeam
    }

    function getWorstTeamMatchups()
    {
        //TODO: Get WORST MATCHUP IN TEAM
    }


    function __destruct()
    {
        mysqli_close($this -> conn);
    }
}