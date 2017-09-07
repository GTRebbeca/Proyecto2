<?php
require_once 'config.php';

$auth_url = LOGIN_URI
        . "/services/oauth2/authorize?response_type=code&client_id="
        . CLIENT_ID .  "&client_secret=" . CLIENT_SECRET . "&username=" . USERNAME . "&password=" . CLAVE . "&redirect_uri=" . urlencode(REDIRECT_URI);

header('Location: ' . $auth_url);
?>