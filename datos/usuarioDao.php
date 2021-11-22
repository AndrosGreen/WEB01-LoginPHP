<?php
//importa la clase conexión y el modelo para usarlos
require_once 'conexion.php'; 
require_once '../modelo/usuario.php'; 
class UsuarioDao
{
    
	private $conexion; 
    
    /**
     * Permite obtener la conexión a la BD
     */
    private function conectar(){
        try{
			$this->conexion = Conexion::abrirConexion(); 
		}
		catch(Exception $e)
		{
			die($e->getMessage()); /*Si la conexion no se establece se cortara el flujo enviando un mensaje con el error*/
		}
    }
    

    /**
     * 
     */
    public function logUser($email,$password){
        try {
            $this->conectar();
            $cadena = "SELECT email, password from employees where email = '".$email."'";
            $sentenciaSQL = $this->conexion->prepare($cadena);
            $sentenciaSQL->execute();

            $fila = $sentenciaSQL->fetchAll(PDO::FETCH_OBJ);
			
            $usuario = new Usuario();
            foreach($fila as $elem){
                $usuario->email = $elem->email;
                $usuario->contrasenia = $elem->password;
            }

            if($usuario->contrasenia == $password){
                return true;
            }
            else {
                return false;
            }
            
        }
        catch(Exception $e){
            echo $e->getMessage();
			return null;
        }
        finally {
            Conexion::cerrarConexion();
        }
    }

   /**
    * Metodo que obtiene todos los usuarios (empleados) de la base de datos y los
    * retorna como una lista de objetos  
    */
	public function obtenerTodos()
	{
		try
		{
            $this->conectar();
            
			$lista = array();
            /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
			$sentenciaSQL = $this->conexion->prepare("SELECT EmployeeId, FirstName, LastName, email FROM Employees");
			
            //Se ejecuta la sentencia sql, retorna un cursor con todos los elementos
			$sentenciaSQL->execute();
            
            /*Se recorre el cursor para obtener los datos*/
			foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
			{
				$obj = new Usuario();
                $obj->id = $fila->EmployeeId;
	            $obj->nombre = $fila->FirstName;
	            $obj->apellidos = $fila->LastName;
	            $obj->email = $fila->email;
	            
				$lista[] = $obj;
			}
            
			return $lista;
		}
		catch(Exception $e){
			echo $e->getMessage();
			return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}
    
    
	/**
     * Metodo que obtiene un registro de la base de datos, retorna un objeto  
     */
    public function obtenerUno($id)
	{
		try
		{ 
            $this->conectar();
            
            //Almacenará el registro obtenido de la BD
			$registro = null; 
            
			$sentenciaSQL = $this->conexion->prepare("SELECT EmployeeId, FirstName, LastName, Email FROM Employees WHERE EmployeeId=?"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute([$id]);
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
            $registro = new Usuario();
            $registro->id = $fila->EmployeeId;
            $registro->nombre = $fila->FirstName;
            $registro->apellido = $fila->LastName;
            $registro->email = $fila->Email;
           
            return $registro;
		}
		catch(Exception $e){
            echo $e->getMessage();
            return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}
    
    /**
     * Elimina el usuario con el id indicado como parámetro
     */
	public function eliminar($id)
	{
		try 
		{
			$this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare("DELETE FROM Employees WHERE EmployeeId = ?");			          
            //echo 'Armar la sentencia';
			$resultado=$sentenciaSQL->execute(array($id));
			//var_dump($resultado);
            return $resultado;
		} catch (Exception $e) 
		{
			//echo $e->getMessage();
            return false;
		}finally{
            Conexion::cerrarConexion();
        }
		return false;
        
	}

	/**
     * Función para editar al empleado de acuerdo al objeto recibido como parámetro
     */
	public function editar(Usuario $obj)
	{
		try 
		{
			$sql = "UPDATE Employees SET 
                    FirstName = ?,
                    LastName = ?,
                    Email = ?
				    WHERE EmployeeId = ?";

            $this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare($sql);			          
			$sentenciaSQL->execute(
				array(	$obj->nombre,
						$obj->apellido,
						$obj->email,
						$obj->id )
					);
            return true;
		} catch (Exception $e){
			echo $e->getMessage();
			return false;
		}finally{
            Conexion::cerrarConexion();
        }
	}

	
	/**
     * Agrega un nuevo usuario de acuerdo al objeto recibido como parámetro
     */
    public function agregar(Empleado $obj)
	{
        $clave=0;
		try 
		{
            $sql = "INSERT INTO Employees (FirstName, LastName, Email, Password) values(?, ?, ?, ?)";
            
            $this->conectar();
            $this->conexion->prepare($sql)
                 ->execute(
                array($obj->nombre,
						$obj->apellido,
                        $obj->email,
						$obj->contrasenia));
            $clave=$this->conexion->lastInsertId();
            return $clave;
		} catch (Exception $e){
			echo $e->getMessage();
			return $clave;
		}finally{
            /*
            En caso de que se necesite manejar transacciones, no deberá desconectarse mientras la transacción deba persistir
            */
            Conexion::cerrarConexion();
        }
	}
}