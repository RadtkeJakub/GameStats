<?php
require 'header.php';
require 'Class/All.php';
?>
    <script>
        function filterChampions()
        {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("proSearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("prosTable");
            tr = table.getElementsByTagName("tr");

            for(i=0; i < tr.length; i++)
            {
                td = tr[i].getElementsByTagName("td")[0];
                if (td)
                {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1)
                    {
                        tr[i].style.display = "";
                    }
                    else
                    {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
    <div class="row darkBackground justify-content-center">
        <div class="col-10 mt-5">
            <div class="float-md-left float-sm-right">
                <div class="input-group mb-3">
                    <input class="form-control" type="search" placeholder="Search for pros" aria-label="Search" id="proSearch" onkeyup="filterChampions()">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button2"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="d-none d-sm-block text-right">
                <div class="btn-group " role="group" aria-label="First group">
                    <form action="pros.php" method="post" id="role">
                        <input type="hidden" name="role" value="">
                        <input type="image" src="icons/all.png" alt="Submit" class="mr-2"/>
                    </form>
                    <form action="pros.php" method="post" id="role">
                        <input type="hidden" name="role" value="<?php echo TOP ?>">
                        <input type="image" src="icons/top.png" alt="Submit" class="mr-2"/>
                    </form>
                    <form action="pros.php" method="post" id="role">
                        <input type="hidden" name="role" value="<?php echo JUNGLE ?>">
                        <input type="image" src="icons/jungle.png" alt="Submit" class="mr-2"/>
                    </form>
                    <form action="pros.php" method="post" id="role">
                        <input type="hidden" name="role" value="<?php echo MID ?>">
                        <input type="image" src="icons/middle.png" alt="Submit" class="mr-2"/>
                    </form>
                    <form action="pros.php" method="post" id="role">
                        <input type="hidden" name="role" value="<?php echo BOT ?>">
                        <input type="image" src="icons/bottom.png" alt="Submit" class="mr-2"/>
                    </form>
                    <form action="pros.php" method="post" id="role">
                        <input type="hidden" name="role" value="<?php echo SUPP ?>">
                        <input type="image" src="icons/support.png" alt="Submit"/>
                    </form>
                </div>
            </div>
            <table class="table table-borderless table-striped table-hover" id="prosTable">
                <thead>
                <tr>
                    <th>Summoner name</th>
                    <th style="text-align: right" >Win Rate</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $all = new All();
                $pros = $all -> getPros();
                foreach ($pros as $i => $pro)
                {
                    if (isset($_POST['role']) && $_POST['role'] != "")
                    {
                        if($pro[2] != $_POST['role']) continue;
                    }
                    if($pro[2] == TOP || $pro[2] == JUNGLE || $pro[2] == MID || $pro[2] == BOT || $pro[2] == SUPP)
                    {
                        echo "<tr class='tableIcon' onclick=\"window.location='/inzynierkav2/pro.php?pro=".$pro[0]."';\">";
                        echo "<td class='float-left align-middle '><img src='icons/".$pro[2].".png' width='30px' height='30px' alt='pro image' class='image mr-2'> ";
                        echo $pro[1]."</td>";
                        echo "<td class='align-middle ";
                        if($pro[4] > 50) echo "text-success";
                        else if ($pro[4] < 50) echo "text-danger";
                        echo "' style='text-align: right'><span class='mr-2'>".$pro[4]."</span></td>";
                        echo "</tr>";
                    }
                }
                unset($all);
                ?>
                </tbody>
            </table>
        </div>
    </div>






<?php require 'footer.php' ?>