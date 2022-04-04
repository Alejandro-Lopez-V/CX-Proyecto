<?php 

    function buscar_rango($inicio, $fin) {
        $inicio = ip2long($inicio);
        $fin = ip2long($fin);
        return array_map('long2ip', range($inicio, $fin) );
    }


    // ping multi ip address
    $iplista = ["192.167.255.255","192.168.0.1"];

    $iplista = buscar_rango($iplista[0],$iplista[1]);
    $totalips = count($iplista);
    $results = [];

    for($i=0; $i<$totalips;$i++){
        $ip = $iplista[$i];
        $ping = exec("ping -n 1 $ip",$salida,$estado);
        $results[] = $estado;
    }

    // Table
    echo '<font face=Lucida Console>';
    echo "<table border=1 style=border-collapse:collapse>
    <th colspan=4> Ping Monitoring </th>
    <tr>
        <td width=20></td>
        <td width=130>IP</td>
        <td width=110>Estado</td>
    </tr>
    ";
    foreach($results as $item => $k){
        echo "<tr>";
        echo "<td>".$item."</td>";
        echo "<td>".$iplista[$item]."</td>";
        if($results[$item]==0){
            echo "<td style=color:green>Conectado</td>";
        } else {
            echo "<td style=color:grey>Desconectado</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
    echo "</font>";
    

    

    


?>

<!DOCTYPE html>
<html>
    <head>
        <title>CX Proyecto</title>
    </head>

    <body>
        <h1>Proyecto Qlero</h1>
        <p>De un profe qlero</p>
    </body>

</html>