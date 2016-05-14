<?php
//comprueba que la session este iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
//date_default_timezone_set('Europe/Madrid');
require($_SERVER['DOCUMENT_ROOT'] . '/The_Escape/Persistencia/ERFunctions.php');

//echo 'directorio1 ' . __DIR__;
//echo 'directorio2 ' . $_SERVER['DOCUMENT_ROOT'];


/**
 * Function which list all escape rooms data from a particular admin 
 * @param $userAdmin
 * @return array|null
 */
function listDataEscapeRoom($userAdmin) {
    if (listEscapeRoom($userAdmin) != null) {
        return listEscapeRoom($userAdmin);
    } else {
        echo "Empty list";
    }
}
/**
 * Function which return all the escape rooms of the database
 * @return array|null
 */
function listDataEscapeRoomUser() {
    if (listAllEscapeRoom() != null) {
        return listAllEscapeRoom();
    } else {
        echo "Empty list";
    }
}
/**
 * UNDEFINED
 */
function dataEscapeRoom($codeER) {
    if (reservationXuser($codeER) != null) {
        return reservationXuser($codeER);
    } else {
        return false;
    }
}
/**
 * UNDEFINED
 */
function hasBooking($username) {
    if (reservationXuser($username) != null) {
        return reservationXuser($username);
    } else {
        return false;
    }
}
/*
*   Retorna reservas hechas en las escape rooms de un cierto admin 
*
*/
function bookingsERAdmin($admin){
    if (bookingsERUser($admin) != null) {
        return bookingsERUser($admin);
    } else {
        return false;
    }
}
?>
