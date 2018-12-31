<?php
require 'sql.php';

$requestCounter = 0;
function checkRequestCounter($count)
{
    global $requestCounter;
    $requestCounter+=$count;

    $limitPerSecond = 20;
    $limitPer10Minutes = 100;

    if(($requestCounter % $limitPerSecond == 0) && ($requestCounter % $limitPer10Minutes != 0))
    {
        sleep(1);
    }
    if($requestCounter % $limitPer10Minutes == 0)
    {
        sleep(120);
    }
}

$key='api_key=RGAPI-a319d88e-821c-41d6-8409-7b24c779380e';
$url = "https://eun1.api.riotgames.com";

$leagues = array();
$leagues["challenger"] = "/lol/league/v4/challengerleagues/by-queue/RANKED_SOLO_5x5";
$leagues["grandMaster"] = "/lol/league/v4/grandmasterleagues/by-queue/RANKED_SOLO_5x5";
$leagues["master"] = "/lol/league/v4/masterleagues/by-queue/RANKED_SOLO_5x5";

$getSummonerAccountId = "/lol/summoner/v4/summoners/";
$getSummonerMatchHistory = "/lol/match/v4/matchlists/by-account/";
$getDetailsById = "/lol/match/v4/matches/";
$getTimelinesById = "/lol/match/v4/timelines/by-match/";

foreach ($leagues as $league) {
    checkRequestCounter(1);
    $getSummonerId = file_get_contents($url.$league."?".$key);
    $objSummonerId = json_decode($getSummonerId);
    $topPlayers = $objSummonerId -> entries;

    foreach($topPlayers as $topPlayer) {
        $topPlayerSummonerId = $topPlayer -> summonerId;

        //GET INFO ABOUT ACCOUNT id,accountId,puuid,name,profileIconId,revisionDate,summonerLevel
        checkRequestCounter(1);
        $getSummoner = file_get_contents($url . $getSummonerAccountId . $topPlayerSummonerId . "?" . $key);
        $objSummoner = json_decode($getSummoner);
        $accountId = $objSummoner->accountId;

        //GET INFO ABOUT ACCOUNT MATCH HISTORY matches[0-99](platformId,gameId,champion,queue,season,timestamp,role,lane),totalGames
        checkRequestCounter(1);
        $getSummonerHistory = file_get_contents($url . $getSummonerMatchHistory . $accountId . "?" . $key);
        $objSummonerHistory = json_decode($getSummonerHistory);
        $matches = $objSummonerHistory->matches;

        foreach ($matches as $match) {
            $gameId = $match -> gameId;
            $getGame -> execute();
            $getGame -> store_result();
            $getGame -> fetch();
            $gameRows = $getGame -> num_rows;

            if ($gameRows == 1) continue;
            echo $gameRows."<br />";
            $region = $match->platformId;
            $matchDate = date('Y-m-d H:i:s', ($match->timestamp) / 1000);

            //GET ACCOUNT MATCH DETAILS
            checkRequestCounter(1);
            $getMatchDetails = file_get_contents($url . $getDetailsById . $gameId . "?" . $key);
            $objMatchDetails = json_decode($getMatchDetails);

            $gameDuration = gmdate('i:s', $objMatchDetails->gameDuration);
            $patch = $objMatchDetails->gameVersion;

            //ADD DATA TO GAME TABLE
            $game->execute();

            //GET ACCOUNT MATCH TIMELINES
            checkRequestCounter(1);
            $getMatchTimelines = file_get_contents($url . $getTimelinesById . $gameId . "?" . $key);
            $objMatchTimelines = json_decode($getMatchTimelines);

            $participants = $objMatchDetails->participants;
            $frames = $objMatchTimelines->frames;

            foreach ($participants as $i => $participant) {
                $role = $participant->timeline->role;
                $lane = $participant->timeline->lane;

                if ($role == "SOLO" && $lane == "TOP") {
                    $role = "TOP";
                } elseif ($role == "NONE" && $lane == "JUNGLE") {
                    $role = "JUNGLE";
                } elseif ($role == "SOLO" && $lane == "MIDDLE") {
                    $role = "MID";
                } elseif ($role == "DUO_CARRY" && $lane == "BOTTOM") {
                    $role = "CARRY";
                } elseif ($role == "DUO_SUPPORT" && $lane == "BOTTOM") {
                    $role = "SUPPORT";
                }

                $accountId = $objMatchDetails->participantIdentities[$i]->player->accountId;
                $summonerName = $objMatchDetails->participantIdentities[$i]->player->summonerName;
                $summoner->execute();

                if ($participant->teamId == 100) $team = "blue";
                else $team = "red";

                if ($participant->stats->win == "Win") $win = 1;
                else $win = 0;

                for($position = 0;$position < 7; $position++)
                {
                  $items = "item".$position;
                  $item = $participant -> stats -> $items;
                  if($item != 0) $playerEndItems -> execute();
                }
                $item0 = $participant->stats->item0;
                $item1 = $participant->stats->item1;
                $item2 = $participant->stats->item2;
                $item3 = $participant->stats->item3;
                $item4 = $participant->stats->item4;
                $item5 = $participant->stats->item5;
                $item6 = $participant->stats->item6;

                $mainPerk = $participant->stats->perkPrimaryStyle;
                $subPerk = $participant->stats->perkSubStyle;
                $Perk1 = $participant->stats->perk0;
                $Perk2 = $participant->stats->perk1;
                $Perk3 = $participant->stats->perk2;
                $Perk4 = $participant->stats->perk3;
                $Perk5 = $participant->stats->perk4;
                $Perk6 = $participant->stats->perk5;
                //ADD PERK INFO TO DATABASE
                $playerRunes -> execute();
                $kill = $participant->stats->kills;
                $death = $participant->stats->deaths;
                $assist = $participant->stats->assists;
                $championId = $participant->championId;


                $participantId = $i + 1;


                //ADD DATA TO PLAYERGAME TABLE
                $playerGame->execute();

                foreach ($frames as $j => $frame) {
                    $events = $frame->events;
                    foreach ($events as $event) {
                        $type = $event->type;
                        if (($type == "ITEM_PURCHASED" || $type == "ITEM_SOLD" || $type == "ITEM_DESTROYED") && ($event->participantId) == $participantId) {
                            $itemId = $event->itemId;
                            $itemTime = $event->timestamp;

                            $playerItems->execute();
                        } elseif ($type == "SKILL_LEVEL_UP" && ($event->participantId) == $participantId) {
                            $event->timestamp;
                            $skillslot = $event->skillSlot;
                            $addPointTime = $event->timestamp;

                            $playerPoints->execute();
                        }
                    }

                    if ($j == count($frames) - 1) continue;
                    $participantFrame = $frame->participantFrames->$participantId;


                    $x = $participantFrame->position->x;
                    $y = $participantFrame->position->y;
                    $minute = $frame->timestamp / 60000;
                    $minute = floor($minute);

                    $playerPosition->execute();
                }


            }

        }
    }
}