<?php

    $key='api_key=RGAPI-a319d88e-821c-41d6-8409-7b24c779380e';

    $leagues = array();
    $leagues["challenger"] = "/lol/league/v4/challengerleagues/by-queue/RANKED_SOLO_5x5";
    $leagues["grandMaster"] = "/lol/league/v4/grandmasterleagues/by-queue/RANKED_SOLO_5x5";
    $leagues["master"] = "/lol/league/v4/masterleagues/by-queue/RANKED_SOLO_5x5";

    //taking account  of challengers
    $getAccountBySummonerName = "/lol/summoner/v4/summoners/";
    //taking challenger match history by accountId
    $getMatchHistory = "/lol/match/v4/matchlists/by-account/";

    $getDetailsById = "/lol/match/v4/matches/";
    $getTimelinesById = "/lol/match/v4/timelines/by-match/";

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




    foreach ($url as $region)
    {
        foreach($leagues as $league)
        {
            $getPlayers = file_get_contents($region . $league . "?" . $key);
            $objPlayers = json_decode($getPlayers);
            $players = $objPlayers -> entries;
            $player = $players[0]->summonerId;
            echo $region . $league . "?" . $key . "<br />";

            $getAccountId = file_get_contents($region . $getAccountBySummonerName . $player . "?" . $key);
            $objAccountId = json_decode($getAccountId);
            $summonerId = $objAccountId->accountId;
            $summonerName = $objAccountId->name;
//            echo $region."<br />";
//            $getGames = file_get_contents($region . $getMatchHistory . $summonerId . $soloRankedQueue . "&" . $key);
//            $objGames = json_decode($getGames);
//            $matches = $objGames->matches;
//            echo $region."<br />";
//            foreach($matches as $match){
//                $gameId = $match -> gameId;
//                $region = $match -> platformId;
//                $matchDate = date('Y-m-d H:i:s',($match -> timestamp)/1000);
//
//                //GET ACCOUNT MATCH DETAILS
//                $getMatchDetails = file_get_contents($region . $getDetailsById . $gameId . "?" . $key);
//                $objMatchDetails = json_decode($getMatchDetails);
//
//                $gameDuration = gmdate('i:s',$objMatchDetails -> gameDuration);
//                $patch = $objMatchDetails -> gameVersion;
//
//                //ADD DATA TO GAME TABLE
//                $game -> execute();
//
//                //GET ACCOUNT MATCH TIMELINES
//                $getMatchTimelines = file_get_contents($url.$getTimelinesById.$gameId."?".$key);
//                $objMatchTimelines = json_decode($getMatchTimelines);
//
//                $participants = $objMatchDetails -> participants;
//                $frames = $objMatchTimelines -> frames;
//
//                foreach($participants as $i=>$participant)
//                {
//                    $role = $participant ->timeline->role;
//                    $lane = $participant ->timeline->lane;
//
//                    if ($role == "SOLO" && $lane == "TOP")
//                    {
//                        $role = "TOP";
//                    }
//                    elseif ($role == "NONE" && $lane == "JUNGLE")
//                    {
//                        $role = "JUNGLE";
//                    }
//                    elseif ($role == "SOLO" && $lane == "MIDDLE")
//                    {
//                        $role = "MID";
//                    }
//                    elseif ($role == "DUO_CARRY" && $lane == "BOTTOM")
//                    {
//                        $role = "CARRY";
//                    }
//                    elseif ($role == "DUO_SUPPORT" && $lane == "BOTTOM")
//                    {
//                        $role = "SUPPORT";
//                    }
//
//                    $accountId = $objMatchDetails ->  participantIdentities[$i] -> player -> accountId;
//                    $summonerName = $objMatchDetails ->  participantIdentities[$i] -> player -> summonerName;
//                    $summoner -> execute();
//
//                    if ($participant -> teamId == 100) $team = "blue";
//                    else $team  = "red";
//
//                    if ($participant -> stats -> win == "Win") $win = 1;
//                    else $win = 0;
//
//                    $item0 = $participant -> stats ->  item0;
//                    $item1 = $participant -> stats ->  item1;
//                    $item2 = $participant -> stats ->  item2;
//                    $item3 = $participant -> stats ->  item3;
//                    $item4 = $participant -> stats ->  item4;
//                    $item5 = $participant -> stats ->  item5;
//                    $item6 = $participant -> stats ->  item6;
//
//                    $kill = $participant -> stats -> kills;
//                    $death =  $participant -> stats -> deaths;
//                    $assist =  $participant -> stats -> assists;
//                    $championId = $participant -> championId;
//
//
//                    $participantId = $i + 1;
//
//
//                    //ADD DATA TO PLAYERGAME TABLE
//                    $playerGame -> execute();
//
//                    echo $summonerName."<br />";
//                    echo $accountId."<br />";
//                    echo $role."<br />";
//                    echo $lane."<br /><br />";
//
//                    echo "<br />";
//                    echo "ACCOUNT: <br />";
//                    echo "account id: ".$accountId."<br />";
//                    echo "summoner name: ".$summonerName."<br /><br />";
//
//                    echo "GAME: <br />";
//                    echo "game id: ".$gameId."<br />";
//                    echo "region: ".$region."<br />";
//                    echo "game duration: " .$gameDuration. "<br />";
//                    echo "patch: " .$patch. "<br />";
//                    echo "data: ".$matchDate."<br /><br />";
//
//                    echo "PLAYER_GAME: <br />";
//                    echo "win: ".$win."<br /><br />";
//                    echo "role: " .$role. "<br />";
//                    echo "champion: " .$championId. "<br />";
//                    echo "team: " .$team. "<br />";
//                    echo "K: " . $kill ."<br />";
//                    echo "D: " . $death."<br />";
//                    echo "A: " . $assist."<br />";
//                    echo "item0: " . $item0 . "<br />";
//                    echo "item1: " . $item1 ."<br />";
//                    echo "item2: " . $item2 ."<br />";
//                    echo "item3: " . $item3 ."<br />";
//                    echo "item4: " . $item4 ."<br />";
//                    echo "item5: " . $item5 ."<br />";
//                    echo "item6: " . $item6 ."<br /><br />";
//
//                    foreach($frames as $j => $frame)
//                    {
//                        $events = $frame -> events;
//                        foreach ($events as $event)
//                        {
//                            $type = $event -> type;
//                            if(($type == "ITEM_PURCHASED" || $type == "ITEM_SOLD" || $type == "ITEM_DESTROYED") && ($event -> participantId) == $participantId)
//                            {
//                                $itemId = $event -> itemId;
//                                $itemTime = $event -> timestamp;
//                                echo "PLAYER_ITEMS: <br />";
//                                echo "action: " .$type. "<br />";
//                                echo "itemid: " .$itemId. "<br />";
//                                echo "seconds: " . $itemTime."<br /><br />";
//
//                                $playerItems->execute();
//                            }
//                            elseif($type == "SKILL_LEVEL_UP" && ($event -> participantId) == $participantId)
//                            {
//                                $event -> timestamp;
//                                $skillslot  = $event ->skillSlot;
//                                $addPointTime = $event -> timestamp;
//                                echo "PLAYER_POINTS <br />";
//                                echo "time: " .$addPointTime. "<br />";
//                                echo "skillslot: " .$skillslot. "<br />";
//                                echo "type: " .$type. "<br /><br />";
//
//                                $playerPoints -> execute();
//                            }
//                        }
//
//                        if ($j == count($frames)-1) continue;
//                        $participantFrame = $frame -> participantFrames -> $participantId;
//
//
//
//                        $x = $participantFrame -> position -> x;
//                        $y = $participantFrame -> position -> y;
//                        $minute = $frame -> timestamp/60000;
//                        $minute = floor($minute);
//                        echo "PLAYER_POSITION: <br />";
//                        echo "X: " .$x. "<br />";
//                        echo "Y: " .$y. "<br />";
//                        echo "minute: " .$minute. "<br /><br />";
//                        $playerPosition ->execute();
//                    }
//
//
//
//
//
//
//
//
//
//                }
//
//
//
//
//                echo $url.$getDetailsById.$gameId."?".$key."<br />";
//                echo $url.$getTimelinesById.$gameId."?".$key."<br />";
//                break;
//            }
        }
    }

