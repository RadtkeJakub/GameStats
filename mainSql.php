<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "inzynierka";

    //CREATE CONNECTION
    $conn = new mysqli($servername,$username,$password,$dbname);

    //CHECK CONNECTION
    if($conn->connect_error) {
        die("Connection failed: ". $conn->connect_error);
    }
    $getJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
    $championRiotInfo = json_decode($getJson);
    $championName = $championRiotInfo -> keys;

    $getChampions = 'SELECT DISTINCT RiotChampionId FROM playergame';
    $championsResult = $conn -> query($getChampions);
    $champions = array();
    $i = 0;
    if ($championsResult -> num_rows > 0) {
        while ($row = $championsResult->fetch_assoc()) {
            $getChampionWinRatio = 'SELECT 100*(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$row["RiotChampionId"].' AND Win = 1)/(SELECT COUNT(RiotChampionId) FROM playergame WHERE RiotChampionId = '.$row["RiotChampionId"].') AS percent';
            $winRatioResult = $conn -> query($getChampionWinRatio);
            $winRatio = $winRatioResult -> fetch_assoc();

            $championId = $row["RiotChampionId"];

            $champions[$i][0] = $row["RiotChampionId"];
            $champions[$i][1] = $championName -> $championId;
            $champions[$i][2] = round($winRatio["percent"]);
            $i++;
        }
    }
    usort($champions,function($a,$b) {
        return $b[2] - $a[2];
    });

    $getChampionInfo = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
    $objChampionInfo = json_decode($getChampionInfo);

    function getChampionInfo($champion) {
        global $objChampionInfo;
        $championInfo = $objChampionInfo -> data -> $champion;

        return $championInfo;
    }

    //SELECT ITEM ID

    function championItems($championId) {
        $itemsWinRatioSql = "SELECT i.Item, COUNT(*) as Repeats,
                          SUM(CASE WHEN Win = 1 THEN 1 ELSE 0 END) as Wins
                          FROM (SELECT Item0 AS Item, Win FROM playergame
                              UNION ALL
                              SELECT Item1 AS Item, Win FROM playergame WHERE RiotChampionId = ".$championId."
                              UNION ALL
                              SELECT Item2 AS Item, Win FROM playergame WHERE RiotChampionId = ".$championId."
                              UNION ALL
                              SELECT Item3 AS Item, Win FROM playergame WHERE RiotChampionId = ".$championId."
                              UNION ALL
                              SELECT Item4 AS Item, Win FROM playergame WHERE RiotChampionId = ".$championId."
                              UNION ALL
                              SELECT Item5 AS Item, Win FROM playergame WHERE RiotChampionId = ".$championId."
                              UNION ALL
                              SELECT Item6 AS Item, Win FROM playergame WHERE RiotChampionId = ".$championId."
                             ) i 
                          GROUP BY i.Item  
                          ORDER BY i.Item";
        global $conn;
        $itemWinRatioQuery = $conn -> query($itemsWinRatioSql);

        $i = 0;
        $items = array();
        while ($row = $itemWinRatioQuery->fetch_assoc()) {
            if ($row['Item'] == 0 || $row['Repeats'] < 30) continue;
            $items[$i][0] = $row['Item'];
            $items[$i][1] = $row['Repeats'];
            $items[$i][2] = $row['Wins'];
            $items[$i][3] = round(100 * ($items[$i][2] / $items[$i][1]));
            $i++;
        }
        usort($items, function ($a, $b) {
            return $b[3] - $a[3];
        });
        return $items;
    }

    $itemsJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/item.json");
    $itemsObj = json_decode($itemsJson);
    //name description plainText into gold
    function getItemInfo($itemId)
    {
       global $itemsObj;
       $itemInfo = $itemsObj -> data -> $itemId;

       return $itemInfo;
    }

    // GET MATCHES
