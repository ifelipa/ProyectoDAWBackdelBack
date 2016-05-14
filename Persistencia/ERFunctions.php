<!--

    PÀGINA PHP ON POSEM TOTES LES FUNCIONS DE LES ESCAPES ROOMS
    #Autor = Jordi Felip i Ismael Felipa
-->

<?php
require_once 'DB_Connect.php';
// fitxer on es troba les funcions per conectar i desconectar de la BBDD //

// ajuda mostrant errors //
ini_set('display_errors', 'On');

/*
    Function that inserts a Escape room of our database 
*/
/*
function addEscapeRoom($name, $address, $descrip, $mark, $price, $duration, $administrator)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $cod = $administrator . "." . str_replace(' ', '', $name);
    $queryAddEscapeRoom = "INSERT INTO escaperoom (coder,name,address,descrip,mark,price, duration,administrator)VALUES('$cod','$name','$address','$descrip','$mark','$price','$duration','$administrator')";
    if (pg_query($conn, $queryAddEscapeRoom)) {
        // cas que hi hagi insercio d'escaperoom //
        return "0";
    } else {
        // cas que no s'hagi executat be la query, no hi ha insercio //
        return "1";
    }
}
*/
function addEscapeRoom($name, $address, $descrip, $mark, $price, $duration, $administrator){
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $cod = $administrator . "." . str_replace(' ', '', $name);
    $queryAddEscapeRoom = "INSERT INTO escaperoom (coder,name,address,descrip,mark,price, duration,administrator)
    VALUES(
            lower(translate('$cod','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
            lower(translate('$name','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
            lower(translate('$address','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
            lower(translate('$descrip','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
            lower(translate('$mark','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
            '$price','$duration',
            lower(translate('$administrator','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ'))
        )";
    if (pg_query($conn, $queryAddEscapeRoom)) {
        // cas que hi hagi insercio d'escaperoom //
        return "0";
    } else {
        // cas que no s'hagi executat be la query, no hi ha insercio //
        return "1";
    }
}

/*
    Function that deletes a Escape room of our database
*/
function deleteEscapeRoom($coder, $admin)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "DELETE FROM escaperoom where coder ='$coder' and administrator = '$admin';";
    $result = null;
    if (pg_query($conn, $query)) {
        close_connection($result, $conn);
        return "0";
    } else {
        close_connection($result, $conn);
        return "1";
    }
}

/*
    Function that should work with both types of users
*/
function editEscapeRoom($coder,$name, $address, $descrip, $mark, $price, $duration, $administrator)
{

    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }    
    $query = "UPDATE escaperoom SET name='$name', address = '$address', descrip='$descrip', mark ='$mark',price ='$price', administrator = '$administrator' WHERE coder='$coder'";
    $result = pg_query($query);
    if ((pg_num_rows($result)) != 0) {
        close_connection($result, $conn);
        return 0;
    }
    close_connection($result, $conn);
    return 1;
}

/*
    List Escape Room de cada Adminstrador
 */
function listEscapeRoom($admin)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "SELECT * FROM escaperoom WHERE administrator='$admin'";
    $result = pg_query($query);
    $room = array();
    if ((pg_num_rows($result)) != 0) {
        $i = 0;
        while ($value = pg_fetch_array($result)) {
            $room[$i] = $value[0] . "-" . $value[1] . "-" . $value[3];
            $i++;
        }

        return $room;
    }
    close_connection($result, $conn);
    return null;
}

/* List Escape Room User*/

function listAllEscapeRoom()
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        $e->getMessage();
    }
    $query = "SELECT * FROM escaperoom";
    $result = pg_query($query);
    $result = pg_query($query);
    $room = array();
    if ((pg_num_rows($result)) != 0) {
        $i = 0;
        while ($value = pg_fetch_array($result)) {
            $room[$i] = $value[0] . "-" . $value[1] . "-" . $value[3] . "-" . $value[2];
            $i++;
        }

        return $room;
    }
    close_connection($result, $conn);
    return null;
}


/*
 * Function which returns the coder of a particular escaperoom
 *
 */
function ercode($escaperoom)
{

    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "SELECT coder FROM escaperoom where name ='$escaperoom';";
    $result = pg_query($query);
    $result = pg_fetch_array($result);
    // we return the code //
    return $result[0];
}

/*
 * Function which returns the price of a particular escaperoom
 *
 */
function erprice($escaperoom) {
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "SELECT price FROM escaperoom where name ='$escaperoom';";
    $result = pg_query($query);
    $result = pg_fetch_array($result);
    // we return the price //
    return $result[0];
}

/*
 * Function which returns the price of a particular parking
 *
 */
function priceparking($codiparking) {
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "SELECT price FROM parking where codparking ='$codiparking';";
    $result = pg_query($query);
    $result = pg_fetch_array($result);
    // we return the price //
    return $result[0];
}

/**
 *
 * Lista de Reservacion x Usuario
 * lista las reservaciones hechas x el usuario
 * @param $user
 * @return array|null
 */
function reservationXuser($user)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        $e->getMessage();
    }
        $query = "SELECT * FROM bookings WHERE coduser = '$user'";
        
        $result = pg_query($query);

    if ((pg_num_rows($result)) != 0) {
        $i = 0;
        while ($value = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
            $room[$i] = $value['coduser'] . "-" . $value['coder'] . "-" . $value['startdate'] . "-" . $value['start'] . "-" . $value['codparking'] . "-" . $value['price'];
            $i++;
        }
        close_connection($result, $conn);
        return $room;
    }
    close_connection($result, $conn);
    return null;
}

/**
 * funcion que retorna los datos de escape room
 * @param $code
 * @return array|null
 */
function returnDataER($code)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }

    $room = array();
    $query = "SELECT * FROM escaperoom WHERE coder = '$code';";
    $result = pg_query($query);
    if ((pg_num_rows($result)) != 0) {
        $i = 0;
        while ($value = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
            $room[$i] = $value['name'] . "-" . $value['address'] . "-" . $value['price'] . "-" . $value['duration'];
            $i++;
        }
        close_connection($result, $conn);
        return $room;
    }
    close_connection($result, $conn);
    return null;
}
/**
 *
 * Lista las reservas de las escaperooms de un admin
 * 
 * @param $user
 * @return array|null
 */
function bookingsERUser($admin)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        $e->getMessage();
    }
        $query = "SELECT * FROM bookings WHERE coder IN (select coder FROM escaperoom where administrator = '$admin')";
        
        $result = pg_query($query);

    if ((pg_num_rows($result)) != 0) {
        $i = 0;
        while ($value = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
            $room[$i] = $value['coduser'] . "-" . $value['coder'] . "-" . $value['startdate'] . "-" . $value['start'] . "-" . $value['codparking'] . "-" . $value['price'];
            $i++;
        }
        close_connection($result, $conn);
        return $room;
    }
    close_connection($result, $conn);
    return null;
}

?>
