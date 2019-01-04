<?php
    require 'header.php';
    require 'mainSql.php';
    error_reporting(0);
    ini_set('display_errors', 0);
?>

    

    <div class="row staatFont rowBackground pt-5 pb-5">

        <div class="col-auto p-5">

        </div>
    </div>

    <div class="row justify-content-center rowBackground staatFont pt-5">
        <div class="col-10">
            <table id="dtChampionTable" class="table table-dark table-striped">
                <tr class="tableBackground ">
                    <th class="mr-0 pr-0 border-right-0 pl-3" colspan="2">Champions</th>
                    <th class="text-center border-left-0 ml-0 pl-0"> Winratio </th>
                    <th colspan="6" class="text-center">Item with highest winration</th>
                    <th class="text-right"> Player with highest winratio </th>
                </tr>
                <?php
                    foreach($champions as $champion){
                        $championInfo = getChampionInfo($champion[1]);
                        echo    "<tr>
                                    <td class='align-middle'><img src='http://ddragon.leagueoflegends.com/cdn/8.24.1/img/champion/".$champion[1].".png' class='img-fluid mr-0 pr-0 border-right-0' height='60' width='60' alt='picture of champion'></td>
                                    <td class='align-middle'>".$championInfo -> name."</td>
                                    <td class='align-center align-middle text-center border-left-0 ml-0 pl-0 ";
                        //IF WINRATIO IS LESS THAN 50 TEXT-COLOR = RED IF ITS MORE THAN 50 COLOR TEXT GREEN
                        if($champion[2] > 50) echo "text-success";
                        else if($champion[2] < 50) echo "text-danger";
                        echo        "'>".$champion[2]."%</td>";
                        $items = championItems($champion[0]);
                        for($i = 0;$i<6;$i++) {
                            $itemInfo = getItemInfo($items[$i][0]);
                            $itemDescription = $itemInfo->description;
                            $itemName = $itemInfo->name;

                            echo   "<td class='align-middle text-center'><img src='http://ddragon.leagueoflegends.com/cdn/8.24.1/img/item/".$items[$i][0].".png' alt='champion image' class='img-fluid d-block mb-2 mt-2 align-middle' height='42' width='42'></td>";
                        }
                        echo       "<td class='text-right align-middle'>todo</td>
                                </tr>";
                    }
                ?>
            </table>
        </div>

    </div>
    
    
<?php require 'footer.php' ?>