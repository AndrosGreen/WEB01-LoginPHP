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

        

        session_start();
        if(isset($_SESSION["error"]) && $_SESSION["error"]){
            echo "<div style='color: red'>{$_SESSION["error"]}</div>";
            unset($_SESSION["error"]);
        }
        if(isset($_SESSION["mensaje"]) && $_SESSION["mensaje"]){
            echo "<div style='color: blue'>{$_SESSION["mensaje"]}</div>";
            unset($_SESSION["mensaje"]);
        }
        require_once '../datos/usuarioDao.php';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // collect value of input field
            $mail = $_POST['correo'];
            $password = $_POST['password'];

            //echo $mail."<br>";
            //echo $password."<br>";

            $dao=new UsuarioDao();
            
            // llamar funcion
            $listaUsuarios = [];
            if( $dao->logUser($mail,$password) ){
                $listaUsuarios=$dao->obtenerTodos();
            }

        }
        
        //$dao=new UsuarioDao();
        //$listaUsuarios=$dao->obtenerTodos();



    ?>
    <a href="registroUsuario.php">Agregar</a>
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Operaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($listaUsuarios as $usuario){
                    echo "<tr><td>".$usuario->apellidos." ".
                    $usuario->nombre."</td><td>$usuario->email</td>
                    <td>
                    <a href='registroUsuario.php?id=$usuario->id'>Editar</a>
                    <form action='eliminarUsuario.php' method='post'>
                        <button name='id' value='$usuario->id'>Eliminar</button>
                    </form>
                    </td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>