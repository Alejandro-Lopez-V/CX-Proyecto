<?php
    $dbServername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "cxproyecto";

    $conn = mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);

    $mostrarTabla = false;
    $ipFinal ="";
    $ipInicial = "";
    $bdVacio = true;

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        if(empty($_POST['ip-inicial'])){
            $errorIpInicial = "Por favor introduce una IP inicial";
        } 
        else {
            $ipInicial = $_POST['ip-inicial'];

            if(empty($_POST['ip-final'])){
                $ipFinal = $ipInicial;
            } else {
                $ipFinal = $_POST['ip-final'];
            }
            $mostrarTabla = true;
        } 
    }

    


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitorizacion de IPs</title>

    <link rel="preload" href="css/normalize.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">

    <link rel="preload" href="css/style.css" as="style">
    <link rel="stylesheet" href="css/style.css">
</head>
<body> 


    <header class="header">
        <div class="header__texto">
            <h2 class="no-margin">Monitorizador de Ip</h2>
        </div>
    </header>

    <h2><?php if(isset($errorIpInicial)) echo $errorIpInicial; ?></h2>

    <form action="index.php" class="formulario" method="post">
        <div class="contenedor-campos">
            <div class="campos">
                <label for="">Direccion inicial</label>
                <input class="input-text" type="text" placeholder="192.123.1.0" name="ip-inicial" value="<?php 
                if(empty($ipInicial)){
                    echo "";
                } else {
                    echo $ipInicial;
                }
                
                ?>">
            </div>
            <div class="campos">
                <label for="">Direccion final (opcional)</label>
                <input class="input-text" type="text" placeholder="192.123.1.5" name="ip-final">
            </div>
            <div class="">
                <input class="boton " type="submit" value="Buscar">
            </div>
        </div>
    </form>

    
</body>
</html>
<?php 
    if($mostrarTabla == true){
        function buscar_rango($inicio, $fin) {
            $inicio = ip2long($inicio);
            $fin = ip2long($fin);
            return array_map('long2ip', range($inicio, $fin) );
        }
    
    
        // ping multi ip address
        $iplista = [$ipInicial,$ipFinal];
    
        $iplista = buscar_rango($iplista[0],$iplista[1]);
        $totalips = count($iplista);
    
        for($i=0; $i<$totalips;$i++){
            $ip = $iplista[$i];
            $sql = "INSERT INTO direcciones (ip) VALUES ('$ip')";
            if($conn->query($sql) === TRUE) {}
        }

        // Seleccionando de Base de datos y agregando a $todasIps
        $sql = "SELECT ip FROM direcciones";
        $result = $conn->query($sql);

        $todasIps = [];
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $todasIps[] = $row['ip'];
            }
        }

        $totalips = count($todasIps);
        $results = [];
        for($i=0; $i<$totalips;$i++){
            $ip = $todasIps[$i];
            $ping = exec("ping -n 1 $ip",$salida,$estado);
            $results[] = $estado;
        }
 
    
        // Table
        echo '<font face=Lucida Console>';
        echo "<table class='contenedor table' border=1 style=border-collapse:collapse>
        <th colspan=4> Ping Monitoring </th>
        <tr>
            <td width=30></td>
            <td width=250>IP</td>
            <td width=220>Estado</td>
        </tr>
        ";
        foreach($results as $item => $k){
            echo "<tr>";
            echo "<td class='text-decoration'>".$item."</td>";
            echo "<td class='text-decoration'>".$todasIps[$item]."</td>";
            if($results[$item]==0){
                echo "<td class='text-decoration' style=color:green>Conectado</td>";
            } else {
                echo "<td class='text-decoration' style=color:grey>Desconectado</td>";
            }
            echo "</tr>";
        }
    
        echo "</table>";
        echo "</font>";
    }

    
    

    

    


?>

