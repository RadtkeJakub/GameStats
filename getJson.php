<?php
    
    $key='?api_key=RGAPI-a319d88e-821c-41d6-8409-7b24c779380e';
    
    //taking list of challengers 
    $getChallengerBySoloQ = "/lol/league/v3/challengerleagues/by-queue/RANKED_SOLO_5x5";
    //taking account id of challengers
    $getChallengerAccountIdBySummonerId = "/lol/summoner/v3/summoners/by-account/";
        
    $url = array();
    //url for Brazil
    $url[0] = "https://br1.api.riotgames.com";
    //url for Europe North-East
    $url[1] = "https://eun1.api.riotgames.com";
    //url for Europe West
    $url[2] = "https://euw1.api.riotgames.com";
    //url for Japan
    $url[3] = "https://jp1.api.riotgames.com";
    //url for Korea
    $url[4] = "https://kr.api.riotgames.com";
    //url for 
    $url[5] = "https://la1.api.riotgames.com";
    //url for 
    $url[6] = "https://la2.api.riotgames.com";
    //url for Namibia
    $url[7] = "https://na1.api.riotgames.com";
    //url for Oceania
    $url[8] = "https://oc1.api.riotgames.com";
    //url for Turkey
    $url[9]= "https://tr1.api.riotgames.com";
    //url for Russia
    $url[10] = "https://ru.api.riotgames.com";
    
        foreach($url as $region)
        {
            $getChallengers = file_get_contents($region.$getChallengerBySoloQ.$key);
            $objChallengers = json_decode($getChallengers);
            $challengers = $objChallengers->entries;
            
            echo $region.$getChallengerBySoloQ.$key. "<br />";
            foreach($challengers as $challenger)
            {
                $challengerSummonerdID = $challenger->playerOrTeamId;
                $getChallengersId = file_get_contents($region.$getChallengerSummonerIdBySummonerId..$key);
            }
            
        }
    
        
         
        
    
?>