<?php
require_once("../Presentacion/index.php");
require('Google/autoload.php');
require_once('Google/Client.php');
require_once('Google/Service/Oauth2.php');

//Creando las claves de la conexion
$client_id = '142362909477-26b7r8fnsjrh02l2b4rot3l4api9dc49.apps.googleusercontent.com';
$client_secret = 'AoOy8WkacguJpZW2ibtljHKQ';
$redirect_uri = 'http://localhost/The_Escape/Presentacion/view/index.php';

//Crea la solicitud del cliente para acceder a la API de Google
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);

//Enviando la solicitud del cliente
$objOAuthService = new Google_Service_Oauth2($client);

//Logout
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['access_token']);
    $client->revokeToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
}

//Authenticate code from Google OAuth Flow
//Add Access Token to Session
if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

//Set Access Token to make Request
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
}

//Get User Data from Google Plus
//If New, Insert to Database
if ($client->getAccessToken()) {
    $userData = $objOAuthService->userinfo->get();
    if (!empty($userData)) {
        $objDBController = new DBController();
        $existing_member = $objDBController->getUserByOAuthId($userData->id);
        if (empty($existing_member)) {
            $objDBController->insertOAuthUser($userData);
        }
    }
    $_SESSION['access_token'] = $client->getAccessToken();
} else {
    $authUrl = $client->createAuthUrl();
}
?>