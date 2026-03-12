<?php
    function getTableMul(int $x = 10, int $y = 10): string {
        $html = "<table>";

        $html .= "<tr>";
        $html .= "<td class='first'></td>";
        for ($j = 1; $j <= $y; $j++) {
            $html .= "<td class='header'>$j</td>";
        }
        $html .= "</tr>";

        for ($i = 1; $i <= $y; $i++) {
            $html .= "<tr>";
            $html .= "<td class='header'>$i</td>";

            for ($j = 1; $j <= $x; $j++) {
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