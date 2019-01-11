<?php
require 'Class/All.php';
require 'header.php';
require 'Class/Champion.php';
?>

<div class="row darkBackground">
    <?php
    $all = new All();
    $checkChampions = $all -> getChampions();

    $i = 0;
    foreach ($checkChampions as $checkChampion)
    {
        global $i;
        if ($checkChampion[0] == $_GET['champion'])
        {
            $championId = $_GET['champion'];
            $i++;
            break;
        }
    }
    if ($i=0) die("Champion not found");
    if (ISSET($_POST['role']) && $_POST['role'] != "" )
    {
        $setRole = $_POST['role'];
    }
    else $setRole = null;

    $champion = new Champion($_GET['champion']);
    $roles = $champion -> getRoles();
    $name = $champion -> getName();
    ?>

<!-- NAME AND IMAGE OF CHAMPION-->
    <div class="col-md-8 col-sm-12 staatFont">
        <div class="text-center align-middle mt-3">
            <?php
            echo "<span class='h1'>".$name."</span><br>";
            echo "<img class='img-fluid mt-0 pt-0 border-top-0' alt = 'image of ".$name."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$name.".png'>";
            ?>
        </div>
    </div>

<!-- CONTAINER FOR ROLE BUTTONS-->
    <div class="col-4 mt-5">
        <div class="d-none d-sm-block text-right">
            <div class="btn-group " role="group" aria-label="First group">
                <form action="champion.php?champion=<?php echo $championId?>" method="post" id="role">
                    <input type="hidden" name="role" value="">
                    <input type="image" src="icons/all.png" alt="Submit" class="mr-2"/>
                </form>
                <?php
                foreach($roles as $role)
                {
                    if($role == TOP || $role == JUNGLE || $role == MID || $role == BOT || $role == SUPP)
                    {
                        echo "<form action=\"champion.php?champion=".$championId."\" method=\"post\" id=\"role\">";
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
                $summoners = $champion -> getSummoners();
                $summonerInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/summoner.json");
                $summonerInfo = json_decode($summonerInfoJson);
                $summonerSpells = $summonerInfo -> data;


                $runes = $champion -> getRunes();
                $runesInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/runesReforged.json");
                $runesInfo = json_decode($runesInfoJson);

                $items = $champion -> getItems();
                $itemsInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/item.json");
                $itemsInfo = json_decode($itemsInfoJson);


            ?>
            <table class="table table-sm table-borderless col-md-4 offset-md-4 ">
                <thead>
                <tr>
                    <th colspan="3" class="h4" scope="col">Summoner Spells:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php
                        foreach ($summoners as $summoner)
                        {

                            foreach($summonerSpells as $summonerSpell)
                            {
                                if($summoner[0] == $summonerSpell -> key)
                                {
                                    echo "<td data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($summonerSpell -> name,ENT_QUOTES)."</b><br>".htmlspecialchars($summonerSpell -> description,ENT_QUOTES)."'><img class='img-fluid' alt = 'image of ".$summonerSpell -> id."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/spell/".$summonerSpell -> id.".png'></td>";
                                }
                            }
                        }
                    ?>
                </tr>
                <tr>
                    <?php
                    foreach ($summoners as $summoner)
                    {
                        echo "<td class ='";
                        if($summoner[2] > 50) echo "text-success";
                        else if ($summoner[2] < 50) echo "text-danger";
                        echo "' >";
                        echo $summoner[2]."</td>";
                    }
                    ?>
                </tr>
                </tbody>
            </table>
            <table class="table table-sm table-bordered table-dark">
                <thead>
                <tr>
                    <th colspan="7" class="h4" scope="col">Runes:</th>
                </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($runes as $rune)
                    {
                        echo "<tr>";

                        for($i=2;$i <= 7;$i++)
                        {
                            foreach($runesInfo as $runeInfo)
                            {
                                $slots = $runeInfo -> slots;
                                foreach($slots as $slot)
                                {
                                    $idks = $slot -> runes;
                                    foreach ($idks as $idk)
                                    {
                                        if($idk->id == $rune[$i])
                                        {
                                            $runeName = $idk -> name;
                                            $runeDetails = $idk -> longDesc;
                                            $img =  $idk -> icon;
                                        }
                                    }
                                }
                            }
                            echo "<td data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($runeName,ENT_QUOTES)."</b><br>".htmlspecialchars($runeDetails,ENT_QUOTES)."'>";
                            echo "<img class='img-fluid float-middle' alt = 'image of ".$runeName."' src = 'http://ddragon.leagueoflegends.com/cdn/img/".$img."' width=60px height=60px>";
                            echo "</td>";
                        }
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='6' class ='h4 ";
                        if($rune[9] > 50) echo "text-success";
                        else if ($rune[9] < 50) echo "text-danger";
                        echo "' >";
                        echo $rune[9]."</td>";
                        echo "</tr>";
                    }
                    ?>


                </tbody>
            </table>
            <!-- ITEMS TABLE -->
            <!-- TODO: CHANGE RUNES TABLE TO ITEMS TABLE -->
            <table class="table table-sm table-bordered table-dark">
                <thead>
                <tr>
                    <th colspan="5" class="h4" scope="col">Items</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <?php
                foreach ($items as $i => $item )
                {
                    $itemId = $item[0];
                    $itemName = $itemsInfo -> data -> $itemId -> name;
                    $itemDescription = $itemsInfo -> data -> $itemId -> description;
                    echo "<td data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($itemName,ENT_QUOTES)."</b><br><br>".htmlspecialchars($itemDescription,ENT_QUOTES)."'>";
                    echo "<img class = 'img-fluid' alt = 'image of ".$item[0]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$item[0].".png'>";
                    echo "</td>";
                    if ($i == 4) break;
                }
                ?>
                </tr>
                <tr>
                    <?php
                    foreach ($items as $i => $item )
                    {
                        echo "<td class ='";
                        if($item[2] > 50) echo "text-success";
                        else if ($item[2] < 50) echo "text-danger";
                        echo "' >";
                        echo $item[2]."</td>";
                        if ($i == 4) break;
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    foreach ($items as $i => $item )
                    {
                        if ($i < 5) continue;
                        $itemId = $item[0];
                        $itemName = $itemsInfo -> data -> $itemId -> name;
                        $itemDescription = $itemsInfo -> data -> $itemId -> description;
                        echo "<td data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($itemName,ENT_QUOTES)."</b><br><br>".htmlspecialchars($itemDescription,ENT_QUOTES)."'>";
                        echo "<img class = 'img-fluid' alt = 'image of ".$item[0]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$item[0].".png'>";
                        echo "</td>";

                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    foreach ($items as $i => $item )
                    {
                        if ($i < 5) continue;
                        echo "<td class ='";
                        if($item[2] > 50) echo "text-success";
                        else if ($item[2] < 50) echo "text-danger";
                        echo "' >";
                        echo $item[2]."</td>";
                    }
                    ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<!-- RIGHT TABLES START HERE   -->
    <div class="col-md-4 col-sm-12  mt-5">
        Best with
        <table class="table table-borderless table-sm">
            <thead>
            <tr>
                <?php
                $bestWith = $champion ->getBestTeamMatchups();
                $championInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
                $championInfo = json_decode($championInfoJson);
                for($i=0;$i<5;$i++)
                {
                    $mateId = $bestWith[$i][0];
                    $mateName = $championInfo -> keys -> $mateId;
                    $mateTitle = $championInfo -> data -> $mateName -> title;
                    echo "<th data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($mateName,ENT_QUOTES)."</b><br>".htmlspecialchars($mateTitle,ENT_QUOTES)."'>";
                    echo "<img class='img-fluid' alt = 'image of ".$mateName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$mateName.".png' width=60px height=60px>";
                    echo "</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                for($i=0;$i<5;$i++)
                {
                    echo "<td align='center' class='";
                    if($bestWith[$i][2] > 50) echo "text-success";
                    else if ($bestWith[$i][2] < 50) echo "text-danger";
                    echo "'>";
                    echo $bestWith[$i][2];
                    echo "</td>";
                }
                ?>
            </tr>
            </tbody>
        </table>
        Worst with
        <table class="table table-borderless table-sm">
            <thead>
            <tr>
                <?php
                $worstWith = $champion ->getWorstTeamMatchups();
                $championInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
                $championInfo = json_decode($championInfoJson);
                for($i=0;$i<5;$i++)
                {
                    if ($bestWith[$i][0] == $worstWith[$i][0])
                    {
                        echo "<br> NO DATA";
                        break;
                    }
                    $mateId = $worstWith[$i][0];
                    $mateName = $championInfo -> keys -> $mateId;
                    $mateTitle = $championInfo -> data -> $mateName -> title;
                    echo "<th data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($mateName,ENT_QUOTES)."</b><br>".htmlspecialchars($mateTitle,ENT_QUOTES)."'>";
                    echo "<img class='img-fluid' alt = 'image of ".$mateName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$mateName.".png' width=60px height=60px>";
                    echo "</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                for($i=0;$i<5;$i++)
                {
                    if ($bestWith[$i][0] == $worstWith[$i][0]) continue;
                    echo "<td align='center' class='";
                    if($worstWith[$i][2] > 50) echo "text-success";
                    else if ($worstWith[$i][2] < 50) echo "text-danger";
                    echo "'>";
                    echo $worstWith[$i][2];
                    echo "</td>";
                }
                ?>
            </tr>
            </tbody>
        </table>
        Best VS
        <table class="table table-borderless table-sm">
            <thead>
            <tr>
                <?php
                $bestVS = $champion ->getBestEnemies();
                $championInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
                $championInfo = json_decode($championInfoJson);
                for($i=0;$i<5;$i++)
                {
                    if ($bestWith[$i][0] == $bestVS[$i][0])
                    {
                        echo "<br> NO DATA";
                        break;
                    }
                    $mateId = $bestVS[$i][0];
                    $mateName = $championInfo -> keys -> $mateId;
                    $mateTitle = $championInfo -> data -> $mateName -> title;
                    echo "<th data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($mateName,ENT_QUOTES)."</b><br>".htmlspecialchars($mateTitle,ENT_QUOTES)."'>";
                    echo "<img class='img-fluid' alt = 'image of ".$mateName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$mateName.".png' width=60px height=60px>";
                    echo "</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                for($i=0;$i<5;$i++)
                {
                    $bestVS[$i][2] = 100 - $bestVS[$i][2];
                    if ($bestWith[$i][0] == $bestVS[$i][0]) continue;
                    echo "<td align='center' class='";
                    if($bestVS[$i][2] > 50) echo "text-success";
                    else if ($bestVS[$i][2] < 50) echo "text-danger";
                    echo "'>";
                    echo $bestVS[$i][2];
                    echo "%</td>";
                }
                ?>
            </tr>
            </tbody>
        </table>
        Worst VS
        <table class="table table-borderless table-sm">
            <thead>
            <tr>
                <?php
                $worstVS = $champion ->getWorstEnemies();
                $championInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
                $championInfo = json_decode($championInfoJson);
                for($i=0;$i<5;$i++)
                {
                    if ($bestVS[$i][0] == $worstVS[$i][0])
                    {
                        echo "<br> NO DATA";
                        break;
                    }
                    $mateId = $worstVS[$i][0];
                    $mateName = $championInfo -> keys -> $mateId;
                    $mateTitle = $championInfo -> data -> $mateName -> title;
                    echo "<th data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($mateName,ENT_QUOTES)."</b><br>".htmlspecialchars($mateTitle,ENT_QUOTES)."'>";
                    echo "<img class='img-fluid' alt = 'image of ".$mateName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$mateName.".png' width=60px height=60px>";
                    echo "</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                for($i=0;$i<5;$i++)
                {
                    $worstVS[$i][2] = 100 - $worstVS[$i][2];
                    if ($worstWith[$i][0] == $worstVS[$i][0]) continue;
                    echo "<td align='center' class='";
                    if($worstVS[$i][2] > 50) echo "text-success";
                    else if ($worstVS[$i][2] < 50) echo "text-danger";
                    echo "'>";
                    echo $worstVS[$i][2];
                    echo "%</td>";
                }
                ?>
            </tr>
            </tbody>
        </table>

        <table class="table table-hover table-borderless table-sm">
            <thead>
            <tr>
                <th rowspan="2"> Players</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $pros = $champion -> getPros();
            for($i=0;$i<5;$i++)
            {
                echo "<tr class = 'tableIcon' onclick=\"window.location='#';\">";
                echo "<td class='align-middle'>";
                echo $pros[$i][0];
                echo "</td>";
                echo "<td align='right' class='align-middle ";
                if($pros[$i][2] > 50) echo "text-success";
                else if ($pros[$i][2] < 50) echo "text-danger";
                echo "'>";
                echo $pros[$i][2];
                echo "</td>";
                echo "</tr>";
            }
            unset($winRate);
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'footer.php' ?>

