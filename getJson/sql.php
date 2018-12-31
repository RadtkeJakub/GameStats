<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "inzynierka";

    //CREATE CONNECTION
    $conn = new mysqli($servername,$username,$password,$dbname);

    //CHECK CONNECTION
    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }

    //PREPARE SUMMONER
    $summoner = $conn -> prepare("INSERT INTO player(RiotAccountId, SummonerName) VALUES (?, ?)");
    $summoner -> bind_param("ss",$accountId,$summonerName);

    //PREPARE GAME
    $game = $conn ->prepare("INSERT INTO game(RiotgameId, Region, GameDuration, Patch, GameDate) VALUES (?, ?, ?, ?, ?)");
    $game -> bind_param("isiss",$gameId,$region,$gameDuration,$patch,$matchDate);

    //PREPARE PLAYER GAME
    $playerGame = $conn -> prepare("INSERT INTO playergame(RiotAccountId, RiotGameId, Role, RiotChampionId, Win, Team, Kills, Deaths, Assists, Item0, Item1, Item2, Item3, Item4, Item5, Item6)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $playerGame -> bind_param("sisiisiiiiiiiiii",$accountId,$gameId,$role,$championId,$win,$team,$kill,$death,$assist,$item0,$item1,$item2,$item3,$item4,$item5,$item6);

    //PREPARE PLAYER ITEMS
    $playerItems = $conn -> prepare("INSERT INTO playeritems(RiotAccountId, RiotGameId, Type, RiotItemId, Seconds) VALUES (?, ?, ?, ?, ?)");
    $playerItems -> bind_param("sisii", $accountId,$gameId,$type,$itemId,$itemTime);

    //PREPARE PLAYER POINTS
    $playerPoints = $conn -> prepare("INSERT INTO playerpoints(RiotAccountId, RiotGameId, SkillSlot, Type, AddTime) VALUES (?, ?, ?, ?, ?)");
    $playerPoints -> bind_param("siisi", $accountId,$gameId,$skillslot,$type,$addPointTime);

    //PREPARE PLAYER POSITION
    $playerPosition = $conn -> prepare("INSERT INTO playerposition(RiotAccountId, RiotGameId, X, Y, Minute) VALUES (?, ?, ?, ?, ?)");
    $playerPosition -> bind_param("siiii",$accountId,$gameId,$x,$y,$minute);

    //PREPARE PLAYER RUNES
    $playerRunes = $conn -> prepare("INSERT INTO playerrunes(RiotAccountId,RiotGameId,MainPerk,SubPerk,Perk1,Perk2,Perk3,Perk4,Perk5,Perk6) VALUES(?,?,?,?,?,?,?,?,?,?)");
    $playerRunes -> bind_param("siiiiiiiii",$accountId,$gameId,$mainPerk,$subPerk,$Perk1,$Perk2,$Perk3,$Perk4,$Perk5,$Perk6);

    //PREPARE PLAYER END ITEMS
    $playerEndItems = $conn -> prepare("INSERT INTO playerenditems(RiotAccountId,RiotGameId,Item, Position) VALUES(?,?,?,?)");
    $playerEndItems -> bind_param("siii",$accountId,$gameId,$item,$position);

    $getGame = $conn -> prepare("SELECT RiotGameId FROM game WHERE RiotGameId = ?");
    $getGame -> bind_param("i",$gameId);


