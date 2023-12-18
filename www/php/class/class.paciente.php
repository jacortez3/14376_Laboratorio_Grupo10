<?php
class paciente{
	private $PacienteID;
	private $Usuario;
	private $Nombre;
	private $Cedula;
	private $Edad;
	private $Genero;
	private $Peso;
	private $Estatura;	
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
//*********************** 3.1 METODO update_paciente() **************************************************	
	
	public function update_paciente(){
		$this->PacienteID = $_POST['id'];
		$this->Usuario = $_POST['usuario'];
		$this->Nombre = $_POST['nombre'];
		$this->Cedula = $_POST['cedula'];
		$this->Edad = $_POST['edad'];
		$this->Genero = $_POST['genero'];
		$this->Peso = $_POST['peso'];
		$this->Estatura = $_POST['estatura'];
		

		$sql = "UPDATE pacientes SET IdUsuario=$this->Usuario, 
									Nombre='$this->Nombre',
									Cedula='$this->Cedula',
									Edad='$this->Edad',
									Genero='$this->Genero',
									'Peso (kg)'='$this->Peso',
									'Estatura (cm)'='$this->Estatura'
				WHERE IdPaciente=$this->PacienteID;";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}																
	}
	

//*********************** 3.2 METODO save_paciente() **************************************************	

	public function save_paciente(){
		
		$this->PacienteID = $_POST['id'];
		$this->Usuario = $_POST['usuario'];
		$this->Nombre = $_POST['nombre'];
		$this->Cedula = $_POST['cedula'];
		$this->Edad = $_POST['edad'];
		$this->Genero = $_POST['genero'];
		$this->Peso = $_POST['peso'];
		$this->Estatura = $_POST['estatura'];
		 
				// echo "<br> FILES <br>";
				// echo "<pre>";
				// 	print_r($_FILES);
				// echo "</pre>";
		
		$sql = "INSERT INTO pacientes VALUES(NULL,
											$this->Usuario
											'$this->Nombre',
											'$this->Cedula',
											'$this->Edad',
											'$this->Genero',
											'$this->Estatura',
											'$this->Peso');";
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

	private function _get_radio($arreglo, $nombre, $defecto){
		$html = '';
	
		foreach ($arreglo as $etiqueta) {
			$html .= '<input type="radio" class="form-check-input"  value="' . $etiqueta . '" name="' . $nombre . '" ' . ($defecto == $etiqueta ? 'checked' : '') . '>';
			$html .= '<label class="form-check-label"> '.$etiqueta.'</label><br/>';
		}
		return $html;
		}
	
//************************************* PARTE II ****************************************************	

	public function get_form($id=NULL){
		
		if($id == NULL){
			$this->Usuario = NULL;
			$this->Nombre = NULL;
			$this->Cedula = NULL;
			$this->Edad = NULL;
			$this->Genero = NULL;
			$this->Peso = NULL;
			$this->Estatura = NULL;

			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM pacientes WHERE IdPaciente =$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar el paciente con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
              // ***** TUPLA ENCONTRADA *****
				// echo "<br>TUPLA <br>";
				// echo "<pre>";
				// 	print_r($row);
				// echo "</pre>";
			
				$this->Usuario = $row['IdUsuario'];
				$this->Nombre = $row['Nombre'];
				$this->Cedula = $row['Cedula'];
				$this->Edad = $row['Edad'];
				$this->Genero = $row['Genero'];
				$this->Peso = $row['Peso (kg)'];
				$this->Estatura = $row['Estatura (cm)'];
				
				$flag = "disabled";
				$op = "update";
			}
		}

		$genero = ["Masculino",
					"Femenino"];

		$html = '
		<div align="center">
		<form name="paciente" method="POST" action="pacientes.php" enctype="multipart/form-data" >
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">


		<div class="col-md-7 col-lg-8">
		<hr>
        <h2 class="mb-3" align="center">Paciente</h2>
		<hr>
        <form class="needs-validation" novalidate>
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Nombre del Paciente</label>
			  <input type="text" class="form-control" size="15" name="nombre" value="' . $this->Nombre . '" required>
			<div class="invalid-feedback">
				El Nombre es un dato requerido.
			</div>
            </div>
			
			<div class="col-sm-6">
              <label class="form-label">Usuario</label>
			  ' . $this->_get_combo_db("usuarios","IdUsuario","Nombre","usuario",$this->Usuario) . '             
            </div>


			<div class="col-sm-6">
              <label class="form-label">Cedula</label>
			  <input type="text" class="form-control" size="10" name="cedula" value="' . $this->Cedula . '" required>
			<div class="invalid-feedback">
				La Cedula es un dato requerido.
			</div>
            </div>


			<div class="col-sm-6">
              <label class="form-label">Edad</label>
			  <input type="number" class="form-control" size="15" name="edad" min=1 max=100 value="' . $this->Edad . '" required>
			<div class="invalid-feedback">
				La Edad es un dato requerido.
			</div>
            </div>

			<div class="col-sm-6">
			<label class="form-label">Genero</label>
			<br>
			' . $this->_get_radio($genero, "genero",$this->Genero) . '
			</div>

			<div class="col-sm-6">
              <label class="form-label">Peso</label>
			  <input type="number" class="form-control" size="15" name="peso" min=1 max=300 value="' . $this->Peso . '" required>
			<div class="invalid-feedback">
				El Peso es un dato requerido.
			</div>
            </div>

			<div class="col-sm-6">
              <label class="form-label">Estatura</label>
			  <input type="number" class="form-control" size="15" name="estatura" min=1 max=300 value="' . $this->Estatura . '" required>
			<div class="invalid-feedback">
				La Estatura es un dato requerido.
			</div>
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
			<h2 align="center">Lista de Pacientes</h2>
			<hr>
			<div class="d-grid">
				<a href="pacientes.php?d=' . $d_new_final . '" class="btn btn-primary btn-block" align="center"><button type="button">Nuevo</button></a>
			</div>
				
			<hr>
			<tr>
				<th>Nombre del Paciente</th>
				<th>Edad</th>
				<th>Genero</th>
				<th>Peso</th>
				<th>Estatura</th>
				<th colspan="3">Acciones</th>
			</tr>';
		$sql = "SELECT * FROM pacientes;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="pacientes.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdPaciente'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdPaciente'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdPaciente'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td>' . $row['Nombre'] . '</td>
					<td>' . $row['Edad'] . '</td>
					<td>' . $row['Genero'] . '</td>
					<td>' . $row['Peso (kg)'] . ' kg</td>
					<td>' . $row['Estatura (cm)'] . ' cm</td>
					<td><a href="pacientes.php?d=' . $d_del_final . '"><button type="button" class="btn btn-danger">BORRAR</button></a></td>
					<td><a href="pacientes.php?d=' . $d_act_final . '"><button type="button" class="btn btn-warning">ACTUALIZAR</button></a></td>
					<td><a href="pacientes.php?d=' . $d_det_final . '"><button type="button" class="btn btn-success">DETALLES</button></a></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
	}
	
	public function get_detail_paciente($id){
		$sql = "SELECT p.Nombre, u.Nombre Usuario, p.Edad, p.Genero, p.Cedula, `Peso (kg)` Peso, `Estatura (cm)` Estatura, u.Foto FROM pacientes p JOIN usuarios u 
		ON (p.IdUsuario=u.IdUsuario) WHERE IdPaciente = $id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el paciente con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el paciente con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<hr>
					<h2 colspan="2" align="center">DATOS DEL PACIENTE</h2>
				<hr>
				<div class="container text-center">
					<div class="row">
						<div class="col">
							<table border="0" align="center" class="table table-dark table-hover">
								
								<tr>
									<td>Nombre del Paciente </td>
									<td>'. $row['Nombre'] .'</td>
								</tr>
								<tr>
									<td>Usuario del Paciente </td>
									<td>'. $row['Usuario'] .'</td>
								</tr>
								<tr>
									<td>Cedula del Paciente </td>
									<td>'. $row['Cedula'] .'</td>
								</tr>
								<tr>
									<td>Edad </td>
									<td>'. $row['Edad'] .'</td>
								</tr>
								<tr>
									<td>Genero</td>
									<td>'. $row['Genero'] .'</td>
								</tr>
								<tr>
									<td>Peso</td>
									<td>'. $row['Peso'] .' kg</td>
								</tr>
								<tr>
									<td>Estatura</td>
									<td>'. $row['Estatura'] .' cm</td>
								</tr>																												
							</table>
						</div>
						<div class="col">
						<img class="rounded-circle" src="'.PATH_FOTO . $row['Foto'] . '" width="250px" align="center"/>
    					</div>	
				</div>
				<div class="d-grid">
					<a href="pacientes.php" class="btn btn-primary btn-block" align="center"><button type="button">Regresar</button></a>
				</div>';
				
				return $html;
		}
	}
	
	
	public function delete_paciente($id){
		$sql = "DELETE FROM pacientes WHERE IdPaciente=$id;";
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
			<strong>Operacion Fallida!</strong> Error al ' . $tipo . '. Favor contactar a .................... <a href="pacientes.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '<hr>
		<div class="alert alert-success">
			<strong>Operacion Exitosa!</strong> El registro se  ' . $tipo . ' correctamente <a href="pacientes.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

