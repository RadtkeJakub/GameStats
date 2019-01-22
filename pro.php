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
            ?>
            <table class="table table-dark table-sm table-bordered">
                <tr>
                    <th class="mt-0 mb-0 border-0">
                        <button type="button" class="btn btn-secondary btn-lg btn-block gamesButton" data-toggle="collapse">
                            Main
                        </button>
                    </th>
                </tr>
                <tr class="collapse champion">
                    <td class="mt-0 mb-0 border-0">
                        <button type="button" class="btn btn-dark btn-lg btn-block championsButton" data-toggle="collapse">
                            Champion
                        </button>
                        <div class="collapse" style="border-color:red;border-style:solid"></div>
                    </td>
                </tr>
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
                $championInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/championFull.json");
                $championInfo = json_decode($championInfoJson);
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

