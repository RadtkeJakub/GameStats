<?php
    require 'header.php';
    require 'mainSql.php';
?>

    

    <div class="row staatFont rowBackground">

        <div class="col-auto p-5">
            <table class="tableBackground table table-borderless">
                <tr>

                    <th scope="col" class="col-auto text-center ml-0 pl-0 border-left-0 mr-0 pr-0 border-right-0 mb-0 pb-0 border-bottom-0">
                    <?php
                        echo "<img src='http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$champions[0][1].".png' class='img-fluid'>";
                    ?>
                    </th>
                    <?php
                    $items = championItems($champions[0][0]);
                    for($i = 0;$i<6;$i++)
                    {
                        $itemInfo = getItemInfo($items[$i][0]);
                        $itemDescription = $itemInfo -> description;
                        $itemName=$itemInfo -> name;
                        echo "<th class='text-center ml-0 pl-0 border-left-0 mb-0 pb-0 border-bottom-0 '>";
                        echo "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' data-placement='top' data-html='true' title='<b>".htmlspecialchars($itemName,ENT_QUOTES)."</b><br /><br />".htmlspecialchars($itemDescription,ENT_QUOTES)."'>";
                        echo "<img src='http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$items[$i][0].".png' alt='champion image' class='img-fluid d-block mb-2 mt-2'>";
                        echo "<span class='h4 text-success'>".$items[$i][3]."%</span>";

                        echo "</th>";
                    }
                    ?>
                </tr>
                <tr class="">
                    <td class="staatFont">
                        <?php
                            $championInfo = getChampionInfo($champions[0][1]);
                            echo "<span class='h4 mb-0 pb-0 border-bottom-0 float-left'>".$championInfo -> name." </span><span class='h4 mb-0 pb-0 border-bottom-0 text-success float-right'>".$champions[0][2]."%</span><br />";
                            echo "<span class='h6 mt-0 pt-0 border-top-0  float-left'>".$championInfo -> title."</span><br />";
                        ?>

                    </td>
                    <td colspan="6" class="rowBackground">

                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row justify-content-center rowBackground staatFont">
        <div class="col-md-auto">
            <table id="dtChampionTable" class="table table-dark table-striped">
                <tr class="tableBackground">
                    <th class="mr-0 pr-0 border-right-0 pl-3" colspan="2">Champions</th>
                    <th class="th-sm text-center border-left-0 ml-0 pl-0"> Winratio </th>
                    <th colspan="6">Item with highest winration</th>
                    <th class="th-sm"> Player with highest winratio </th>
                </tr>
                <?php
                    foreach($champions as $champion){
                        $championInfo = getChampionInfo($champion[1]);
                        echo    "<tr>
                                    <td><img src='http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$champion[1].".png' class='img-fluid mr-0 pr-0 border-right-0' height='42' width='42' alt='picture of champion'></td>
                                    <td class='align-middle'>".$championInfo -> name."</td>
                                    <td class='align-center text-center border-left-0 ml-0 pl-0 ";
                        //IF
                        if($champion[2] > 50) echo "text-success";
                        else if($champion[2] < 50) echo "text-danger";
                        echo        "'>".$champion[2]."%</td>";
                        for($i=0;$i<6;$i++){
                            echo   "<td class='align-middle'></td>";
                        }
                        echo       "<td class='text-right align-middle'>todo</td>
                                </tr>";
                    }
                ?>
            </table>
        </div>

    </div>
    
    
<?php require 'footer.php' ?>