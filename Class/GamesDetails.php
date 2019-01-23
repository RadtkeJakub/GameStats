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
    private $riotAccountId;
    private $gameId;
    private $role;

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
        $this -> role = $role;
    }

    //Taking players from 10 last games
    function getMatchHistory()
    {
        if($this -> role) $sql =   'SELECT p.RiotGameId
                           FROM playergame p
                           LEFT JOIN game g
                           ON p.RiotGameId = g.RiotGameId
                           WHERE RiotAccountId = "'.$this -> riotAccountId.'"
                           ORDER BY `g`.`GameDate` DESC
                           LIMIT 0,10';
        
        else $sql = 'SELECT p.RiotGameId
                           FROM playergame p
                           LEFT JOIN game g
                           ON p.RiotGameId = g.RiotGameId
                           WHERE RiotAccountId = "'.$this -> riotAccountId.'"
                           ORDER BY `g`.`GameDate` DESC
                           LIMIT 0,10';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $matchHistory = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $matchHistory[$i][0] = $row['RiotGameId'];

                $i++;
            }
        }
        else return $matchHistory[$i] = "NO DATA";

        foreach ($matchHistory as $i => $riotGameId)
        {
            $sql = 'SELECT RiotAccountId 
                    FROM playergame 
                    WHERE RiotGameId = "'. $riotGameId[0].'"
                    ORDER BY Win DESC,
                    CASE WHEN Role = "TOP" then 1
                         WHEN Role = "JUNGLE" then 2
                         WHEN Role = "MIDDLE" then 3
                         WHEN Role = "BOTTOM" then 4
                         WHEN Role = "SUPPORT" then 5
                         ELSE 6
                    END';
            $result = ($this -> conn) -> query($sql);

            $j = 1;

            if($result -> num_rows > 0)
            {
                while($row = $result -> fetch_assoc())
                {
                    $matchHistory[$i][$j] = $row['RiotAccountId'];
                    $j++;
                }
            }
        }
        return $matchHistory;
    }

    function getTeams($riotGameId)
    {
        $sql = 'SELECT RiotChampionId,Win,RiotAccountId 
                FROM playergame 
                WHERE RiotGameId = "'.$riotGameId.'"   
                ORDER BY Win DESC,
                CASE WHEN Role = "TOP" then 1
                     WHEN Role = "JUNGLE" then 2
                     WHEN Role = "MIDDLE" then 3
                     WHEN Role = "BOTTOM" then 4
                     WHEN Role = "SUPPORT" then 5
                     ELSE 6
                END';
        $result = ($this -> conn) -> query($sql);

        $i = 0;
        $player = array();

        if($result -> num_rows > 0)
        {
            while ($row = $result -> fetch_assoc())
            {
                $player[$i][0] = $row['RiotChampionId'];
                $player[$i][1] = $row['Win'];
                $player[$i][2] = $row['RiotAccountId'];
                $i++;
            }
            return $player;
        }
        else exit('NO DATA');
    }

    function getDateAndPatch($riotGameId)
    {
        $dateAndPatch = array();

        $sql = 'SELECT GameDate,Patch FROM game WHERE RiotGameId = "'.$riotGameId.'"';

        $result = ($this -> conn) -> query($sql);
        if($result -> num_rows > 0)
        {
            $row = $result -> fetch_assoc();
            $dateAndPatch[0] = $row['GameDate'];
            $dateAndPatch[1] = $row['Patch'];

            return $dateAndPatch;
        }
        else exit("NO DATA");
    }

    function getSummoners($riotGameId,$riotAccountId)
    {
        $sql = 'SELECT Spell FROM playerSpells WHERE RiotGameId = '.$riotGameId.' AND RiotAccountId = "'.$riotAccountId.'"';

        $result = ($this -> conn) -> query($sql);

        $spells = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $spells[] = $row['Spell'];
            }
            return $spells;

        }
        else exit("NO DATA");
    }

    function getMainRunes($riotGameId,$riotAccountId)
    {
        $sql = 'SELECT MainPerk,SubPerk FROM playerrunes WHERE RiotGameId = '.$riotGameId.' AND RiotAccountId = "'.$riotAccountId.'"';

        $result = ($this -> conn) -> query($sql);

        $mainRunes = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $mainRunes[0] = $row['MainPerk'];
                $mainRunes[1] = $row['SubPerk'];
            }
            return $mainRunes;

        }
        else exit("NO DATA");
    }

    function getRunes($riotGameId,$riotAccountId)
    {
        $sql = '';
    }

    function getItems($riotGameId,$riotAccountId)
    {
        $sql = '';
    }

    function getItemsHistory($riotGameId,$riotAccountId)
    {
        $sql = '';
    }

    function getChampion($riotGameId,$riotAccountId)
    {
        $sql = 'SELECT RiotChampionId FROM playergame WHERE RiotGameId = '.$riotGameId.' AND RiotAccountId = "'.$riotAccountId.'"';

        $result = ($this -> conn) -> query($sql);

        if($result -> num_rows > 0)
        {
          $row = $result -> fetch_assoc();
          $champion = $row['RiotChampionId'];

          return $champion;
        }
        else exit("NO DATA");
    }

    function __destruct()
    {
        mysqli_close($this -> conn);
    }
}

