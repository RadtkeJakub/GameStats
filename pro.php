<?php
require 'Class/All.php';
require 'header.php';
require 'Class/Champion.php';
require 'Class/Pro.php';
?>

<div class="row darkBackground">
    <?php
    $all = new All();
    $checkPros = $all -> getPros();
    unset($all);

    $i = 0;
    foreach ($checkPros as $checkPro)
    {
        global $i;
        if ($checkPro[0] == $_GET['pro'])
        {
            $name = $_GET['pro'];
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

    $pros = new Pro($name,$setRole);
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

