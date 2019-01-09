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

    function __construct($championId,$role = null)
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

        $this -> championId = $championId;
        $this -> role = $role;
    }

    function getName()
    {
        $championInfo = $this->championInfo;
        $championId = $this->championId;
        $championName = $championInfo -> keys -> $championId;
        if($championName) return $championName;
        else return "NO DATA";
    }

    function getWinRatio()
    {
        if ($this->role) $sql = 'SELECT ROUND(100*(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$this->championId.' AND role ="'.$this->role.'" AND Win = 1)/(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$this->championId.' AND role = "'.$this->role.'")) AS percent';
        else $sql = 'SELECT ROUND(100*(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$this->championId.' AND Win = 1)/(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$this->championId.')) AS percent';
        $result = ($this->conn) -> query($sql);

        if($result -> num_rows > 0)
        {
            $row =  $result -> fetch_assoc();
            return $row['percent']."%";
        }
        else return "NO DATA";
    }

    function getGames()
    {
        if ($this->role)  $sql = 'SELECT COUNT(*) AS games FROM playergame WHERE RiotChampionId = '.$this->championId.' AND role = "'.$this->role.'"';
        else $sql = 'SELECT COUNT(*) AS games FROM playergame WHERE RiotChampionId = '.$this->championId;
        $result = ($this->conn) -> query($sql);

        if($result -> num_rows > 0)
        {
            $row = $result -> fetch_assoc();
            return $row['games'];
        }
        else return "NO DATA";
    }

    function getItems()
    {
        if($this->role) $sql = 'SELECT i.Item,p.RiotChampionId,COUNT(i.item) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(i.Item)) as Winrate
                                FROM playeritems i 
                                RIGHT JOIN playergame p 
                                ON CONCAT(i.RiotAccountId,i.RiotGameId) = CONCAT(p.RiotAccountId,p.RiotGameId) WHERE i.item IS NOT NULL AND p.RiotChampionId = '.$this -> championId.' AND p.role = "'.$this -> role.'"
                                GROUP BY i.item  
                                ORDER BY `Winrate`  DESC LIMIT 6';

        else $sql = 'SELECT i.Item,p.RiotChampionId,COUNT(i.item) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(i.Item)) as Winrate
                     FROM playeritems i 
                     RIGHT JOIN playergame p 
                     ON CONCAT(i.RiotAccountId,i.RiotGameId) = CONCAT(p.RiotAccountId,p.RiotGameId) WHERE i.item IS NOT NULL AND p.RiotChampionId = '.$this -> championId.'
                     GROUP BY i.item  
                     ORDER BY `Winrate`  DESC LIMIT 6';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $items = array();

        if($result -> num_rows == 6)
        {
            while($row = $result -> fetch_assoc())
            {
                $items[$i][0] = $row['Item'];
                $items[$i][1] = $row['Total'];
                $items[$i][2] = $row['Winrate']."%";
                $i++;
            }
            return $items;
        }
        else {
            $items[0][0] = "NO DATA";
            $items[0][1] = "NO DATA";
            $items[0][2] = "NO DATA";
            return $items;
        }
    }

    function getRunes()
    {
        if($this->role) $sql = 'SELECT r.*,p.RiotChampionId,COUNT(r.Perk1) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(r.Perk1)) as Winrate 
                                FROM playerrunes r RIGHT JOIN playergame p ON CONCAT(r.RiotAccountId,r.RiotGameId) = CONCAT(p.RiotAccountId,p.RiotGameId) 
                                WHERE r.Perk1 IS NOT NULL AND p.RiotChampionId = '.$this -> championId.' AND p.role = "'.$this -> role.'"
                                GROUP BY r.Perk1,r.Perk2,r.Perk3,r.Perk4,r.Perk5,r.Perk6 
                                ORDER BY `Winrate` DESC
                                LIMIT 2';

        else $sql = 'SELECT r.*,p.RiotChampionId,COUNT(r.Perk1) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(r.Perk1)) as Winrate 
                     FROM playerrunes r RIGHT JOIN playergame p ON CONCAT(r.RiotAccountId,r.RiotGameId) = CONCAT(p.RiotAccountId,p.RiotGameId) 
                     WHERE r.Perk1 IS NOT NULL AND p.RiotChampionId = '.$this -> championId.'
                     GROUP BY r.Perk1,r.Perk2,r.Perk3,r.Perk4,r.Perk5,r.Perk6 
                     ORDER BY `Winrate` DESC
                     LIMIT 2';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $runes = array();

        if($result -> num_rows == 2)
        {
            while($row = $result -> fetch_assoc())
            {
                $runes[$i][0] = $row['MainPerk'];
                $runes[$i][1] = $row['SubPerk'];
                $runes[$i][2] = $row['Perk1'];
                $runes[$i][3] = $row['Perk2'];
                $runes[$i][4] = $row['Perk3'];
                $runes[$i][5] = $row['Perk4'];
                $runes[$i][6] = $row['Perk5'];
                $runes[$i][7] = $row['Perk6'];
                $runes[$i][8] = $row['Total'];
                $runes[$i][9] = $row['Winrate']."%";
                $i++;
            }
            return $runes;
        }
        else {
            for($i=0;$i<2;$i++)
            {
                for($j=0;$j<10;$j++)
                {
                    $runes[$i][$j] = "NO DATA";
                }
            }
            return $runes;
        }
    }


    function getSummoners()
    {
        if($this->role) $sql = 'SELECT s.*,p.RiotChampionId,COUNT(s.Spell) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(s.Spell)) as Winrate 
                                FROM playerspells s 
                                RIGHT JOIN playergame p ON CONCAT(s.RiotAccountId,s.RiotGameId) = CONCAT(p.RiotAccountId,p.RiotGameId) 
                                WHERE s.Spell IS NOT NULL AND p.RiotChampionId = '.$this -> championId.' AND p.role = "'.$this -> role.'"
                                GROUP BY s.Spell
                                ORDER BY `Winrate` DESC
                                LIMIT 4';

        else $sql = 'SELECT s.*,p.RiotChampionId,COUNT(s.Spell) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(s.Spell)) as Winrate 
                     FROM playerspells s 
                     RIGHT JOIN playergame p ON CONCAT(s.RiotAccountId,s.RiotGameId) = CONCAT(p.RiotAccountId,p.RiotGameId) 
                     WHERE s.Spell IS NOT NULL AND p.RiotChampionId = '.$this -> championId.'
                     GROUP BY s.Spell
                     ORDER BY `Winrate` DESC
                     LIMIT 4';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $spells = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $spells[$i][0] = $row['Spell'];
                $spells[$i][1] = $row['Total'];
                $spells[$i][2] = $row['Winrate']."%";
                $i++;
            }
            return $spells;
        }
        else {
            for($i=0;$i<2;$i++)
            {
                for($j=0;$j<3;$j++)
                {
                    $spells[$i][$j] = "NO DATA";
                }
            }
            return $spells;
        }
    }

    function getBestTeamMatchups()
    {
        if($this->role) $sql = 'SELECT RiotChampionId,COUNT(RiotChampionId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotChampionId)) as Winrate
                                FROM playergame t1
                                RIGHT JOIN (SELECT RiotGameId,TEAM FROM playergame WHERE RiotChampionId = '.$this -> championId.' AND role = "'.$this -> role.'") t2
                                ON t1.RiotGameId = t2.RiotGameId AND t1.Team = t2.Team
                                WHERE RiotChampionId != '.$this -> championId.'
                                GROUP BY RiotChampionId  
                                ORDER BY `Winrate`  DESC
                                LIMIT 8';

        else $sql = 'SELECT RiotChampionId,COUNT(RiotChampionId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotChampionId)) as Winrate
                     FROM playergame t1
                     RIGHT JOIN (SELECT RiotGameId,TEAM FROM playergame WHERE RiotChampionId = '.$this -> championId.') t2
                     ON t1.RiotGameId = t2.RiotGameId AND t1.Team = t2.Team
                     WHERE RiotChampionId != '.$this -> championId.'
                     GROUP BY RiotChampionId  
                     ORDER BY `Winrate`  DESC
                     LIMIT 8';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $teamMatchup = array();

        if($result -> num_rows == 8)
        {
            while($row = $result -> fetch_assoc())
            {
                $teamMatchup[$i][0] = $row['RiotChampionId'];
                $teamMatchup[$i][1] = $row['Total'];
                $teamMatchup[$i][2] = $row['Winrate']."%";
                $i++;
            }
            return $teamMatchup;
        }
        else {
            for($i=0;$i<8;$i++)
            {
                for($j=0;$j<3;$j++)
                {
                    $teamMatchup[$i][$j] = "NO DATA";
                }
            }
            return $teamMatchup;
        }
    }

    function getWorstTeamMatchups()
    {
        if($this->role) $sql = 'SELECT RiotChampionId,COUNT(RiotChampionId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotChampionId)) as Winrate
                                FROM playergame t1
                                RIGHT JOIN (SELECT RiotGameId,TEAM FROM playergame WHERE RiotChampionId = '.$this -> championId.' AND role = "'.$this -> role.'") t2
                                ON t1.RiotGameId = t2.RiotGameId AND t1.Team = t2.Team
                                WHERE RiotChampionId != '.$this -> championId.'
                                GROUP BY RiotChampionId  
                                ORDER BY `Winrate`  ASC
                                LIMIT 8';

        else $sql = 'SELECT RiotChampionId,COUNT(RiotChampionId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotChampionId)) as Winrate
                     FROM playergame t1
                     RIGHT JOIN (SELECT RiotGameId,TEAM FROM playergame WHERE RiotChampionId = '.$this -> championId.') t2
                     ON t1.RiotGameId = t2.RiotGameId AND t1.Team = t2.Team
                     WHERE RiotChampionId != '.$this -> championId.'
                     GROUP BY RiotChampionId  
                     ORDER BY `Winrate`  ASC
                     LIMIT 8';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $teamMatchup = array();

        if($result -> num_rows == 8)
        {
            while($row = $result -> fetch_assoc())
            {
                $teamMatchup[$i][0] = $row['RiotChampionId'];
                $teamMatchup[$i][1] = $row['Total'];
                $teamMatchup[$i][2] = $row['Winrate']."%";
                $i++;
            }
            return $teamMatchup;
        }
        else {
            for($i=0;$i<8;$i++)
            {
                for($j=0;$j<3;$j++)
                {
                    $teamMatchup[$i][$j] = "NO DATA";
                }
            }
            return $teamMatchup;
        }
    }

    function getBestEnemies()
    {
        if($this->role) $sql = 'SELECT RiotChampionId,COUNT(RiotChampionId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotChampionId)) as Winrate
                                FROM playergame t1
                                RIGHT JOIN (SELECT RiotGameId,TEAM FROM playergame WHERE RiotChampionId = '.$this -> championId.' AND role = "'.$this -> role.'") t2
                                ON t1.RiotGameId = t2.RiotGameId AND t1.Team != t2.Team
                                GROUP BY RiotChampionId  
								ORDER BY `Winrate` ASC
                                LIMIT 8';

        else $sql = 'SELECT RiotChampionId,COUNT(RiotChampionId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotChampionId)) as Winrate
                     FROM playergame t1
                     RIGHT JOIN (SELECT RiotGameId,TEAM FROM playergame WHERE RiotChampionId = '.$this -> championId.') t2
                     ON t1.RiotGameId = t2.RiotGameId AND t1.Team != t2.Team
                     GROUP BY RiotChampionId  
                     ORDER BY `Winrate` ASC
                     LIMIT 8';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $enemyMatchup = array();

        if($result -> num_rows == 8)
        {
            while($row = $result -> fetch_assoc())
            {
                $enemyMatchup[$i][0] = $row['RiotChampionId'];
                $enemyMatchup[$i][1] = $row['Total'];
                $enemyMatchup[$i][2] = $row['Winrate']."%";
                $i++;
            }
            return $enemyMatchup;
        }
        else {
            for($i=0;$i<8;$i++)
            {
                for($j=0;$j<3;$j++)
                {
                    $enemyMatchup[$i][$j] = "NO DATA";
                }
            }
            return $enemyMatchup;
        }
    }

    function getWorstEnemies()
    {
        if($this->role) $sql = 'SELECT RiotChampionId,COUNT(RiotChampionId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotChampionId)) as Winrate
                                FROM playergame t1
                                RIGHT JOIN (SELECT RiotGameId,TEAM FROM playergame WHERE RiotChampionId = '.$this -> championId.' AND role = "'.$this -> role.'") t2
                                ON t1.RiotGameId = t2.RiotGameId AND t1.Team != t2.Team
                                GROUP BY RiotChampionId  
								ORDER BY `Winrate` DESC
                                LIMIT 8';

        else $sql = 'SELECT RiotChampionId,COUNT(RiotChampionId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotChampionId)) as Winrate
                     FROM playergame t1
                     RIGHT JOIN (SELECT RiotGameId,TEAM FROM playergame WHERE RiotChampionId = '.$this -> championId.') t2
                     ON t1.RiotGameId = t2.RiotGameId AND t1.Team != t2.Team
                     GROUP BY RiotChampionId  
                     ORDER BY `Winrate` DESC
                     LIMIT 8';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $enemyMatchup = array();

        if($result -> num_rows == 8)
        {
            while($row = $result -> fetch_assoc())
            {
                $enemyMatchup[$i][0] = $row['RiotChampionId'];
                $enemyMatchup[$i][1] = $row['Total'];
                $enemyMatchup[$i][2] = $row['Winrate']."%";
                $i++;
            }
            return $enemyMatchup;
        }
        else {
            for($i=0;$i<8;$i++)
            {
                for($j=0;$j<3;$j++)
                {
                    $enemyMatchup[$i][$j] = "NO DATA";
                }
            }
            return $enemyMatchup;
        }
    }

    function getPros()
    {
        if($this->role) $sql = 'SELECT t1.SummonerName,COUNT(t1.RiotAccountId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(t1.RiotAccountId)) as Winrate
                                FROM player t1
                                RIGHT JOIN (SELECT RiotAccountId,Win
                                            FROM playergame 
                                            WHERE RiotChampionId = '.$this -> championId.' AND role = "'.$this -> role.'") t2
                                ON t1.RiotAccountId = t2.RiotAccountId
                                GROUP BY t1.SummonerName  
                                ORDER BY `Winrate` DESC
                                LIMIT 3';

        else $sql = 'SELECT t1.SummonerName,COUNT(t1.RiotAccountId) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(t1.RiotAccountId)) as Winrate
                     FROM player t1
                     RIGHT JOIN (SELECT RiotAccountId,Win
                                 FROM playergame 
                                 WHERE RiotChampionId = '.$this -> championId.') t2
                     ON t1.RiotAccountId = t2.RiotAccountId
                     GROUP BY t1.SummonerName  
                     ORDER BY `Winrate` DESC
                     LIMIT 3';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $pros = array();

        if($result -> num_rows == 3)
        {
            while($row = $result -> fetch_assoc())
            {
                $pros[$i][0] = $row['SummonerName'];
                $pros[$i][1] = $row['Total'];
                $pros[$i][2] = $row['Winrate']."%";
                $i++;
            }
            return $pros;
        }
        else {
            for($i=0;$i<3;$i++)
            {
                for($j=0;$j<3;$j++)
                {
                    $pros[$i][$j] = "NO DATA";
                }
            }
            return $pros;
        }
    }

    function __destruct()
    {
        mysqli_close($this -> conn);
    }
}