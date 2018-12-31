<?php
    require 'sql.php';

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

    //GET INFO ABOUT ACCOUNT MATCH HISTORY matches[0-99](platformId,gameId,champion,queue,season,timestamp,role,lane),totalGames
    $getRedweHistory = file_get_contents($url.$getRedweMatchHistory.$accountId."?".$key);
    $objRedweHistory = json_decode($getRedweHistory);
    echo $url.$getRedweMatchHistory.$accountId."?".$key."<br />";
    $matches = $objRedweHistory->matches;

    foreach($matches as $match){
       $gameId = $match -> gameId;
       $region = $match -> platformId;
       $matchDate = date('Y-m-d H:i:s',($match -> timestamp)/1000);

       //GET ACCOUNT MATCH DETAILS
       $getMatchDetails = file_get_contents($url.$getDetailsById.$gameId."?".$key);
       $objMatchDetails = json_decode($getMatchDetails);

       $gameDuration = gmdate('i:s',$objMatchDetails -> gameDuration);
       $patch = $objMatchDetails -> gameVersion;

       //ADD DATA TO GAME TABLE
       $game -> execute();

       //GET ACCOUNT MATCH TIMELINES
       $getMatchTimelines = file_get_contents($url.$getTimelinesById.$gameId."?".$key);
       $objMatchTimelines = json_decode($getMatchTimelines);

       $participants = $objMatchDetails -> participants;
       $frames = $objMatchTimelines -> frames;

       foreach($participants as $i=>$participant)
       {
           $role = $participant ->timeline->role;
           $lane = $participant ->timeline->lane;

           if ($role == "SOLO" && $lane == "TOP")
           {
               $role = "TOP";
           }
           elseif ($role == "NONE" && $lane == "JUNGLE")
           {
               $role = "JUNGLE";
           }
           elseif ($role == "SOLO" && $lane == "MIDDLE")
           {
               $role = "MID";
           }
           elseif ($role == "DUO_CARRY" && $lane == "BOTTOM")
           {
               $role = "CARRY";
           }
           elseif ($role == "DUO_SUPPORT" && $lane == "BOTTOM")
           {
               $role = "SUPPORT";
           }

           $accountId = $objMatchDetails ->  participantIdentities[$i] -> player -> accountId;
           $summonerName = $objMatchDetails ->  participantIdentities[$i] -> player -> summonerName;
           $summoner -> execute();

           if ($participant -> teamId == 100) $team = "blue";
           else $team  = "red";

           if ($participant -> stats -> win == "Win") $win = 1;
           else $win = 0;

           $item0 = $participant -> stats ->  item0;
           $item1 = $participant -> stats ->  item1;
           $item2 = $participant -> stats ->  item2;
           $item3 = $participant -> stats ->  item3;
           $item4 = $participant -> stats ->  item4;
           $item5 = $participant -> stats ->  item5;
           $item6 = $participant -> stats ->  item6;

           $kill = $participant -> stats -> kills;
           $death =  $participant -> stats -> deaths;
           $assist =  $participant -> stats -> assists;
           $championId = $participant -> championId;


           $participantId = $i + 1;


           //ADD DATA TO PLAYERGAME TABLE
           $playerGame -> execute();

           echo $summonerName."<br />";
           echo $accountId."<br />";
           echo $role."<br />";
           echo $lane."<br /><br />";

           echo "<br />";
           echo "ACCOUNT: <br />";
           echo "account id: ".$accountId."<br />";
           echo "summoner name: ".$summonerName."<br /><br />";

           echo "GAME: <br />";
           echo "game id: ".$gameId."<br />";
           echo "region: ".$region."<br />";
           echo "game duration: " .$gameDuration. "<br />";
           echo "patch: " .$patch. "<br />";
           echo "data: ".$matchDate."<br /><br />";

           echo "PLAYER_GAME: <br />";
           echo "win: ".$win."<br /><br />";
           echo "role: " .$role. "<br />";
           echo "champion: " .$championId. "<br />";
           echo "team: " .$team. "<br />";
           echo "K: " . $kill ."<br />";
           echo "D: " . $death."<br />";
           echo "A: " . $assist."<br />";
           echo "item0: " . $item0 . "<br />";
           echo "item1: " . $item1 ."<br />";
           echo "item2: " . $item2 ."<br />";
           echo "item3: " . $item3 ."<br />";
           echo "item4: " . $item4 ."<br />";
           echo "item5: " . $item5 ."<br />";
           echo "item6: " . $item6 ."<br /><br />";

           foreach($frames as $j => $frame)
           {
               $events = $frame -> events;
               foreach ($events as $event)
               {
                   $type = $event -> type;
                   if(($type == "ITEM_PURCHASED" || $type == "ITEM_SOLD" || $type == "ITEM_DESTROYED") && ($event -> participantId) == $participantId)
                   {
                       $itemId = $event -> itemId;
                       $itemTime = $event -> timestamp;
                       echo "PLAYER_ITEMS: <br />";
                       echo "action: " .$type. "<br />";
                       echo "itemid: " .$itemId. "<br />";
                       echo "seconds: " . $itemTime."<br /><br />";

                       $playerItems->execute();
                   }
                   elseif($type == "SKILL_LEVEL_UP" && ($event -> participantId) == $participantId)
                   {
                       $event -> timestamp;
                       $skillslot  = $event ->skillSlot;
                       $addPointTime = $event -> timestamp;
                       echo "PLAYER_POINTS <br />";
                       echo "time: " .$addPointTime. "<br />";
                       echo "skillslot: " .$skillslot. "<br />";
                       echo "type: " .$type. "<br /><br />";

                       $playerPoints -> execute();
                   }
               }

               if ($j == count($frames)-1) continue;
               $participantFrame = $frame -> participantFrames -> $participantId;



               $x = $participantFrame -> position -> x;
               $y = $participantFrame -> position -> y;
               $minute = $frame -> timestamp/60000;
               $minute = floor($minute);
               echo "PLAYER_POSITION: <br />";
               echo "X: " .$x. "<br />";
               echo "Y: " .$y. "<br />";
               echo "minute: " .$minute. "<br /><br />";
               $playerPosition ->execute();
           }









       }




       echo $url.$getDetailsById.$gameId."?".$key."<br />";
       echo $url.$getTimelinesById.$gameId."?".$key."<br />";
       break;
    }

    $epoch = 1544445754244;
    echo date('Y-m-d H:i:s',$epoch/1000);