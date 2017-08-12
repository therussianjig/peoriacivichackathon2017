<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '606098532088-bmra96qmv4hbcg6r3llo9116a4d1gm5i.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'Nfuaq9HKrkPXdp-NsME0CPXB'; //Google client secret
$redirectURL = 'https://peoriacivic.000webhostapp.com/home.php'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to CodexWorld.com');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>