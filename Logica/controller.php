<!--
    PÀGINA PHP QUE UTILITZEM DE CONTROLADOR
    #Autor = Ismael Felipa i Jordi Felip

    nota google clave
    id cliente: 142362909477-26b7r8fnsjrh02l2b4rot3l4api9dc49.apps.googleusercontent.com
    numero secreto clinete : AoOy8WkacguJpZW2ibtljHKQ
-->


<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (session_status() != PHP_SESSION_ACTIVE) {session_start();}
// arxiu on es troben les funcions relacionades amb l'usuari //
require '../Persistencia/userFunctions.php';

// arxiu on es troben les funcions relacionades amb l'Escape Room //
require '../Persistencia/ERFunctions.php';
// del formulari d'entrada venim al controller //


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // cas que haguem donat al boto del formulari d'entrada //
    if (isset($_POST['boto'])) {
        // agafem els valors de la cookie i els guardem en una sessió
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        // guardem aquests valors en unes variables per utilitzarlos més endavant i gestionar la entrada del usuari dins del sistema del videoclub //
        $username = $_SESSION['username'];
        $passwordUser = $_SESSION['password'];
        //Validar el inicio de session
        $username = htmlentities($username, ENT_QUOTES | ENT_IGNORE, "UTF-8");
        $passwordUser = htmlentities($passwordUser, ENT_QUOTES | ENT_IGNORE, "UTF-8");
        // fem la query -> funcio de l'usuari//
        $validacio = validacioUser($username, $passwordUser);
        // cas que l'usuari sigui o no admin //
        if ($validacio == 1 || $validacio == 2) {

            //variable que asigna que el usuario esta logueado
            $_SESSION['signUp'] = 1;

            // si esta activat creeem una cookie amb el nom del username //
            if (isset($_POST['Recordam'])) {
                setcookie("loguejat", $username, time() + 3600, "/");
                echo "cookie should have been made";
            }
            date_default_timezone_set('UTC');
            // guardem la constant que representa la data actual quan es connecta el user //
            $dataActual = date(DATE_RFC2822);
            setcookie('dataActual', $dataActual, time() + 3600, "/");
            setcookie('signUp', '1', time() + 3600, "/");


            if ($validacio == 1) {
                // redirigim la pagina a la d'administrador //
                $_SESSION['isAdmin'] = true;
                header("Location: ../Presentacion/view/index.php");
            } else if ($validacio == 2) {
                // redirigim la pagina a la d'usuari //
                $_SESSION['isAdmin'] = false;
                header("Location: ../Presentacion/view/index.php");
            }
        } else {
            echo "User not found";
            header("Location: ../Presentacion/");
        }
    }


    if (isset($_COOKIE['signUp']) && $_COOKIE['signUp'] == "1") {
        // implementacio log out //
        if (isset($_POST['log_out'])) {
            $missatge = closeSession();
            echo $missatge;
            // tornem a la pagina principal de l'aplicacio //
            header("refresh:1; url=../Presentacion/");
            exit();
        }
        // implementacio nou usuari //
        // boto dins del modal //
        if (isset($_POST['create'])) {
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $zip_code = $_POST['zip_code'];
            $phone_number = $_POST['phone'];
            $admin = $_POST['isAdmin'];
            $gender = $_POST['gender'];
            $userAdd = addUser($name, $surname, $username, $email, $admin, $password, $zip_code, $phone_number, $gender);
            if ($userAdd == "3") {
                echo "Username exists";
            } elseif ($userAdd == "2") {
                echo "Email already exists";
            } elseif ($userAdd == "1") {
                echo "User not created";
            } elseif ($userAdd == "0") {
                echo "User created";
            }
            //tornem a la pagina principal de l'aplicacio //
            header("refresh:1; url=../Presentacion/");
        }

        if (isset($_POST['DeleteAccountUser'])) {
            $username = $_SESSION['username'];
            $deleteUser = deleteUser($username);
            if ($deleteUser == "1") {
                // tornem a la pagina principal //
                echo "We are removing your account";
                closeSession();
                header("refresh:1;url=../Presentacion/");
            } else {
                // cas que no puguem borrar l'usuari //
                echo "We can't remove your account";
                header("refresh:1;url=../Presentacion/view/settings.php");
            }
        }
        if (isset($_POST['DeleteAccountAdmin'])) {
            $username = $_SESSION['username'];
            if (deleteUser($username) == "1") {
                // tornem a la pagina principal //
                echo "We are removing your account";
                closeSession();
                header("refresh:1;url=../Presentacion/");
            } else {
                // cas que no puguem borrar l'usuari //
                echo "We can't remove your account";
                header("refresh:1;url=../Presentacion/view/settings.php");
            }
        }

        if (isset($_POST['editUser'])) {
            echo "Hem clicat el boto d'editar";
            $username = $_SESSION['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $zip_code = $_POST['zip_code'];
            $phone_number = $_POST['phone'];
            $editUser = editUser($username, $password, $email, $phone_number, $zip_code);
            if ($editUser == "1") {
                // tornem a la pagina principal //
                echo "You have modified correctly your account";
                header("refresh:1;url=../Presentacion/view/profile.php");
            } else {
                // cas que no puguem borrar l'usuari //
                echo "We are not able to modify your account";
                header("refresh:1;url=../Presentacion/view/settings.php");
            }
        }


        /*recordar contraseña*/
        if (isset($_POST['send_password'])) {
            echo "recordar passs";
            $email2 = $_POST['email_remenber'];
            echo $email2 . "<br>";
            $emailUser = retrievesPas($email2);
            echo $emailUser;
        }


        /**CONTROLLER DE SEARCH*/
        if (isset($_POST['searchBar'])) {
            // agafem el que hem introduit //
            $solicitut = $_POST['input-search'];
            // ho imprimim //
            $response = "";
            echo "<br>" . $solicitut . "<br>";
            if (searchER($solicitut) != null) {
                $response = searchER($solicitut);
            } else {
                echo "We have not found any matches";
                header("refresh:1;url=../Presentacion/");
            }
        }


        // cas que l'admin vulgui afegir un ER //
        if (isset($_POST['AddER'])) {
            echo "Añadir Escape Room";
            $name = $_POST['name'];
            $address = $_POST['address'];
            $descrip = $_POST['descrip'];
            $mark = $_POST['mark'];
            $price = $_POST['price'];
            $duration = $_POST['duration'];
            $administrator = $_SESSION['username'];
            $ERAdd = addEscapeRoom($name, $address, $descrip, $mark, $price, $duration, $administrator);
            if ($ERAdd == "0") {
                echo "Escape room added";
                header("refresh:1;url=../Presentacion/view/erAdmin.php");
            } elseif ($userAdd == "1") {
                echo "Escape room not added";
            }
        }
        // cas que l'admin vulgui borrar una ER //
        if (isset($_POST['DelER'])) {
            $administrator = $_SESSION['username'];
            $code = $_POST['cod_er'];
            if (deleteEscapeRoom($code, $administrator) == "0") {
                echo "Escape room remove";
                header("refresh:1;url=../Presentacion/view/erAdmin.php");
            } elseif ($userAdd == "1") {
                echo "Escape room not remove";
            }
        }
        // cas que es vulgui reservar una ER //
        if (isset($_POST['reserveER'])) {
            // coduser //
            $user = $_SESSION['username'];
            // escape room name //
            $escapeRoom = $_POST['multiple'];
            $coder = ercode($escapeRoom);
            // echo "Escape room code: " . $coder . "<br>";
            $startdate = $_POST['date'];
            $start = $_POST['time'];
            $people = $_POST['people'];
            // we get the price of the selected escape room //
            $price = erprice($escapeRoom);
            // cas que no s'hagi seleccionat el checkbox del parking //
            if ($_POST['parking'] == null) {
                // els altres camps de parking estan a null a la bbdd //
                //aquest hauria de ser el preu Total //
                $price = $price * $people;
                //coduser | coder | startdate | start | finish | people | codparking | price
                $queryBooking = "INSERT INTO bookings (coduser,coder,startdate,start,finish,people,codparking,price)
                                  VALUES('$user','$coder','$startdate','$start',null,'$people',0,'$price')";
                $result = pg_query($queryBooking);
                if (!$result) {
                    echo "An error occurred.\n";
                    header("refresh:1;url=../Presentacion/view/erUser.php");
                } else {
                    echo "<br>RESERVATION DATA<br>";
                    echo "User: " . $user . "<br>";
                    echo "Escape room: " . $escapeRoom . "<br>";
                    echo "Book date: " . $startdate . "<br>";
                    echo "Book hour: " . $start . "<br>";
                    echo "Number of people: " . $people . "<br>";
                    echo "Total price: " . $price;
                    header("refresh:4;url=../Presentacion/view/erUser.php");
                }
            } else {
                $codiparking = $_POST['codparking'];
                $places = $_POST['places'];
                // we get the price of a place in that parking //
                $priceparking = priceparking($codiparking);
                // total price //
                $price = $price * $people + ($priceparking * $places);

                $queryBooking = "INSERT INTO bookings (coduser,coder,startdate,start,finish,people,codparking,price)
                                  VALUES('$user','$coder','$startdate','$start',null,'$people',$codiparking,'$price')";
                $result = pg_query($queryBooking);
                if (!$result) {
                    echo "An error occurred.\n";
                    header("refresh:1;url=../Presentacion/view/erUser.php");
                } else {
                    echo "<br>RESERVATION DATA<br>";
                    echo "User: " . $user . "<br>";
                    echo "Escape room: " . $escapeRoom . "<br>";
                    echo "Book date: " . $startdate . "<br>";
                    echo "Book hour: " . $start . "<br>";
                    echo "Number of people: " . $people . "<br>";
                    echo "Parking code: " . $codiparking . "<br>";
                    echo "Nº of parking places: " . $places . "<br>";
                    echo "Total price: " . $price . "<br>";
                    header("refresh:4;url=../Presentacion/view/erUser.php");
                }
            }
        }
        // cas que es vulgui modificar una ER //
        if (isset($_POST['ModER'])) {
            echo "Hem clicat el boto d'editar ER";
            $admin = $_SESSION['username'];
            echo "<br>Actual admin: " . $admin . "<br>";
            $ERName = $_POST['name'];
            echo "ER Name: " . $ERName . "<br>";
            $coder = $admin . "." . $ERName;
            echo "El codigo de escape room es: " . $coder;
            $codERSelect = ercode($_POST['list_er_modify']);
            $address = $_POST['address'];
            $descrip = $_POST['descrip'];
            $mark = $_POST['mark'];
            $price = $_POST['price'];
            $duration = $_POST['duration'];
            $admin = $_SESSION['username'];
            $modificacionER = editEscapeRoom($codERSelect, $ERName, $address, $descrip, $mark, $price, $duration, $admin);
            if ($modificacionER == "1") {
                // tornem a la pagina principal //
                echo "You have modified correctly your escape room";
                header("refresh:1;url=../Presentacion/view/erAdmin.php");
            } else {
                // cas que no puguem borrar l'usuari //
                echo "We are not able to modify your escape room";
                header("refresh:1;url=../Presentacion/view/erAdmin.php");
            }
        }
    } else {

        header("refresh:1; url = ../Presentacion/");
    }//control de cookie y session iniciada


}//post general

if (isset($_POST['dataParking'])) {
    $req = $_POST['dataParking'];
    $codER = ercode($req);
    $query = "select name from parking where codparking in (select codparking from erxparking where coder='$codER');";
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        $e->getMessage();
    }
    $result = pg_query($query);
    //$fila = pg_fetch_assoc($result);
    if (pg_num_rows($result) != 0) {
        while ($fila = pg_fetch_array($result)) {
            echo "<option value=''> " . $fila[0] . "</option>";
        }
    }
}



?>
