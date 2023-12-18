<?php
class medico{
	private $MedicoID;
	private $UsuarioID;
	private $Nombre;
	private $Especialidad;
	private $con;
	private $op;

	function __construct($cn){
		$this->con = $cn;
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
		
//*********************** 3.1 METODO update_medico() **************************************************	
	
	public function update_medico(){
		$this->MedicoID = $_POST['id'];
		$this->UsuarioID = $_POST['usuario'];
		$this->Nombre = $_POST['nombre'];
		$this->Especialidad = $_POST['especialidad'];
			
		$sql = "UPDATE medicos SET IdUsuario =$this->UsuarioID,
									Nombre='$this->Nombre',
									Especialidad =$this->Especialidad
				WHERE IdMedico =$this->MedicoID;";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}													
	}
	

//*********************** 3.2 METODO save_medico() **************************************************	

	public function save_medico(){
		
		$this->UsuarioID = $_POST['usuario'];
		$this->Nombre = $_POST['nombre'];
		$this->Especialidad = $_POST['especialidad'];
		 
				// echo "<br> FILES <br>";
				// echo "<pre>";
				// 	print_r($_FILES);
				// echo "</pre>";
		      
		$sql = "INSERT INTO medicos VALUES(NULL,
											'$this->Nombre',
											$this->Especialidad,
											$this->UsuarioID);";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("guardó");
		}else{
			echo $this->_message_error("guardar");
		}																	
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
			$this->Nombre = NULL;
			$this->Especialidad = NULL;

			$op = "new";
			
		}else{
			$sql = "SELECT * FROM medicos WHERE IdMedico =$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar la medico con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
              // ***** TUPLA ENCONTRADA *****
				// echo "<br>TUPLA <br>";
				// echo "<pre>";
				// 	print_r($row);
				// echo "</pre>";
				
				$this->UsuarioID = $row['IdUsuario'];
				$this->Nombre = $row['Nombre'];
				$this->Especialidad = $row['Especialidad'];
				
				$flag = "disabled";
				$op = "update";
			}
		}

		$html = '
		<div align="center">
		<form name="medico" method="POST" action="medicos.php" enctype="multipart/form-data" >
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">

		<div class="col-md-7 col-lg-8">
		<hr>
        <h2 class="mb-3" align="center">Medico</h2>
		<hr>
        <form class="needs-validation" novalidate>
          <div class="row g-3">
			<div class="col-sm-6">
				<label class="form-label">Nombre del Medico</label>
				<input type="text" name="nombre" size="15" class="form-control" value="' . $this->Nombre . '" />
				<div class="invalid-feedback">
				El Nombre es un dato requerido.
			</div>
			</div>

			<div class="col-sm-6">
              <label class="form-label">Usuario</label>
			  ' . $this->_get_combo_db("usuarios","IdUsuario","Nombre","usuario",$this->UsuarioID) . '             
            </div>

			<div class="col-sm-6">
              <label class="form-label">Especialidad</label>
			  ' . $this->_get_combo_db("especialidades","IdEsp","Descripcion","especialidad",$this->Especialidad) . '             
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
			<h2 align="center">Lista de Medicos</h2>
			<hr>
			<div class="d-grid">
				<a href="medicos.php?d=' . $d_new_final . '" class="btn btn-primary btn-block" align="center"><button type="button">Nuevo</button></a>
			</div>
				
			<hr>
			<tr>
				<th>Medico</th>
				<th>Usuario</th>
				<th>Especialidad</th>
				<th colspan="3">Acciones</th>
			</tr>';
		$sql = "SELECT m.IdMedico, m.Nombre, u.Nombre Usuario, e.Descripcion Especialidad FROM medicos m JOIN especialidades e ON (m.Especialidad=e.IdEsp) JOIN usuarios u 
		ON (u.IdUsuario=m.IdUsuario);";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="medicos.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdMedico'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdMedico'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdMedico'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td>' . $row['Nombre'] . '</td>
					<td>' . $row['Usuario'] . '</td>
					<td>' . $row['Especialidad'] . '</td>
					<td><a href="medicos.php?d=' . $d_del_final . '"><button type="button" class="btn btn-danger">BORRAR</button></a></td>
					<td><a href="medicos.php?d=' . $d_act_final . '"><button type="button" class="btn btn-warning">ACTUALIZAR</button></a></td>
					<td><a href="medicos.php?d=' . $d_det_final . '"><button type="button" class="btn btn-success">DETALLES</button></a></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
	}
	
	public function get_detail_medico($id){
		$sql = "SELECT m.Nombre, u.Nombre Usuario, e.Descripcion Especialidad,u.Foto FROM medicos m JOIN especialidades e ON (m.Especialidad=e.IdEsp) JOIN usuarios u 
		ON (u.IdUsuario=m.IdUsuario) WHERE m.IdMedico = $id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el medico con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el medico con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<hr>
					<h2 colspan="2" align="center">DATOS DEL MEDICO</h2>
				<hr>
				<div class="container text-center">
					<div class="row">
						<div class="col">
							<table border="0" align="center" class="table table-dark table-hover">

								<tr>
									<td>Nombre del Medico </td>
									<td>'. $row['Nombre'] .'</td>
								</tr>
								<tr>
									<td>Usuario del Medico </td>
									<td>'. $row['Usuario'] .'</td>
								</tr>
								<tr>
									<td>Especialidad </td>
									<td>'. $row['Especialidad'] .'</td>
								</tr>																												
							</table>
						</div>
						<div class="col">
						    <img class="rounded-circle" src="'.PATH_FOTO .$row['Foto'] . '" width="100px" align="center"/>
    					</div>
				</div>
				
				<div class="d-grid">
					<a href="medicos.php" class="btn btn-primary btn-block" align="center"><button type="button">Regresar</button></a>
				</div>';
				
				return $html;
		}
	}
	
	
	public function delete_medico($id){
		$sql = "DELETE FROM medicos WHERE IdMedico=$id;";
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
			<strong>Operacion Fallida!</strong> Error al ' . $tipo . '. Favor contactar a .................... <a href="medicos.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '<hr>
		<div class="alert alert-success">
			<strong>Operacion Exitosa!</strong> El registro se  ' . $tipo . ' correctamente <a href="medicos.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

