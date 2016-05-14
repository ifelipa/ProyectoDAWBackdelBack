<?php
/**
 * PÀGINA PHP ON POSEM TOTES LES FUNCIONS DELS USUARIS
 * User: Jordi Felip i Ismael Felipa
 */
// file where we have the functions required to connect and disconnect to DB //
require_once("DB_Connect.php");
// help us to show errors and warnings //
ini_set('display_errors', 'On');

/**
 * Function that validates a user
 * @param $username
 * @param $password
 * @return bool|int
 */
function validacioUser($username, $password)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    // SQL QUERY TO VERIFY USER //
    $query = "select * from users where username='" . $username . "' and password=md5('" . $password . "');";
    // executem la consulta //
    $result = pg_query($query);
    if ($result && pg_num_rows($result)!=0) {
        // guardem el valor de la consulta
        $camps = pg_fetch_array($result);
        // mirem el valor del camp isAdmin //
        $isAdmin = $camps[5];
        if (pg_num_rows($result) == 1) {
            // cas que sigui admin retornem un 1
            if ($isAdmin == 't') {
                return 1;
            }
            // cas que no sigui admin retornem un 2
            return 2;
        }
        // cas que no s'hagi trobat resultat //
        close_connection($conn, $result);
        return false;
    }
}

/**
 * Function which returns if a particular user is admin or not
 * @param $username
 * @return null
 */
function isAdmin($username)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "select admin from users where username= '$username'";
    $result = pg_query($query);
    if ((pg_num_rows($result)) == 1) {
        //$responde=pg_fetch_array($result);
        close_connection($result, $conn);
        print_r($result);
        return $result;
    }
    close_connection($result, $conn);
    return null;
}

/**
 * Function that close the session of a particular user/admin
 * @return string
 */
function closeSession()
{
    $_SESSION = array();
    session_destroy();
    unset($_SESSION['signUp']);
    
+    $missatge = "Session deleted";
    
    if (isset($_COOKIE['dataActual'])) {
        setcookie('dataActual', '', time() - 3600, "/");
    }
    // mirem si el usuari havia donat el check de recordar //
    if (isset($_COOKIE['loguejat'])) {
        setcookie('loguejat', '', time() - 3600, "/");
        $missatge = "Cookie and session deleted";
    }
    return $missatge;
}

/**
 * Function that adds a user into DB
 * @param $name
 * @param $surname
 * @param $username
 * @param $email
 * @param $admin
 * @param $password
 * @param $zip_code
 * @param $phone_number
 * @param $gender
 * @return string
 */
function addUser($name, $surname, $username, $email, $admin, $password, $zip_code, $phone_number, $gender)
{
    // validamos el username que pasa el usuario para que no se repita
    if (validateUsername($username)) {
        if (validateEmail($email)) { //validamos el email que pasa el usuario para que no este registrado
            try {
                $conn = valid_connection();
            } catch (Exception $e) {
                echo 'Connection Error--> ' . $e->getMessage();
            }
            $cod = $username;
            if ($admin == "isAdmin") {
                $isAdmin = 't';
            } else {
                $isAdmin = 'f';
            }
            if ($gender == "male") {
                $sex = 'M';
            } else {
                $sex = 'F';
            }
            $queryAddUser = "INSERT INTO users (coduser,name,surname,username,email,admin, password,zip_code,phone_number,gender)
            VALUES(
                    lower(translate('$cod','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    lower(translate('$name','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    lower(translate('$surname','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    '$username',
                    lower(translate('$email','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    lower(translate('$isAdmin','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    md5('$password'),
                    lower(translate('$zip_code','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    lower(translate('$phone_number','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    lower(translate('$sex','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ'))
                )";

            if (pg_query($conn, $queryAddUser)) {
                return "0";
            } else {
                return "1";
            }
        } else {
            return "2";
        }
    } else {
        return "3";
    }
}

/**
 * Function that checks if a username exists in our DB
 * @param $username
 * @return bool
 */
function validateUsername($username)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "select * from users where username= '$username'";
    $result = pg_query($query);
    if ((pg_num_rows($result)) != 0) {
        close_connection($result, $conn);
        return false;
    }
    close_connection($result, $conn);
    return true;
}

/*
 * Function that validates a particular email.
 * retorna false si el email ya existe, true si no existe
 */
function validateEmail($email)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "select * from users where email='$email'";
    $result = pg_query($query);
    if ((pg_num_rows($result)) != 0) {
        close_connection($result, $conn);
        return false;
    }
    close_connection($result, $conn);
    return true;
}


/**
 *
 * Function used to delete a simple user
 * @param $username
 * @return int
 */
function deleteUser($username)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "DELETE FROM users where username ='$username';";
    $result = pg_query($query);
    if ((pg_num_rows($result)) != 0) {
        close_connection($result, $conn);
        return 0;
    }
    close_connection($result, $conn);
    return 1;
}

/*
    Function used to delete a admin
*/

function deleteAdmin($username)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "DELETE FROM users where username ='$username';";
    $result = pg_query($query);
    if ((pg_num_rows($result)) != 0) {
        close_connection($result, $conn);
        return 0;
    }
    close_connection($result, $conn);
    return 1;
}

/**
 *
 * Function that edits a user/admin
 * @param $username
 * @param $password
 * @param $email
 * @param $phone_number
 * @param $zip_code
 * @return int
 */
function editUser($username, $password, $email, $phone_number, $zip_code)
{
    try {
        $conn = valid_connection();
    } catch (Exception $e) {
        echo 'Connection Error--> ' . $e->getMessage();
    }
    $query = "UPDATE users  SET password=md5('$password'),
                    email=lower(translate('$email','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    phone_number = lower(translate('$phone_number','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ')),
                    zip_code=lower(translate('$zip_code','áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜñç', 'aeiouAEIOUaeiouAEIOUaeiouAEIOUÑÇ'))
                    WHERE username='$username'";

    $result = pg_query($query);
    if ((pg_num_rows($result)) != 0) {
        close_connection($result, $conn);
        return 0;
    }
    close_connection($result, $conn);
    return 1;
}


/**
 * Function that retrieves the password
 * @param $email
 * @return bool
 */
function retrievesPas($email)
{
    $validacio = validateEmail($email);
    echo $validacio;
    // if email exists //
    if (validateEmail($email) == 0) {
        try {
            $conn = valid_connection();
        } catch (Exception $e) {
            echo 'Connection Error--> ' . $e->getMessage();
        }
        $query = "SELECT * FROM users WHERE email = '$email';";
        $result2 = pg_query($query);
        $user = pg_fetch_row($result2);
        $user_pass = $user[6];
        echo $user_pass . "<br>";
        sendEmail($email, $user_pass);
        return true;
    } else {
        // is not needed to look for a password because user don't exist //
        return false;
    }
}

/**
 * Function that sends an email to the user with a password
 * @param $email
 * @param $pass
 */
function sendEmail($email, $pass)
{
    $text = $pass . "<br>";
    echo '*********' . $email . "<br>";
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    echo "l'email es: " . $email . "<br>";
    $subject = "subject";
    $text = "hola Que tal";
    echo "el subject es: " . $subject . "<br>";
    echo "el text es: " . $text . "<br>";
    echo $headers;
    mail($email, $subject, $text, $headers);
    print phpinfo();
}

?>
