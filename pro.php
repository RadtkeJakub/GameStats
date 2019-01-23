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

    $gamesDetails = new GamesDetails($riotAccountId);
    $matchesHistory = $gamesDetails -> getMatchHistory();


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

<!--CONTAINER FOR MAIN CONTENT -->
<div class="row darkBackground staatFont justify-content-center">
    <div class="col-md-8 col-sm-12 mt-sm-2 mt-md-5">
        <div class="text-center justify-content-center">
            <?php
            $summonerInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/summoner.json");
            $summonerInfo = json_decode($summonerInfoJson);
            $summonerSpells = $summonerInfo -> data;

            $runesInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/runesReforged.json");
            $runesInfo = json_decode($runesInfoJson);

            $itemsInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/item.json");
            $itemsInfo = json_decode($itemsInfoJson);

            $championInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
            $championInfo = json_decode($championInfoJson);
            ?>
            <h4>Match History</h4>
            <table class="table table-striped table-dark table-sm table-bordered">
                <?php
                    foreach($matchesHistory as $matchHistory)
                    {
                        foreach($matchHistory as $i => $gameId)
                        {
                            if($i == 0)
                            {
                                echo '<tr class="stop border-bottom border-secondary userSelect">';
                                echo '<td class="mt-0 mb-0 border-0 gamesButton tableIcon tableHover" onclick>';
                                $players = $gamesDetails -> getTeams($gameId);
                                $dateAndPatch = $gamesDetails -> getDateAndPatch($gameId);
                                // player[0] = RiotChampionId player[1] = 1 if game won/0 if not

                                foreach ($players as $j => $player)
                                {
                                    $championId = $player[0];
                                    $championName = $championInfo -> keys -> $championId;
                                    $championTitle = $championInfo -> data -> $championName -> title;
                                    echo "<img class='img-fluid ml-1 mr-1 border ";
                                    if($riotAccountId === $player[2]) echo "border-info";
                                    else if($player[1] == 1) echo "border-success";
                                    else echo "border-danger";
                                    echo "' alt = 'image of ".$championName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$championName.".png' width=60px height=60px data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($championName,ENT_QUOTES)."</b><br>".htmlspecialchars($championTitle,ENT_QUOTES)."' >";
                                    if ($j == 4) echo "<br><span style='color:white;'>VS</span><br>";
                                }
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

                                echo '<tr class="collapse champion border-bottom border-secondary tableIcon userSelect">';
                                echo '<td class="mt-0 mb-0 border-0 championsButton" onclick>';
                                echo "<img class='img-fluid mr-3 border ";
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

                                foreach($summoners as $summoner)
                                {
                                    foreach($summonerSpells as $summonerSpell)
                                    {
                                        if($summoner == $summonerSpell -> key)
                                        {
                                            echo "<img class='img-fluid' alt = 'image of ".$summonerSpell -> id."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/".$summonerSpell -> id.".png' width='40px' height='40px' data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($summonerSpell -> name,ENT_QUOTES)."</b><br>".htmlspecialchars($summonerSpell -> description,ENT_QUOTES)."'>";
                                        }
                                    }
                                }
                                echo '<div class="collapse" style="border-color:red;border-style:solid"></div>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                    }
                ?>
            </table>
        </div>
    </div>
    <!-- RIGHT TABLES START HERE   -->
    <div class="col-md-4 col-sm-12  mt-5">
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
</div>

<?php require 'footer.php' ?>

