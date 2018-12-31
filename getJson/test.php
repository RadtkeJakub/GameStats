<?php
$sql ="    
SELECT
    Items.Item,
    count(Items.Item) as repeats
    count(ItemW.Item) as Wrepeats
    FROM 
    (
        SELECT Item0 AS Item FROM playergame
        UNION ALL
        SELECT Item1 AS Item FROM playergame
        UNION ALL
        SELECT Item2 AS Item FROM playergame
        UNION ALL
        SELECT Item3 AS Item FROM playergame
        UNION ALL
        SELECT Item4 AS Item FROM playergame
        UNION ALL
        SELECT Item5 AS Item FROM playergame
        UNION ALL
        SELECT Item6 AS Item FROM playergame
    ) AS Items 
    INNER JOIN 
    (
        SELECT Item0 AS Item FROM playergame WHERE Win = 1
        UNION ALL
        SELECT Item1 AS Item FROM playergame WHERE Win = 1
        UNION ALL
        SELECT Item2 AS Item FROM playergame WHERE Win = 1
        UNION ALL
        SELECT Item3 AS Item FROM playergame WHERE Win = 1
        UNION ALL
        SELECT Item4 AS Item FROM playergame WHERE Win = 1
        UNION ALL
        SELECT Item5 AS Item FROM playergame WHERE Win = 1
        UNION ALL
        SELECT Item6 AS Item FROM playergame WHERE Win = 1
    )AS ItemW
    ON Items.Item = ItemW.Item
GROUP BY
Items.Item";