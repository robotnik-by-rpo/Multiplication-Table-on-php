<html>
<head>
    <title>Таблица умножения</title>
</head>
<style>
    table {
    border-collapse: collapse;
    background: white;
    }
    td {
        border: 1px solid red;
        text-align: center;
        padding: 10px 15px;
    }
    .header {
        background: orange;
        font-weight: bold;
    }
    .diag {
        background: yellow;
    }
    .first{
        background: red;
    }

</style>
<body>

<table>
<?php
    echo "<tr>";
    echo "<td class='first'></td>";
    for ($j = 1; $j <= 10; $j++) {
        echo "<td class='header'>$j</td>";
    }
    echo "</tr>";

    for ($i = 1; $i <= 10; $i++) {
        echo "<tr>";
        echo "<td class='header'>$i</td>";

        for ($j = 1; $j <= 10; $j++) {
            $res = $i * $j;
            if ($i == $j){
               echo "<td class = 'diag'>$res</td>";
            } else{
                echo "<td>$res</td>";
            }
           
        }

        echo "</tr>";
    }
?>
</table>

</body>

</html>