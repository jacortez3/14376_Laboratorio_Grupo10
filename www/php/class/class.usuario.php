<?php

class usuario{
	/* Atributos*/

	private $UsuarioID;
	private $RolID;
	private $Usuario;
    private $Contrasena;
    private $Foto;
    private $con;
    private $op;

	
	/*Constructor*/
	function __construct($cn){
		$this->con = $cn;
	}

    public function getIdUsuario(){
        return $this->UsuarioID;
    }

    public function getUsuario(){
        return $this->Usuario;  
    }
    public function getPassword(){
        return $this->Contrasena;
    }

    public function getIdRol(){
        return $this->RolID;
    }

    private function retrieveOp() {
        // Recuperar el valor de 'op' desde la URL
        $this->op = isset($_GET['op']) ? $_GET['op'] : '';
    }

    public function getOp() {
        return $this->op;
    }

	public function printOp() {
        //echo "Este es el valor de op: ". $this->op;
    }

	public function obtenerUsuarios() {
        $sql = "SELECT Nombre, Password, Rol FROM usuarios";
        $res = $this->con->query($sql);
        
        $usuarios = array();
    
        while ($row = $res->fetch_assoc()) {
            $usuario = new usuario($this->con); // No necesitas pasar $this->con aquí
            $usuario->Usuario = $row['Nombre'];
            $usuario->Contrasena = $row['Password'];
            $usuario->RolID = $row['Rol'];
    
            $usuarios[$usuario->Usuario] = $usuario;
        }
        return $usuarios;
    }

    public function validarUsuario($usuario, $clave) {
        $sql = "SELECT * FROM usuarios WHERE Nombre = '$usuario' AND Password = '$clave'";
        $res = $this->con->query($sql);

        if ($res->num_rows > 0) {
            // El usuario y la contraseña son válidos
            $row = $res->fetch_assoc();
            $this->usuario = $row['Nombre'];
            $this->password = $row['Password'];
            $this->RolID = $row['Rol'];

            return true;
        } else {
            // El usuario y la contraseña no son válidos
            return false;
        }
    }
    
    public function update_usuario(){
        $this->UsuarioID = $_POST['id'];
        $this->RolID = $_POST['rol'];
        $this->Contrasena = $_POST['clave'];
        
        
        $sql = "UPDATE usuarios SET Rol =$this->RolID,
                                    Password ='$this->Contrasena'
                WHERE Idusuario =$this->UsuarioID;";
        //echo $sql;
        //exit;
        if($this->con->query($sql)){
            echo $this->_message_ok("modificó");
        }else{
            echo $this->_message_error("al modificar");
        }								
                                        
    }
    
    
    //*********************** 3.2 METODO save_usuario() **************************************************	
    
    public function save_usuario(){
        
        $this->UsuarioID = $_POST['id'];
        $this->RolID = $_POST['rol'];
        $this->Usuario = $_POST['usuario'];
        $this->Contrasena = $_POST['clave'];

        $this->Foto = $this->_get_name_file($_FILES['foto']['name'],12);
		
		
		//exit;
		if(!move_uploaded_file($_FILES['foto']['tmp_name'],PATH_FOTO.$this->Foto)){
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		}
         
        $sql = "INSERT INTO usuarios VALUES(NULL,
                                            '$this->Usuario',
                                            '$this->Contrasena',
                                            $this->RolID,
                                            '$this->Foto');";
        //echo $sql;
        //exit;
        if($this->con->query($sql)){
            echo $this->_message_ok("guardó");
        }else{
            echo $this->_message_error("guardar");
        }								
                                        
    }
    
//*********************** 3.3 METODO _get_name_File() **************************************************	
	
private function _get_name_file($nombre_original, $tamanio){
    $tmp = explode(".",$nombre_original); //Divido el nombre por el punto y guardo en un arreglo
    $numElm = count($tmp); //cuento el número de elemetos del arreglo
    $ext = $tmp[$numElm-1]; //Extraer la última posición del arreglo.
    $cadena = "";
        for($i=1;$i<=$tamanio;$i++){
            $c = rand(65,122);
            if(($c >= 91) && ($c <=96)){
                $c = NULL;
                 $i--;
             }else{
                $cadena .= chr($c);
            }
        }
    return $cadena . "." . $ext;
}

    //*************************************** PARTE I ************************************************************
    
        
     /*Aquí se agregó el parámetro:  $defecto*/
    private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
        $html = '<select name="' . $nombre . '" class="form-select">';
        $sql = "SELECT $valor,$etiqueta FROM $tabla ;";
        $res = $this->con->query($sql);
        while($row = $res->fetch_assoc()){
            //ImpResultQuery($row);
            $html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
        }
    
        $html .= '</select>';
        return $html;
    }
    
    
    //************************************* PARTE II ****************************************************	
    
    public function get_form($id=NULL){
        
        if($id == NULL){
            $this->UsuarioID = NULL;
            $this->RolID = NULL;
            $this->Usuario = NULL;
            $this->Contrasena = NULL;
            $this->Foto = NULL;
    
            $flag = NULL;
            $op = "new";
            
        }else{
    
            $sql = "SELECT * FROM usuarios WHERE Idusuario =$id;";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();
            
            $num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar la usuario con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
            
              // ***** TUPLA ENCONTRADA *****
                // echo "<br>TUPLA <br>";
                // echo "<pre>";
                // 	print_r($row);
                // echo "</pre>";
            
                $this->UsuarioID = $row['IdUsuario'];
                $this->RolID = $row['Nombre'];
                $this->Usuario = $row['Password'];
                $this->Contrasena = $row['Rol'];
                $this->Foto = $row['Foto'];
                
                $flag = "disabled";
                $op = "update";
            }
        }
    
        $html = '
        <div align="center">
        <form name="usuario" method="POST" action="usuarios.php" enctype="multipart/form-data" >
        
        <input type="hidden" name="id" value="' . $id  . '">
        <input type="hidden" name="op" value="' . $op  . '">
    
    
        <div class="col-md-7 col-lg-8">
        <hr>
        <h2 class="mb-3" align="center">usuario</h2>
        <hr>
        <form class="needs-validation" novalidate>
          <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label">Usuarios</label>
                <input type="text" name="usuario" size="15" class="form-control" value="' . $this->Usuario . '" />
                <div class="invalid-feedback">
                    El Usuario es un dato requerido.
                </div>
            </div>
            
            <div class="col-sm-6">
				<label class="form-label">Nombre del Medico</label>
				<input type="text" name="clave" size="15" class="form-control" value="' . $this->Contrasena . '" />
				<div class="invalid-feedback">
				    La Clave es un dato requerido.
			    </div>
			</div>
    
            <div class="col-sm-6">
              <label class="form-label">Rol</label>
			  ' . $this->_get_combo_db("roles","IdRol","Nombre","rol",$this->RolID) . '             
            </div>

            <div class="col-sm-6">
              <label class="form-label">Foto</label>
              <input type="file" class="form-control form-control-sm" name="foto" ' . $flag . '>
            </div>

          <hr class="my-4">
    
          <button class="w-100 btn btn-primary btn-lg" type="submit"  name="Guardar" value="GUARDAR">Guardar</button>
        </form>
      </div>';
        return $html;
    }
    
    
    
    public function get_list(){
        $d_new = "new/0";
        $d_new_final = base64_encode($d_new);
        $html = '
        <table border="0" align="center"  class="table table-striped">
            <hr>
            <h2 align="center">Lista de usuarios</h2>
            <hr>
            <div class="d-grid">
                <a href="usuarios.php?d=' . $d_new_final . '" class="btn btn-primary btn-block" align="center"><button type="button">Nuevo</button></a>
            </div>
                
            <hr>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th colspan="3">Acciones</th>
            </tr>';
        $sql = "SELECT u.IdUsuario, u.Nombre, r.Nombre Rol FROM usuarios u JOIN roles r ON (u.Rol=r.IdRol);";	
        $res = $this->con->query($sql);
        // Sin codificar <td><a href="usuarios.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
        while($row = $res->fetch_assoc()){
            $d_del = "del/" . $row['IdUsuario'];
            $d_del_final = base64_encode($d_del);
            $d_act = "act/" . $row['IdUsuario'];
            $d_act_final = base64_encode($d_act);
            $d_det = "det/" . $row['IdUsuario'];
            $d_det_final = base64_encode($d_det);					
            $html .= '
                <tr>
                    <td>' . $row['Nombre'] . '</td>
                    <td>' . $row['Rol'] . '</td>
                    <td><a href="usuarios.php?d=' . $d_del_final . '"><button type="button" class="btn btn-danger">BORRAR</button></a></td>
                    <td><a href="usuarios.php?d=' . $d_act_final . '"><button type="button" class="btn btn-warning">ACTUALIZAR</button></a></td>
                    <td><a href="usuarios.php?d=' . $d_det_final . '"><button type="button" class="btn btn-success">DETALLES</button></a></td>
                </tr>';
        }
        $html .= '  
        </table>';
        
        return $html;
    }
    
    public function get_detail_usuario($id){
        $sql = "SELECT u.Nombre, r.Nombre Rol, u.Foto FROM usuarios u JOIN roles r ON (u.Rol=r.IdRol) WHERE IdUsuario=$id;";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();
        
        $num = $res->num_rows;
    
        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el usuario con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el usuario con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
                $html = '
                <hr>
                    <h2 colspan="2" align="center">DATOS DEL USUARIO</h2>
                <hr>
                <div class="container text-center">
                    <div class="row">
                        <div class="col">
                            <table border="0" align="center" class="table table-dark table-hover">
                                <tr>
                                    <td>Usuario </td>
                                    <td>'. $row['Nombre'] .'</td>
                                </tr>
                                <tr>
                                    <td>Rol </td>
                                    <td>'. $row['Rol'] .'</td>
                                </tr>																													
                            </table>
                        </div>
                        <div class="col">
						    <img class="rounded-circle" src="'.PATH_FOTO . $row['Foto'] . '" width="100px" align="center"/>
    					</div>
                </div>
                
                <div class="d-grid">
                    <a href="usuarios.php" class="btn btn-primary btn-block" align="center"><button type="button">Regresar</button></a>
                </div>';
                
                return $html;
        }
    }
    
    
    public function delete_usuario($id){
        $sql = "DELETE FROM usuarios WHERE IdUsuario=$id;";
            if($this->con->query($sql)){
            echo $this->_message_ok("ELIMINÓ");
        }else{
            echo $this->_message_error("eliminar");
        }	
    }
    
    //*************************************************************************	
    
    private function _message_error($tipo){
        $html = '
        <div class="alert alert-danger">
            <strong>Operacion Fallida!</strong> Error al ' . $tipo . '. Favor contactar a .................... <a href="usuarios.php" class="alert-link">Regresar</a>.
        </div>';
        return $html;
    }
    
    
    private function _message_ok($tipo){
        $html = '<hr>
        <div class="alert alert-success">
            <strong>Operacion Exitosa!</strong> El registro se  ' . $tipo . ' correctamente <a href="usuarios.php" class="alert-link">Regresar</a>.
        </div>';
        return $html;
    }
}



//****************************************************************************	

 // FIN SCRPIT
?>