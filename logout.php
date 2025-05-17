<?php
session_start();

session_unset(); // -Détruire toutes les variables de session
session_destroy(); // Détruire la session
header("Location: index.php"); // Rediriger vers la page de connexion
exit(); // Terminer le script

require_once 'database/database.php';
