<?php
require_once('../Persistencia/DB_Connect.php');

sleep(1);


//Si la busqueda se realiza a traves de la página index
if (isset($_POST['dataSearch'])) {
    $req = '';
    $req = $_POST['dataSearch'];
    $req1 = strtr(utf8_decode($req),
            utf8_decode(
            'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
    $req1 = strtolower($req1);
    $query = "select * from escaperoom where name LIKE '%".$req1."%';";
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        $e->getMessage();
    }
    $result = pg_query($query);

    if (pg_num_rows($result) != 0) {
        while ($fila = pg_fetch_assoc($result)) {
            echo "<div class='data-result'>";
            echo "<h4> <strong>" . $fila['name'] . '</strong></h4>';
            echo "<p>Address: " . $fila['address'] . '</p>';
            echo "<p>Descrip: " . $fila['descrip'] . '</p>';
            echo "<p>Mark: " . $fila['mark'] . '</p>';
            echo "<p>Price: " . $fila['price'] . '</p>';
            echo "<br>";
            echo "</div>";
        }
    }
    close_connection($result, $conn);
}




//si la busqueda se realiza mediante el mapa
if (isset($_POST['mapSearch'])) {

    $query = "select * from escaperoom where name LIKE '%".$_POST['mapSearch']."%';";
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        $e->getMessage();
    }
    $result = pg_query($query);

    if (pg_num_rows($result) != 0) {
        $i=0;
        $room=array();
        while ($lista = pg_fetch_assoc($result)) {
            $room[0]= $lista['name'] .";". $lista['address'] .";". $lista['descrip'];
            $i++;
        }
        echo json_encode($room);
        //echo $room;
        exit();

    }
    close_connection($result, $conn);
}
?>
