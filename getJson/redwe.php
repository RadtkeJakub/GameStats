<?php
    
    $key='api_key=RGAPI-a319d88e-821c-41d6-8409-7b24c779380e';
    
    $getRedweAccountId = "/lol/summoner/v4/summoners/by-name/redwe";
    $getRedweMatchHistory = "/lol/match/v4/matchlists/by-account/";
    $getDetailsById = "/lol/match/v4/matches/";
    $getTimelinesById = "/lol/match/v4/timelines/by-match/";

    $url = "https://eun1.api.riotgames.com";
    
    //GET INFO ABOUT ACCOUNT id,accountId,puuid,name,profileIconId,revisionDate,summonerLevel
    $getRedwe = file_get_contents($url.$getRedweAccountId."?".$key);
    $objRedwe = json_decode($getRedwe);
    echo $url.$getRedweAccountId."?".$key."<br />";
    
    $accountId = $objRedwe -> accountId;
    $summonerName = $objRedwe -> name;
    
    //GET INFO ABOUT ACCOUNT MATCH HISTORY matches[0-99](platformId,gameId,champion,queue,season,timestamp,role,lane),totalGames
    $getRedweHistory = file_get_contents($url.$getRedweMatchHistory.$accountId."?".$key);
    $objRedweHisotry = json_decode($getRedweHistory);
    echo $url.$getRedweMatchHistory.$accountId."?".$key."<br />";
    $matches = $objRedweHisotry->matches;
    
    foreach($matches as $match){
       $gameId = $match -> gameId; 
       $region = $match -> platformId;
       $date = date('Y-m-d H:i:s',($match -> timestamp)/1000);
       
       //GET ACCOUNT MATCH DETAILS
       $getMatchDetails = file_get_contents($url.$getDetailsById.$gameId."?".$key);
       $objMatchDetails = json_decode($getMatchDetails);
       
       $gameDuration = gmdate('i:s',$objMatchDetails -> gameDuration);
       $patch = $objMatchDetails -> gameVersion;
       
       //GET ACCOUNT MATCH TIMELINES
       $getMatchTimelines = file_get_contents($url.$getTimelinesById.$gameId."?".$key);
       $objMatchTimelines = json_decode($getMatchTimelines);
       
       echo "<br />";
       echo "ACCOUNT: <br />";
       echo "account id: ".$accountId."<br />";
       echo "summoner name: ".$summonerName."<br /><br />";
       
       echo "GAME: <br />";
       echo "game id: ".$gameId."<br />";
       echo "region: ".$region."<br />";
       echo "game duration: " .$gameDuration. "<br />";
       echo "patch: " .$patch. "<br />";
       echo "data: ".$date."<br /><br />";
       
       echo "PLAYER_GAME: <br />";
       echo "role: " . "<br />";
       echo "champion: " . "<br />";
       echo "K: " . "<br />";
       echo "D: " . "<br />";
       echo "A: " . "<br />";
       echo "item0: " . "<br />";
       echo "item1: " . "<br />";
       echo "item2: " . "<br />";
       echo "item3: " . "<br />";
       echo "item4: " . "<br />";
       echo "item5: " . "<br />";
       echo "item6: " . "<br /><br />";
       
       echo "PLAYER_POSITION: <br />";
       echo "X: " . "<br />";
       echo "Y: " . "<br />";
       echo "minute: " . "<br /><br />";
       
       echo "PLAYER_ITEMS: <br />";
       echo "action: " . "<br />";
       echo "itemid: " . "<br />";
       echo "seconds: " . "<br /><br />";
       
       
       echo "PLAYER_POINTS <br />";
       echo "level: " . "<br />";
       echo "skillslot: " . "<br />";
       echo "type: " . "<br /><br />";
       
       
       echo $url.$getDetailsById.$gameId."?".$key."<br />";
       echo $url.$getTimelinesById.$gameId."?".$key."<br />";
       break;
    }
    
    $epoch = 1544445754244;
    echo date('Y-m-d H:i:s',$epoch/1000);