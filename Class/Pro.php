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
    private $name;
    private $championInfo;
    private $itemInfo;
    private $role;
    private $riotAccountId;

    function __construct($riotAccountId,$role = null)
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

        $this -> riotAccountId = $riotAccountId;

        $getJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
        $this -> championInfo = json_decode($getJson);

        $getItemJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/item.json");
        $this -> itemInfo = json_decode($getItemJson);

        $this -> role = $role;

        $sql = 'SELECT SummonerName FROM player WHERE RiotAccountId = "'.$riotAccountId.'"';
        $result = ($this -> conn) -> query($sql);
        $row = $result -> fetch_assoc();

        $this -> name = $row['SummonerName'];

    }

    function getName()
    {
        return $this -> name;
    }

    function getWinRate()
    {
        if ($this -> role) $sql = 'SELECT count(*) AS Total,ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(*)) AS WinRate FROM playergame WHERE RiotAccountId = "'.$this -> riotAccountId.'" AND role = "'.$this -> role.'"';
        else $sql = 'SELECT count(*),ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(*)) AS WinRate FROM playergame WHERE RiotAccountId = "'.$this -> riotAccountId.'"';

        $result = ($this->conn) -> query($sql);
        $winRate = array();

        if ($result -> num_rows > 0)
        {
            $row = $result -> fetch_assoc();

            $winRate[0] = $row['Total'];
            $winRate[1] = $row['WinRate'];

            return $winRate;
        }
        else
        {
            $winRate[0] = "NO DATA";
            return $winRate;
        }
    }

    function getRoles()
    {
        $sql = 'SELECT COUNT(*) AS Total,role FROM playergame WHERE RiotAccountId = "'.$this -> riotAccountId.'" GROUP BY role';

        $result = ($this->conn) -> query($sql);

        $roles = array();
        $i = 0;

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $roles[$i] = $row['role'];
                $i++;
            }
            return $roles;
        }
        else {
            $roles[0] = "NO DATA";
            return $roles;
        }
    }

    function getMatchHistory()
    {
        if ($this -> role) $sql = '';
        else $sql = '';
    }

    function getMostPlayedChampions()
    {
        if ($this -> role) $sql =  'SELECT RiotAccountId
                                    ,RiotChampionId
                                    ,COUNT(*) AS Total
                                    ,ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(*)) AS WinRate
                                    FROM playergame 
                                    WHERE RiotAccountId = "'.$this->riotAccountId.'" AND Role = "'.$this->role.'" 
                                    GROUP BY RiotAccountId,RiotChampionId  
                                    ORDER BY `WinRate`  DESC';
        else $sql = 'SELECT RiotAccountId
                    ,RiotChampionId
                    ,COUNT(*) AS Total
                    ,ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(*)) AS WinRate
                    FROM playergame 
                    WHERE RiotAccountId = "'.$this->riotAccountId.'"
                    GROUP BY RiotAccountId,RiotChampionId  
                    ORDER BY `WinRate`  DESC';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $champions = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $champions[$i][0] = $row['RiotChampionId'];
                $champions[$i][1] = $row['Total'];
                $champions[$i][2] = $row['WinRate']."%";
                $i++;
            }
            return $champions;
        }
        else {
            $champions[0][0] = "NO DATA";
            $champions[0][1] = "NO DATA";
            $champions[0][2] = "NO DATA";
            return $champions;
        }
    }

    function getGames()
    {
        if ($this -> role) $sql = 'SELECT RiotGameId FROM playergame WHERE RiotAccountId = "'.$this -> riotAccountId.'"';
        else $sql = 'SELECT RiotGameId FROM playergame WHERE RiotAccountId = "'.$this -> riotAccountId.'" AND role = "'.$this -> role.'"';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $games = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $games[$i] = $row['RiotGameId'];
                $i++;
            }
            return $games;
        }
        else {
            $games[0] = "NO DATA";
            return $games;
        }
    }

    function __destruct()
    {
        mysqli_close($this -> conn);
    }
}