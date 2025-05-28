<?php
session_start();
require_once "libraries/utils.php";

session_unset(); // -Détruire toutes les variables de session
session_destroy(); // Détruire la session

redirect('index.php'); // Rediriger vers la page de connexion



