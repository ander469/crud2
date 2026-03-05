 <?php
    require_once '../crud2/config/app.php';
    require_once './autoload.php';
    require_once"./app/views/inc/session_start.php";

    if (isset($_GET['views'])) {
        $url=explode("/", $_GET['views']);
    }else {
        $url=["login"];
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        require_once"./app/views/inc/head.php";
     ?>
</head>
<body>
     <?php
        use app\controllers\viewsController;
        $viewsController= new viewsController();
        $vistas=$viewsController->obtenerVistasControlador($url[0]);

        if ($vistas=="login" || $vistas=="404") {
            require_once"./app/views/content/".$vistas."-view.php";
        } else {

        require_once"./app/views/inc/navbar.php";
        require_once $vistas;
        }
        
        
        require_once"./app/views/inc/scrip.php";
     ?>
</body> 
</html>
