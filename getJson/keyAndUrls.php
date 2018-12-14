<?php
    
    $key='api_key=RGAPI-a319d88e-821c-41d6-8409-7b24c779380e';
    
    //taking list of challengers 
    $getChallengerBySoloQ = "/lol/league/v4/challengerleagues/by-queue/RANKED_SOLO_5x5";
    //taking list of players in master
    $getMasterBySoloQ = "/lol/league/v4/masterleagues/by-queue/RANKED_SOLO_5x5";
    
    //taking account  of challengers
    $getAccountBySummonerName = "/lol/summoner/v4/summoners/";
    //taking challenger match history by accountId
    $getMatchHistory = "/lol/match/v4/matchlists/by-account/";
    
    $getGamesDetails = "/lol/match/v4/matches/";
    
    //solo ranked queue
    $soloRankedQueue = "?queue=420";
        
    $url = array();
    //url for Brazil
    $url["br"] = "https://br1.api.riotgames.com";
    //url for Europe North-East
    $url["eune"] = "https://eun1.api.riotgames.com";
    //url for Europe West
    $url["euw"] = "https://euw1.api.riotgames.com";
    //url for Japan
    $url["jp"] = "https://jp1.api.riotgames.com";
    //url for Korea
    $url["kr"] = "https://kr.api.riotgames.com";
    //url for Namibia
    $url["na"] = "https://na1.api.riotgames.com";
    //url for Oceania
    $url["oc"] = "https://oc1.api.riotgames.com";
    //url for Turkey
    $url["tr"] = "https://tr1.api.riotgames.com";
    //url for Russia
  //  $url["ru"] = "https://ru.api.riotgames.com";
    
    
    
    foreach ($url as $region)
    {
        $getChallenger = file_get_contents($region.$getChallengerBySoloQ."?".$key);
        $objChallenger = json_decode($getChallenger);
        $challengers = $objChallenger -> entries;
        $challenger = $challengers[0] -> summonerId;
        echo $region.$getChallengerBySoloQ."?".$key."<br />";
        
        $getAccountId = file_get_contents($region.$getAccountBySummonerName.$challenger."?".$key);    
        $objAccountId = json_decode($getAccountId);
        $summonerId = $objAccountId -> accountId;
        $summonerName = $objAccountId -> name;
        
        $getGames = file_get_contents($region.$getMatchHistory.$summonerId.$soloRankedQueue."&".$key);
        $objGames = json_decode($getGames);
        $gameId = $objGames->matches[0]->gameId;
     
        $getGameDetails = file_get_contents($region.$getGamesDetails.$gameId."?".$key);
        $objGameDetails = json_decode($getGameDetails);
        echo $region.$getGamesDetails.$gameId."?".$key."<br />";
        
    }
    
    