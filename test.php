<?php
    
    $key='api_key=RGAPI-a319d88e-821c-41d6-8409-7b24c779380e';
    
    //taking list of challengers 
    $getChallengerBySoloQ = "/lol/league/v3/challengerleagues/by-queue/RANKED_SOLO_5x5";
    //taking account id of challengers
    $getChallengerAccountIdBySummonerId = "/lol/summoner/v3/summoners/";
    //taking match history by accountId
    $getChallengerMatchHistory = "/lol/match/v3/matchlists/by-account/";
    //solo ranked queue
    $soloQueue = "?queue=420";
        
    
    //url for Brazil

    $url= "https://eun1.api.riotgames.com";

    
        
            $getChallengers = file_get_contents($url.$getChallengerBySoloQ."?".$key);
            $objChallengers = json_decode($getChallengers);
            $challenger = $objChallengers->entries;
            
           // echo $url.$getChallengerBySoloQ.$key. "<br />";
            
                $challengerSummonerId = $challenger[0]->playerOrTeamId;
                
                echo $url.$getChallengerAccountIdBySummonerId.$challengerSummonerId."?".$key."<br />";
                $getChallengerAccountId = file_get_contents(
                    $url.
                    $getChallengerAccountIdBySummonerId.
                    $challengerSummonerId.
                    "?".
                    $key
                );
                
                $objChallengerAccountId = json_decode($getChallengerAccountId);
                $challengerAccountId = $objChallengerAccountId -> accountId;
                echo $challengerAccountId;
                
                echo $url.$getChallengerMatchHistory.$challengerAccountId.$soloQueue."&".$key;
            
                
