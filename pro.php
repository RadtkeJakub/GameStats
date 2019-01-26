<?php
require 'header.php';
require 'Class/All.php';
require 'Class/Champion.php';
require 'Class/Pro.php';
require 'Class/GamesDetails.php';
?>

<div class="row darkBackground">
    <?php
    $all = new All();
    $checkPros = $all -> getPros();
    unset($all);
    $riotAccountId = $_GET['pro'];
    $i = 0;
    foreach ($checkPros as $checkPro)
    {
        global $i;
        if ($checkPro[0] == $_GET['pro'])
        {
            $riotAccountId = $_GET['pro'];
            $i++;
            break;
        }
    }
    if ($i == 0) die("Player not found");

    if (ISSET($_POST['role']) && $_POST['role'] != "" )
    {
        $setRole = $_POST['role'];
    }
    else $setRole = null;

    $pro = new Pro($riotAccountId,$setRole);
    $roles = $pro -> getRoles();
    $name = $pro -> getName();

    $gamesDetails = new GamesDetails($riotAccountId,$setRole);
    $matchesHistory = $gamesDetails -> getMatchHistory();

    $summonerInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/summoner.json");
    $summonerInfo = json_decode($summonerInfoJson);
    $summonerSpells = $summonerInfo -> data;

    $runesInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/runesReforged.json");
    $runesInfo = json_decode($runesInfoJson);

    $itemsInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/item.json");
    $itemsInfo = json_decode($itemsInfoJson);
    $itemsDetails = $itemsInfo -> data;

    $championInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
    $championInfo = json_decode($championInfoJson);
    ?>
    <!-- NAME OF PRO PLAYER-->
    <div class="col-md-8 col-sm-12 staatFont">
            <div class="text-center align-middle mt-3">
                <?php
                echo "<span class='h1'>".$name."</span><br>";
                ?>
            </div>
    </div>

    <!-- CONTAINER FOR ROLE BUTTONS-->
    <div class="col-4 mt-5">
        <div class="d-none d-sm-block text-right">
            <div class="btn-group " role="group" aria-label="First group">
                <form action="pro.php?pro=<?php echo $riotAccountId?>" method="post" id="role">
                    <input type="hidden" name="role" value="">
                    <input type="image" src="icons/all.png" alt="Submit" class="mr-2"/>
                </form>
                <?php
                foreach($roles as $role)
                {
                    if($role == TOP || $role == JUNGLE || $role == MID || $role == BOT || $role == SUPP)
                    {
                        echo "<form action=\"pro.php?pro=".$riotAccountId."\" method=\"post\" id=\"role\">";
                        echo "<input type=\"hidden\" name=\"role\" value=\"$role\">";
                        echo "<input type=\"image\" src=\"icons/$role.png\" alt=\"Submit\" class=\"mr-2\"/>";
                        echo "</form>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- TABLE WITH HIGHEST WIN RATE CHAMPIONS -->

<!--CONTAINER FOR MAIN CONTENT -->
<div class="row darkBackground staatFont justify-content-center">
    <div class="col-md-4 col-sm-12 mt-5 text-right offset-7">
        Highest win rate with
        <table class="table table-borderless table-sm">
            <thead>
            <tr>
                <?php
                $mostPlayedChampions = $pro ->getMostPlayedChampions();
                foreach($mostPlayedChampions as $i => $mostPlayedChampion)
                {
                    $mostPlayedChampionId = $mostPlayedChampion[0];
                    $mostPlayedChampionName = $championInfo -> keys -> $mostPlayedChampionId;
                    $mostPlayedChampionTitle = $championInfo -> data -> $mostPlayedChampionName -> title;
                    echo "<th align='center' style='text-align: center;'   data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($mostPlayedChampionName,ENT_QUOTES)."</b><br>".htmlspecialchars($mostPlayedChampionTitle,ENT_QUOTES)."'>";
                    echo "<img class='img-fluid' alt = 'image of ".$mostPlayedChampionName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$mostPlayedChampionName.".png' width=60px height=60px>";
                    echo "</th>";

                    if ($i == 4) break;
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                foreach($mostPlayedChampions as $i => $mostPlayedChampion)
                {
                    echo "<td align='center' class='";
                    if($mostPlayedChampion[2] > 50) echo "text-success";
                    else if ($mostPlayedChampion[2] < 50) echo "text-danger";
                    echo "'>";
                    echo $mostPlayedChampion[2];
                    echo "</td>";

                    if ($i == 4) break;
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12 justify-content-center">
        <div>
            <h4 align="center">Match History</h4>
            <table class="table table-dark table-sm table-bordered content">
                <?php
                    foreach($matchesHistory as $c => $matchHistory)
                    {
                        foreach($matchHistory as $i => $gameId)
                        {
                            if($i == 0)
                            {
                                $players = $gamesDetails -> getTeams($gameId);
                                $dateAndPatch = $gamesDetails -> getDateAndPatch($gameId);
                                // player[0] = RiotChampionId player[1] = 1 if game won/0 if not
                                echo '<tr class="stop border-bottom border-secondary userSelect">';
                                echo '<td class="mt-0 mb-0 border-0 gamesButton tableIcon tableHover" onclick>';
                                echo '<table align="center">';
                                echo '<tr>';
                                for ($j=0;$j<5;$j++)
                                {
                                    echo '<td class="text-center numbers" data-toggle="tooltip" data-placement="top" title="K/D/A">'.$players[$j][3].'/'.$players[$j][4].'/'.$players[$j][5].'</td>';
                                }
                                echo '</tr>';
                                echo '<tr class="text-center">';
                                for ($j=0;$j<5;$j++)
                                {
                                    $championId = $players[$j][0];
                                    $championName = $championInfo -> keys -> $championId;
                                    $championTitle = $championInfo -> data -> $championName -> title;
                                    echo "<td><img class='img-fluid border ";
                                    if($riotAccountId === $players[$j][2]) echo "border-info";
                                    else if($players[$j][1] == 1) echo "border-success";
                                    else echo "border-danger";
                                    echo "' alt = 'image of ".$championName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$championName.".png' width=60px height=60px data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($championName,ENT_QUOTES)."</b><br>".htmlspecialchars($championTitle,ENT_QUOTES)."' ></td>";
                                }
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td class="text-center" colspan="5">VS</td>';
                                echo '</tr>';
                                echo '<tr>';
                                for ($j=5;$j<10;$j++)
                                {
                                    $championId = $players[$j][0];
                                    $championName = $championInfo -> keys -> $championId;
                                    $championTitle = $championInfo -> data -> $championName -> title;
                                    echo "<td><img class='img-fluid border ";
                                    if($riotAccountId === $players[$j][2]) echo "border-info";
                                    else if($players[$j][1] == 1) echo "border-success";
                                    else echo "border-danger";
                                    echo "' alt = 'image of ".$championName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$championName.".png' width=60px height=60px data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($championName,ENT_QUOTES)."</b><br>".htmlspecialchars($championTitle,ENT_QUOTES)."' ></td>";
                                }
                                echo '</tr>';
                                echo '<tr>';
                                for ($j=5;$j<10;$j++)
                                {
                                    echo '<td class="text-center numbers" data-toggle="tooltip" data-placement="bottom" title="K/D/A">'.$players[$j][3].'/'.$players[$j][4].'/'.$players[$j][5].'</td>';
                                }
                                echo '</tr>';
                                echo '</table>';

                                echo '</td>';
                                echo '</tr>';
                            }
                            else
                            {
                                $champion = $gamesDetails -> getChampion($matchHistory[0],$gameId);
                                $championName = $championInfo -> keys -> $champion;
                                $championTitle = $championInfo -> data -> $championName -> title;
                                $summoners = $gamesDetails -> getSummoners($matchHistory[0],$gameId);
                                $mainRunes = $gamesDetails -> getMainRunes($matchHistory[0],$gameId);
                                $items = $gamesDetails -> getItems($matchHistory[0],$gameId);
                                $runes = $gamesDetails -> getRunes($matchHistory[0],$gameId);
                                $itemsHistory = $gamesDetails -> getItemsHistory($matchHistory[0],$gameId);
                                $skillPoints = $gamesDetails -> getSkillOrder($matchHistory[0],$gameId);

                                echo '<tr class="collapse champion border-bottom border-secondary tableIcon userSelect">';
                                echo '<td class="mt-0 mb-0 border-0 championsButton " onclick style="background-color: #2c3034">';
                                echo "<img class='img-fluid mr-3 border centerMargin ";
                                if($riotAccountId === $gameId) echo "border-info";
                                else if($i <= 5) echo "border-success";
                                else echo "border-danger";
                                echo "' alt = 'image of ".$championName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$championName.".png' width=60px height=60px data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($championName,ENT_QUOTES)."</b><br>".htmlspecialchars($championTitle,ENT_QUOTES)."' >";

                                $mainRuneName = array();
                                $mainRuneImg = array();
                                foreach($mainRunes as $j => $mainRune)
                                {
                                    foreach($runesInfo as $mainRuneInfo)
                                    {
                                        if($mainRune == $mainRuneInfo -> id)
                                        {
                                            $mainRuneName[$j] = $mainRuneInfo -> key;
                                            $mainRuneImg[$j] = $mainRuneInfo -> icon;
                                        }
                                    }
                                }
                                echo "<div  class ='mr-3' style='width: 40px; height: 40px; position: relative; display: inline-block; ' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($mainRuneName[0],ENT_QUOTES)."/".htmlspecialchars($mainRuneName[1],ENT_QUOTES)."</b>'>";
                                echo "<img class='img-fluid mr-3 ' alt = 'image of ".$mainRuneName[0]."' src = 'http://ddragon.leagueoflegends.com/cdn/img/".$mainRuneImg[0]."' width=40px height=40px>";
                                echo "<img id='img2' class='img-fluid mr-3' alt = 'image of ".$mainRuneName[1]."' src = 'http://ddragon.leagueoflegends.com/cdn/img/".$mainRuneImg[1]."' width=20px height=20px style='background-color:black'>";
                                echo '</div>';

                                foreach($summoners as $j => $summoner)
                                {
                                    foreach($summonerSpells as $summonerSpell)
                                    {
                                        if($summoner == $summonerSpell -> key)
                                        {
                                            echo "<img class='img-fluid ";
                                            if ($j == 1) echo "mr-3";
                                            echo "' alt = 'image of ".$summonerSpell -> id."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/".$summonerSpell -> id.".png' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($summonerSpell -> name,ENT_QUOTES)."</b><br>".htmlspecialchars($summonerSpell -> description,ENT_QUOTES)."'>";
                                        }
                                    }
                                }

                                foreach($items as $item)
                                {
                                    $itemName = $itemsDetails -> $item -> name;
                                    $itemDescription = $itemsDetails -> $item -> description;
                                    echo "<img class='img-fluid mr-1' alt = 'image of ".$itemName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$item.".png' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($itemName,ENT_QUOTES)."</b><br>".htmlspecialchars($itemDescription,ENT_QUOTES)."'>";
                                }

                                echo '<div class="collapse pt-5">';

                                echo '<div class="text-left ml-4"><h5>Player name: <a href="pro.php?pro='.$gameId.'">';
                                echo $gamesDetails -> getName($gameId);
                                echo '</a></h5></div>';

                                echo '<div>';
                                echo '<table class="table-sm" align="center">';
                                echo '<tr>';
                                echo '<th colspan="6" class="text-center h3">Runes</th>';
                                echo '</tr>';
                                echo '<tr>';
                                foreach ($runes as $j => $rune)
                                {
                                    foreach ($runesInfo as $runeInfo)
                                    {
                                        $slots = $runeInfo->slots;
                                        foreach ($slots as $x => $slot)
                                        {
                                            $idks = $slot->runes;
                                            foreach ($idks as $y => $idk)
                                            {
                                                if ($idk->id == $rune)
                                                {
                                                    $positionX = $x;
                                                    $positionY = $y+1;
                                                    $runeName = $idk->name;
                                                    $runeDetails = $idk->longDesc;
                                                    $img = $idk->icon;
                                                }
                                            }
                                        }
                                    }
                                    echo "<td class='text-center col-1'><img class='img-fluid float-middle' alt = 'image of " . $runeName . "' src = 'http://ddragon.leagueoflegends.com/cdn/img/" . $img . "' width=60px height=60px data-toggle='tooltip' data-html='true' data-placement='top' title='<b>" . htmlspecialchars($runeName, ENT_QUOTES) . "</b><br>" . htmlspecialchars($runeDetails, ENT_QUOTES) . "'><br>".$positionX." - ".$positionY."</td>";

                                }

                                echo '</tr>';
                                echo '</table>';
                                echo '</div>';

                                echo '<div>';
                                echo '<table class = "table-sm" >';
                                echo '<tr>';
                                echo '<th class = "text-center h3" colspan="'.(count($skillPoints)+1).'">Skill Points</th>';
                                echo '</tr>';
                                echo '<tr>';

                                $skillsName = array();
                                $skillsInfo = $championInfo -> data -> $championName -> spells;
                                foreach($skillsInfo as $j=>$skillInfo)
                                {
                                    $skillsName[$j][0] = $skillInfo -> name;
                                    $skillsName[$j][1] = $skillInfo -> image -> full;
                                }
                                echo '<td></td>';
                                foreach($skillPoints as $j =>$skillPoint)
                                {
                                    echo '<td class="text-center"  >';
                                    echo $j+1;
                                    echo '</td>';
                                }
                                echo '</tr>';

                                echo '<tr>';
                                echo '<td class="text-center" style="width:'.(count($skillPoints)/100).'%">';
                                echo "<img class='img-fluid mr-1' alt = 'image of ".$skillsName[0][0]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/".$skillsName[0][1]."' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($skillsName[0][0], ENT_QUOTES) . "</b>'>";
                                echo '</td>';
                                foreach($skillPoints as $j =>$skillPoint)
                                {
                                    echo '<td class="text-center ';
                                    if ($skillPoint[0] == 1 ) echo 'bg-warning';
                                    echo '" style="width:'.(count($skillPoints)/100).'%">';
                                    echo '</td>';
                                }
                                echo '</tr>';

                                echo '<tr>';
                                echo '<td class="text-center" style="width:'.(count($skillPoints)/100).'%">';
                                echo "<img class='img-fluid mr-1' alt = 'image of ".$skillsName[1][0]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/".$skillsName[1][1]."' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($skillsName[1][0], ENT_QUOTES) . "</b>'>";
                                echo '</td>';

                                foreach($skillPoints as $j =>$skillPoint)
                                {
                                    echo '<td class="text-center ';
                                    if ($skillPoint[0] == 2 ) echo 'bg-warning';
                                    echo '" style="width:'.(count($skillPoints)/100).'%">';
                                    echo '</td>';
                                }
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td class="text-center" style="width:'.(count($skillPoints)/100).'%">';
                                echo "<img class='img-fluid mr-1' alt = 'image of ".$skillsName[2][0]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/".$skillsName[2][1]."' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($skillsName[2][0], ENT_QUOTES) . "</b>'>";
                                echo '</td>';
                                foreach($skillPoints as $j =>$skillPoint)
                                {
                                    echo '<td class="text-center ';
                                    if ($skillPoint[0] == 3 ) echo 'bg-warning';
                                    echo '" style="width:'.(count($skillPoints)/100).'%">';
                                    echo '</td>';
                                }
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td class="text-center" style="width:'.(count($skillPoints)/100).'%">';
                                echo "<img class='img-fluid mr-1' alt = 'image of ".$skillsName[3][0]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/".$skillsName[3][1]."' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($skillsName[3][0], ENT_QUOTES) . "</b>'>";
                                echo '</td>';
                                foreach($skillPoints as $j =>$skillPoint)
                                {
                                    echo '<td class="text-center ';
                                    if ($skillPoint[0] == 4 ) echo 'bg-warning';
                                    echo '" style="width:'.(count($skillPoints)/100).'%">';
                                    echo '</td>';
                                }
                                echo '</tr>';
                                echo '</table>';
                                echo '</div>';


                                echo '<div class="pr-5 pl-5 mt-3">';
                                echo '<div class="text-center h3 mb-0">ITEMS BUY ORDER</div>';
                                echo '<br>';
                                $counter = 0;
                                foreach($itemsHistory as $j => $itemHistory)
                                {
                                    if($j == 0)
                                    {
                                        $itemId = $itemHistory[0];
                                        $itemName = $itemsDetails -> $itemId -> name;
                                        $itemDescription = $itemsDetails -> $itemId -> description;
                                        echo "<img class='img-fluid mr-1' alt = 'image of ".$itemName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$itemId.".png' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($itemName,ENT_QUOTES)."</b><br>".htmlspecialchars($itemDescription,ENT_QUOTES)."'>";

                                    }
                                    else if ($itemHistory[2] - $itemsHistory[$j-1][2] > 30000)
                                    {

                                        echo "<img class='img-fluid mr-1' alt = 'arrow' src = 'icons/arrow.png' width='40px' height='40px' data-toggle='tooltip' data-placement='top' title='recall'>";
                                        $itemId = $itemHistory[0];
                                        $itemName = $itemsDetails -> $itemId -> name;
                                        $itemDescription = $itemsDetails -> $itemId -> description;
                                        echo "<img class='img-fluid mr-1' alt = 'image of ".$itemName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$itemId.".png' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($itemName,ENT_QUOTES)."</b><br>".htmlspecialchars($itemDescription,ENT_QUOTES)."'>";
                                    }
                                    else
                                    {

                                        $itemId = $itemHistory[0];
                                        $itemName = $itemsDetails -> $itemId -> name;
                                        $itemDescription = $itemsDetails -> $itemId -> description;
                                        echo "<img class='img-fluid mr-1' alt = 'image of ".$itemName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$itemId.".png' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($itemName,ENT_QUOTES)."</b><br>".htmlspecialchars($itemDescription,ENT_QUOTES)."'>";
                                    }
                                }
                                echo '</div>';
                                echo '</div>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                    }
                ?>
            </table>
        </div>
    </div>
</div>

<?php require 'footer.php' ?>

