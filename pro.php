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
//            $champion = new Champion();
//            $summoners = $champion -> getSummoners();
            $summonerInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/summoner.json");
            $summonerInfo = json_decode($summonerInfoJson);
            $summonerSpells = $summonerInfo -> data;
//
//
//            $runes = $champion -> getRunes();
            $runesInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/runesReforged.json");
            $runesInfo = json_decode($runesInfoJson);
//
//            $items = $champion -> getItems();
            $itemsInfoJson = file_get_contents("http://ddragon.leagueoflegends.com/cdn/8.24.1/data/en_GB/item.json");
            $itemsInfo = json_decode($itemsInfoJson);
            ?>
            <table class="table table-dark table-sm table-bordered">
                <tr>
                    <th class="mt-0 mb-0 border-0" style="height:10px;"><button type="button" class="btn btn-secondary btn-lg btn-block mt-0 mb-0 border-top-0 border-bottom-0"  data-toggle="collapse" data-target="#demo">Main</button></th>
                </tr>
                <tr id="demo" class="collapse">
                    <td class="mt-0 mb-0 border-0" style="height:10px;"><button type="button" class="btn btn-dark btn-lg btn-block mt-0 mb-0 border-top-0 border-bottom-0"  data-toggle="collapse" data-target="#second">Champion</button></td>
                </tr>
                <tr id="second" class="collapse" >
                    <td>info</td>
                </tr>
                <tr class="smallTr ">

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
                    if ($i == 5) break;

                    $mostPlayedChampionId = $mostPlayedChampion[0];
                    $mostPlayedChampionName = $championInfo -> keys -> $mostPlayedChampionId;
                    $mostPlayedChampionTitle = $championInfo -> data -> $mostPlayedChampionName -> title;
                    echo "<th align='center' style='text-align: center;'   data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($mostPlayedChampionName,ENT_QUOTES)."</b><br>".htmlspecialchars($mostPlayedChampionTitle,ENT_QUOTES)."'>";
                    echo "<img class='img-fluid' alt = 'image of ".$mostPlayedChampionName."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$mostPlayedChampionName.".png' width=60px height=60px>";
                    echo "</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                foreach($mostPlayedChampions as $i => $mostPlayedChampion)
                {
                    if ($i == 5) break;

                    echo "<td align='center' class='";
                    if($mostPlayedChampion[2] > 50) echo "text-success";
                    else if ($mostPlayedChampion[2] < 50) echo "text-danger";
                    echo "'>";
                    echo $mostPlayedChampion[2];
                    echo "</td>";
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require 'footer.php' ?>

