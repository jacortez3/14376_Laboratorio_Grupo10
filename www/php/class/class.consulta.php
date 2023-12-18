<?php
class consulta{
	private $ConsultaID;
	private $PacienteID;
	private $MedicoID;
	private $FechaConsulta;
	private $HoraInicio;
	private $HoraFin;
	private $Diagnostico;
	private $con;
	private $op;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
	private function retrieveOp() {
        // Recuperar el valor de 'op' desde la URL
        // $this->op = isset($_GET['op']) ? $_GET['op'] : '';
    }

    public function getOp() {
        return $this->op;
    }

	public function printOp() {
        // echo "Este es el valor de op: ". $this->op;
    }
		
//*********************** 3.1 METODO update_consulta() **************************************************	
	
	public function update_consulta(){
		$this->ConsultaID = $_POST['id'];
		$this->PacienteID = $_POST['paciente'];
		$this->MedicoID = $_POST['medico'];
		$this->FechaConsulta = $_POST['FechaConsulta'];
		$this->HoraInicio = $_POST['HoraInicio'];
		$this->HoraFin = $_POST['HoraFin'];
		$this->Diagnostico = $_POST['diagnostico'];
		
		
		$sql = "UPDATE consultas SET IdMedico=$this->MedicoID,
									IdPaciente=$this->PacienteID,
									FechaConsulta='$this->FechaConsulta',
									HI='$this->HoraInicio',
									HF='$this->HoraFin',
									Diagnostico='$this->Diagnostico'
				WHERE IdConsulta=$this->ConsultaID;";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}								
										
	}	

//*********************** 3.2 METODO save_consulta() **************************************************	

	public function save_consulta(){
		
		$this->PacienteID = $_POST['paciente'];
		$this->MedicoID = $_POST['medico'];
		$this->FechaConsulta = $_POST['FechaConsulta'];
		$this->HoraInicio = $_POST['HoraInicio'];
		$this->HoraFin = $_POST['HoraFin'];
		$this->Diagnostico = $_POST['diagnostico'];
		 
				// echo "<br> FILES <br>";
				// echo "<pre>";
				// 	print_r($_FILES);
				// echo "</pre>";
		      
		$sql = "INSERT INTO consultas VALUES(NULL,
											$this->MedicoID,
											$this->PacienteID,
											'$this->FechaConsulta',
											'$this->HoraInicio',
											'$this->HoraFin',
											'$this->Diagnostico');";
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
			$this->PacienteID = NULL;
			$this->MedicoID = NULL;
			$this->FechaConsulta = NULL;
			$this->HoraInicio = NULL;
			$this->HoraFin = NULL;
			$this->Diagnostico = NULL;

			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM consultas WHERE IdConsulta=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar la consulta con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
              // ***** TUPLA ENCONTRADA *****
				// echo "<br>TUPLA <br>";
				// echo "<pre>";
				// 	print_r($row);
				// echo "</pre>";
			
				$this->PacienteID = $row['IdPaciente'];
				$this->MedicoID = $row['IdMedico'];
				$this->FechaConsulta = $row['FechaConsulta'];
				$this->HoraInicio = $row['HI'];
				$this->HoraFin = $row['HF'];
				$this->Diagnostico = $row['Diagnostico'];
				
				$flag = "disabled";
				$op = "update";
			}
		}

		$html = '
		<div align="center">
		<form name="consulta" method="POST" action="consultas.php" enctype="multipart/form-data" >
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">


		<div class="col-md-7 col-lg-8">
		<hr>
        <h2 class="mb-3" align="center">Consulta</h2>
		<hr>
        <form class="needs-validation" novalidate>
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Paciente</label>
			  ' . $this->_get_combo_db("pacientes","IdPaciente","Nombre","paciente",$this->PacienteID) . '             	   
            </div>
			
			<div class="col-sm-6">
			<label class="form-label">Medico</label>
				' . $this->_get_combo_db("medicos","IdMedico","Nombre","medico",$this->MedicoID) . '
			</div>

			<div class="col-sm-6">
				<label class="form-label">Fecha Consulta</label>
				<input type="date" name="FechaConsulta" class="form-control" value="' . $this->FechaConsulta . '" />
			</div>

			<div class="col-sm-6">
				<label class="form-label">Hora Inicio</label>
				<input type="time" name="HoraInicio" class="form-control" value="' . $this->HoraInicio . '" />
			</div>

			<div class="col-sm-6">
				<label class="form-label">Hora Fin</label>
				<input type="time" name="HoraFin" class="form-control" value="' . $this->HoraFin . '" />
			</div>

			<div class="col-sm-6">
			<label class="form-label">Diagnostico</label>
			<input type="text" class="form-control" size="15" name="diagnostico" value="' . $this->Diagnostico . '" required>
			<div class="invalid-feedback">
				El Diagnostico es un dato requerido.
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
			<h2 align="center">Lista de Consultas</h2>
			<hr>
			<div class="d-grid">
				<a href="consultas.php?d=' . $d_new_final . '" class="btn btn-primary btn-block" align="center"><button type="button">Nuevo</button></a>
			</div>
				
			<hr>
			<tr>
				<th>Paciente</th>
				<th>Medico</th>
				<th>Especialidad</th>
				<th>Fecha de la Consulta</th>
				<th>Diagnostico</th>
				<th colspan="3">Acciones</th>
			</tr>';
		$sql = "SELECT c.IdConsulta, p.Nombre NombrePaciente, m.Nombre NombreMedico, e.Descripcion Especialidad, c.FechaConsulta, c.Diagnostico 
		FROM consultas c JOIN medicos m ON (c.IdMedico=m.IdMedico) JOIN pacientes p ON (c.IdPaciente=p.IdPaciente) JOIN especialidades e ON (e.IdEsp=m.Especialidad);";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="consultas.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdConsulta'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdConsulta'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdConsulta'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td>' . $row['NombrePaciente'] . '</td>
					<td>' . $row['NombreMedico'] . '</td>
					<td>' . $row['Especialidad'] . '</td>
					<td>' . $row['FechaConsulta'] . '</td>
					<td>' . $row['Diagnostico'] . '</td>
					<td><a href="consultas.php?d=' . $d_del_final . '"><button type="button" class="btn btn-danger">BORRAR</button></a></td>
					<td><a href="consultas.php?d=' . $d_act_final . '"><button type="button" class="btn btn-warning">ACTUALIZAR</button></a></td>
					<td><a href="consultas.php?d=' . $d_det_final . '"><button type="button" class="btn btn-success">DETALLES</button></a></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
	}
	
	public function get_detail_consulta($id){
		$sql = "SELECT p.Nombre AS NombrePaciente, p.Cedula, p.Edad, p.`Peso (kg)`, p.`Estatura (cm)`, m.Nombre AS NombreMedico, e.Descripcion AS Especialidad, c.FechaConsulta, 
		c.HI, c.HF, c.Diagnostico, me.Nombre AS Medicamento, me.Tipo AS GrupoMedicamento FROM pacientes p JOIN consultas c ON (c.IdPaciente = p.IdPaciente) JOIN medicos m 
		ON (c.IdMedico = m.IdMedico) JOIN especialidades e ON (e.IdEsp = m.Especialidad) JOIN recetas r ON (r.IdConsulta = c.IdConsulta) JOIN medicamentos me 
		ON (me.IdMedicamento = r.IdMedicamento) WHERE c.IdConsulta=$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el consulta con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el consulta con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<hr>
					<h2 colspan="2" align="center">DATOS DE LA CONSULTA MEDICA</h2>
				<hr>
				<div class="container text-center">
					<div class="row">
						<div class="col">
							<table border="0" align="center" class="table table-dark table-hover">
								<tr>
									<td>Nombre del Paciente </td>
									<td>'. $row['NombrePaciente'] .'</td>
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
									<td>Peso </td>
									<td>'. $row['Peso (kg)'] .' kg</td>
								</tr>
								<tr>
									<td>Estatura </td>
									<td>'. $row['Estatura (cm)'] .' cm</td>
								</tr>
								<hr>
								<tr>
									<td>Nombre del Medico </td>
									<td>'. $row['NombreMedico'] .'</td>
								</tr>
								<tr>
									<td>Especialidad </td>
									<td>'. $row['Especialidad'] .'</td>
								</tr>
								<tr>
									<td>Fecha de la Consulta </td>
									<td>'. $row['FechaConsulta'] .'</td>
								</tr>
								<tr>
									<td>Hora de Inicio </td>
									<td>'. $row['HI'] .'</td>
								</tr>
								<tr>
									<td>Hora Fin </td>
									<td>'. $row['HF'] .'</td>
								</tr>
								<tr>
									<td>Diagnostico </td>
									<td>'. $row['Diagnostico'] .'</td>
								</tr>	
								<hr>
								<tr>
									<td>Medicamento </td>
									<td>'. $row['Medicamento'] .'</td>
								</tr>
								<tr>
									<td>Tipo de Medicamento </td>
									<td>'. $row['GrupoMedicamento'] .'</td>
								</tr>																													
							</table>
						</div>
				</div>
				
				<div class="d-grid">
					<a href="consultas.php" class="btn btn-primary btn-block" align="center"><button type="button">Regresar</button></a>
				</div>';
				
				return $html;
		}
	}
	
	
	public function delete_consulta($id){
		$sql = "DELETE FROM consultas WHERE IdConsulta=$id;";
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
			<strong>Operacion Fallida!</strong> Error al ' . $tipo . '. Favor contactar a .................... <a href="consultas.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '<hr>
		<div class="alert alert-success">
			<strong>Operacion Exitosa!</strong> El registro se  ' . $tipo . ' correctamente <a href="consultas.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

