<?php
/**
 * Created by PhpStorm.
 * User: redwe
 * Date: 05.01.2019
 * Time: 11:46
 */

class All
{
    private $conn;
    private $championInfo;
    private $itemInfo;
    private $role;

    function __construct($role = null)
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
    }

    function getChampions()
    {
        if ($this->role) $sql = 'SELECT RiotChampionId,COUNT(*) AS Total ,ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/count(*)) AS Percent
                                 FROM playergame 
                                 WHERE role = "'.$this->role.'"
                                 GROUP BY RiotChampionId 
                                 ORDER BY `percent` DESC';
        else $sql = 'SELECT RiotChampionId,COUNT(*) AS Total ,ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/count(*)) AS Percent
                     FROM playergame 
                     GROUP BY RiotChampionId 
                     ORDER BY `percent` DESC';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $champions = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $champions[$i][0] = $row['RiotChampionId'];
                $championInfo = $this->championInfo;
                $championId = $champions[$i][0];
                $championName = $championInfo -> keys -> $championId;
                $championTitle = $championInfo -> data -> $championName -> title;
                $champions[$i][1] = $championName;
                $champions[$i][2] = $row['Total'];
                $champions[$i][3] = $row['Percent']."%";
                $champions[$i][4] = $championTitle;

                $i++;
            }
            return $champions;
        }
        else {
            for($i=0;$i<10;$i++)
            {
                for($j=0;$j<4;$j++)
                {
                    $runes[$i][$j] = "NO DATA";
                }
            }
            return $champions;
        }

    }

    function getItems()
    {
        $sql = 'SELECT i.Item,COUNT(i.item) AS Total, ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(i.Item)) as Winrate
                FROM playeritems i 
                RIGHT JOIN playergame p 
                ON CONCAT(i.RiotAccountId,i.RiotGameId) = CONCAT(p.RiotAccountId,p.RiotGameId) WHERE i.item IS NOT NULL
                GROUP BY i.item  
                ORDER BY `Winrate`  DESC
                LIMIT 10';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $items = array();
        $itemInfo = $this -> itemInfo;

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $items[$i][0] = $row['Item'];
                $items[$i][1] = $row['Total'];
                $items[$i][2] = $row['Winrate']."%";
                $itemId = $items[$i][0];
                $name = $itemInfo -> data -> $itemId  -> name;
                $description = $itemInfo -> data -> $itemId -> description;
                $items[$i][3] = $name;
                $items[$i][4] = $description;
                $i++;
            }
            return $items;
        }
        else {
            for($i=0;$i<10;$i++)
            {
                for($j=0;$j<3;$j++)
                {
                    $runes[$i][$j] = "NO DATA";
                }
            }
            return $items;
        }
    }

    function getPros()
    {
        $sql = 'SELECT tab1.RiotAccountId
                      ,tab1.SummonerName
                      ,tab2.Role
                      ,tab2.Total
                      ,tab2.Winrate
                From player tab1
                LEFT JOIN (
                            SELECT g.RiotAccountId
                                  ,g.Role
                                  ,g.Total
                                  ,MAX(g.Winrate) as Winrate
                            FROM(
                                SELECT RiotAccountId
                                      ,Role
                                      ,COUNT(RiotAccountId) AS Total
                                      ,ROUND(100*SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END)/COUNT(RiotAccountId)) as Winrate
                                FROM playergame
                                GROUP BY Role,RiotAccountId
                          		HAVING Total > 5) g GROUP BY g.RiotAccountId  ) tab2
                ON tab1.RiotAccountId = tab2.RiotAccountId
                WHERE tab2.Role IS NOT NULL
                GROUP BY tab1.RiotAccountId  
                ORDER BY `tab2`.`Winrate` DESC';

        $result = ($this->conn) -> query($sql);

        $i = 0;
        $pros = array();

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                $pros[$i][0] = $row['RiotAccountId'];
                $pros[$i][1] = $row['SummonerName'];
                $pros[$i][2] = $row['Role'];
                $pros[$i][3] = $row['Total'];
                $pros[$i][4] = $row['Winrate']."%";

                $i++;
            }
            return $pros;
        }
        else {
            for($i=0;$i<10;$i++)
            {
                for($j=0;$j<3;$j++)
                {
                    $runes[$i][$j] = "NO DATA";
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