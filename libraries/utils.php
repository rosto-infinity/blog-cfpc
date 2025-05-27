<?php
function render(string $path, array $variables =[])
{
  // [
  //   'var1' => 1,
  //   'var2' => 2,
  //   'var3' => 3,
  // ]

  // $var1 = 1,
  // $var2 =2,
  // $var3 = 3,
extract($variables);
ob_start();
require_once "layouts/" .$path."_html.php";
$pageContent = ob_get_clean();
require_once 'layouts/layout_html.php';
}
function redirect(string $path){
  header("Location: $path"); // Rediriger vers la page de connexion
  exit(); // Terminer le script
}
