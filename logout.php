<?php
session_start();
require_once 'libraries/utils.php';
session_unset(); // -Détruire toutes les variables de session
session_destroy(); // Détruire la session
// header("Location: index.php"); // Rediriger vers la page de connexion
// exit(); // Terminer le script
redirect("index.php");

