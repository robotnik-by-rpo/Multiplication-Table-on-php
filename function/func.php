<?php
    function getTableMul(): string {
        $html = "<table>";

        $html .= "<tr>";
        $html .= "<td class='first'></td>";
        for ($j = 1; $j <= 10; $j++) {
            $html .= "<td class='header'>$j</td>";
        }
        $html .= "</tr>";

        for ($i = 1; $i <= 10; $i++) {
            $html .= "<tr>";
            $html .= "<td class='header'>$i</td>";

            for ($j = 1; $j <= 10; $j++) {
                $res = $i * $j;
                if ($i == $j) {
                    $html .= "<td class='diag'>$res</td>";
                } else {
                    $html .= "<td>$res</td>";
                }
            }
            $html .= "</tr>"; 
        }

        $html .= "</table>";
        return $html;
    }
?>