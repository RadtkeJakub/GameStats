
<!-- JQuery -->
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="js/mdb.min.js"></script>
<!-- MDBootstrap Datatables  -->
<script type="text/javascript" src="js/addons/datatables.min.js"></script>

<!-- Function for tooltips on hover -->
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $(".gamesButton").on("click", function() {
        if($(this).closest("table").find(".collapse.champion").collapse("hide"))
        {
            $(this).closest("table").find(".collapse.champion").collapse("show");
        }
        else
        {
            $(this).closest("table").find(".collapse.champion").collapse("hide");
        }
    });

    $(".championsButton").on("click", function() {
        if($(this).siblings(".collapse").collapse("hide"))
        {
            $(this).siblings(".collapse").collapse("show");
        }
        else
        {
            $(this).siblings(".collapse").collapse("hide");
        }
    });
</script>
</body>

</html>