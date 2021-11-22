<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        var_dump($_POST);
    ?>
    <form method="POST">
        <input type="hidden" name="id">
        <input type="text" name="nombre" placeholder="Nombre">
        <input type="text" name="apellidos" placeholder="Apellidos">
        <input type="email" name="email" placeholder="corrreo@dominio.com">
        <?php
            //La contraseña no se podrá editar
            //Solo será visible cuando la operación es Agregar
            if(!isset($_GET["id"])){
        ?>
            <input type="password" name="password" placeholder="Contraseña">
        <?php
            }
        ?>
        <button>Aceptar</button>
    </form>
</body>
</html>