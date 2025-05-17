<?php
session_start();
require_once 'libraries/database.php';

// Initialiser la connexion PDO
$pdo = getPdo();


if (isset($_POST['login'])) {
  $errors =[]; 
  if (!empty($_POST['email']) && !empty($_POST['password'])) {

      // -Vérification des informations de connexion
      $query = "SELECT * FROM users
      WHERE (email = :email OR username =:email)";
      $query = $pdo->prepare($query);
      $query->execute([
          'email' => $_POST['email'], 
          'password' => $_POST['password']
      ]);
      $user = $query->fetch();
      // echo"<pre>";
      // print_r($user);
      // echo"<pre>";
      // die();
      
      // --Si les informations de connexion sont correctes, on crée une session et on redirige vers la page d'accueil de l'admin ou l'utilisateur

      if ($user && password_verify($_POST['password'], $user['password'])) {
          $_SESSION['role'] = $user['role'];
          $_SESSION['auth'] = $user;
         

          // -Redirection en fonction du rôle
          switch ($user['role']) {
              case 'admin':
                  header("Location: admin.php");
                  break;

              default:
                  header("Location: user.php");
                  break;
          }
      } else {
          $errors['email'] = "Email ou mot de passe incorrect.";
      }
  }else{
      $errors['login'] = "Tous les champs doivent être remplis.";
  }
}



// 1-On affiche le titre

$pageTitle ="Se connecter dans le Blog"; 

// 2-Debut du tampon de la page de sortie
 
ob_start();

// 3-inclure le layout de la page login
require_once 'layouts/articles/login_html.php';

//4-recuperation du contenu du tampon de la page de login
$pageContent = ob_get_clean();

//5-Inclure le layout de la page de sortie
require_once 'layouts/layout_html.php';

