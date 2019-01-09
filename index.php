<?php
    require 'header.php';
    require 'All.php';
//    error_reporting(0);
//    ini_set('display_errors', 0);
?>

<div class="row darkBackground staatFont">
    <div class="col-12 col-md-8  text-center mt-5">
        asd
    </div>
    <div class="col-md-4 d-none d-md-block mt-5">
        Champions
        <table class="table table-borderless table-sm">
            <thead>
                <tr>
                <?php
                    $winRate = new All();
                    $champions = $winRate -> getChampions();
                    for($i=0;$i<5;$i++)
                    {
                      echo "<th data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($champions[$i][1],ENT_QUOTES)."</b><br>".htmlspecialchars($champions[$i][4],ENT_QUOTES)   ."'>";
                      echo "<img class='img-fluid' alt = 'image of ".$champions[$i][1]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$champions[$i][1].".png' width=60px height=60px>";
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
                        if($champions[$i][3] > 50) echo "text-success";
                        else if ($champions[$i][3] < 50) echo "text-danger";
                        echo "'>";
                        echo $champions[$i][3];
                        echo "</td>";
                    }
                ?>
                </tr>
                <tr>
                    <?php
                    $winRate = new All();
                    $champions = $winRate -> getChampions();
                    for($i=5;$i<10;$i++)
                    {
                        echo "<td data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($champions[$i][1],ENT_QUOTES)."</b><br>".htmlspecialchars($champions[$i][4],ENT_QUOTES)."'>";
                        echo "<img class='img-fluid' alt = 'image of ".$champions[$i][1]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$champions[$i][1].".png' width=60px height=60px>";
                        echo "</td>";
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    for($i=5;$i<10;$i++)
                    {
                        echo "<td align='center' class='";
                        if($champions[$i][3] > 50) echo "text-success";
                        else if ($champions[$i][3] < 50) echo "text-danger";
                        echo "'>";
                        echo $champions[$i][3];
                        echo "</td>";
                    }
                    ?>
                </tr>
            </tbody>
        </table>
        Items <br />
        <table class="table table-borderless table-sm">
            <thead>
            <tr>
                <?php
                $items = $winRate -> getItems();
                for($i=0;$i<5;$i++)
                {
                    echo "<th data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($items[$i][3],ENT_QUOTES)."</b><br><br>".htmlspecialchars($items[$i][4],ENT_QUOTES)."'>";
                    echo "<img class = 'img-fluid' alt = 'image of ".$items[$i][0]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$items[$i][0].".png'>";
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
                    if($items[$i][2] > 50) echo "text-success";
                    else if ($items[$i][2] < 50) echo "text-danger";
                    echo "'>";
                    echo $items[$i][2];
                    echo "</td>";
                }
                ?>
            </tr>
            <tr>
                <?php
                $items = $winRate -> getItems();
                for($i=5;$i<10;$i++)
                {
                    echo "<td data-toggle='tooltip' data-html='true' data-placement='top' title='<b>".htmlspecialchars($items[$i][3],ENT_QUOTES)."</b><br><br>".htmlspecialchars($items[$i][4],ENT_QUOTES)."'>";
                    echo "<img class = 'img-fluid' alt = 'image of ".$items[$i][0]."' src = 'http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$items[$i][0].".png'>";
                    echo "</td>";
                }
                ?>
            </tr>
            <tr>
                <?php
                for($i=5;$i<10;$i++)
                {
                    echo "<td align='center' class='";
                    if($items[$i][2] > 50) echo "text-success";
                    else if ($items[$i][2] < 50) echo "text-danger";
                    echo "'>";
                    echo $items[$i][2];
                    echo "</td>";
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
                $pros = $winRate ->getPros();
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
                ?>
            </tbody>
        </table>
    </div>
</div>
    


    
    
<?php require 'footer.php' ?>