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
                           WHERE RiotAccountId = "'.$this -> riotAccountId.'" AND Role = "'.$this->role.'"
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
        $sql = 'SELECT RiotChampionId,Win,RiotAccountId,Kills,Deaths,Assists
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
                $player[$i][3] = $row['Kills'];
                $player[$i][4] = $row['Deaths'];
                $player[$i][5] = $row['Assists'];
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
        else
        {
            $mainRunes[0] = 'NO DATA';
            $mainRunes[1] = 'NO DATA';
            return $mainRunes;
        }
    }

    function getRunes($riotGameId,$riotAccountId)
    {
        $sql = 'SELECT Perk1,Perk2,Perk3,Perk4,Perk5,Perk6 FROM playerrunes WHERE RiotGameId = '.$riotGameId.' AND RiotAccountId = "'.$riotAccountId.'"';

        $result = ($this -> conn) -> query($sql);
        $runes = array();

        if ($result -> num_rows > 0)
        {
            $row = $result -> fetch_assoc();
            $runes[0] = $row['Perk1'];
            $runes[1] = $row['Perk2'];
            $runes[2] = $row['Perk3'];
            $runes[3] = $row['Perk4'];
            $runes[4] = $row['Perk5'];
            $runes[5] = $row['Perk6'];
            return $runes;
        }
        else exit ('NO DATA');
    }

    function getItems($riotGameId,$riotAccountId)
    {
        $sql = 'SELECT Item FROM playeritems WHERE RiotGameId = '.$riotGameId.' AND RiotAccountId = "'.$riotAccountId.'" ORDER BY Position';
        $result = ($this -> conn) -> query($sql);

        $items = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $items[] = $row['Item'];
            }
            return $items;
        }
        else return $items[] = "NO DATA";
    }

    function getItemsHistory($riotGameId,$riotAccountId)
    {
        $sql = 'SELECT RiotItemId,Type,Seconds FROM playeritemshistory WHERE RiotGameId = '.$riotGameId.' AND RiotAccountId = "'.$riotAccountId.'" ORDER BY Seconds';

        $result = ($this ->conn) -> query($sql);

        $i=0;
        $itemsHistory = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $itemsHistory[$i][0] = $row['RiotItemId'];
                $itemsHistory[$i][1] = $row['Type'];
                $itemsHistory[$i][2] = $row['Seconds'];
                $i++;
            }
            return $itemsHistory;
        }
        else exit("NO DATA");
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

    function getName($riotAccountId)
    {
        $sql = 'SELECT SummonerName FROM player WHERE RiotAccountId = "'.$riotAccountId.'"';

        $result = ($this -> conn) -> query($sql);

        if($result -> num_rows > 0)
        {
            $row = $result -> fetch_assoc();
            $summonerName = $row['SummonerName'];

            return $summonerName;
        }
        else exit("NO DATA");
    }

    function getSkillOrder($riotGameId,$riotAccountId)
    {
        $sql = 'SELECT Skillslot,AddTime FROM playerpoints WHERE RiotGameId = '.$riotGameId.' AND RiotAccountId = "'.$riotAccountId.'" ORDER BY AddTime';

        $result = ($this -> conn) -> query($sql);

        $i = 0;
        $skillsPoint = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $skillsPoint[$i][0] = $row['Skillslot'];
                $skillsPoint[$i][1] = $row['AddTime'];
                $i++;
            }
            return $skillsPoint;
        }
        else exit("NO DATA");
    }

    function __destruct()
    {
        mysqli_close($this -> conn);
    }
}

