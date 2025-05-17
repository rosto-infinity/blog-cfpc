
<?php
require_once 'libraries/database.php';
require_once 'libraries/utils.php';

$pdo = getPdo();

// 1--tttOn affiche le titre autre

$pageTitle ='Page User'; 

// 2---Debut du tampon de la page de sortie
 
ob_start();

// 3--inclure le layout de la page user
require_once 'layouts/usersefffdtgggg/user_dashboardffffffffrr_html.php';

//4---recuperation du contenu du tampon de la page user
$pageContent = ob_get_clean();

//5----Inclure le layout de la page de sortie
require_once 'layouts/layout_html.php';
