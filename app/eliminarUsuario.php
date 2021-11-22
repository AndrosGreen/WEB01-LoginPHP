<?php
    if(isset($_POST["id"])){
        //Poner código para eliminar
        require_once '../datos/usuarioDao.php';
        $dao=new UsuarioDao();
        $resultado=$dao->eliminar($_POST["id"]);
        echo $resultado;        
        session_start();
        if(!$resultado){
            //Si hubo error al eliminar mostrar un mensaje
            $_SESSION["error"]="No se pudo eliminar el usuario, asegúrese de que este usuario no tiene ventas";
        }else{
            $_SESSION["mensaje"]="Eliminación exitosa";
        }
        
         header("Location:listaUsuarios.php");
         exit();
        
    }else{
        echo "Cargando por primera vez o con actualización forsoza";
    }
?>