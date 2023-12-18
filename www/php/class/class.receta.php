<?php
class receta{
	private $RecetaID;
	private $ConsultaID;
	private $MedicamentoID;
	private $Cantidad;
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
		
//*********************** 3.1 METODO update_receta() **************************************************	
	
	public function update_receta(){
		$this->RecetaID = $_POST['id'];
		$this->ConsultaID = $_POST['consulta'];
		$this->MedicamentoID = $_POST['medicamento'];
		$this->Cantidad = $_POST['cantidad'];
		
		
		$sql = "UPDATE recetas SET IdConsulta =$this->ConsultaID,
									IdMedicamento =$this->MedicamentoID,
									Cantidad='$this->Cantidad'
				WHERE IdReceta =$this->RecetaID;";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}								
										
	}
	

//*********************** 3.2 METODO save_receta() **************************************************	

	public function save_receta(){
		
		$this->ConsultaID = $_POST['consulta'];
		$this->MedicamentoID = $_POST['medicamento'];
		$this->Cantidad = $_POST['cantidad'];
		 
		$sql = "INSERT INTO recetas VALUES(NULL,
											$this->ConsultaID,
											$this->MedicamentoID,
											'$this->Cantidad');";
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
			$this->ConsultaID = NULL;
			$this->MedicamentoID = NULL;
			$this->Cantidad = NULL;

			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM recetas WHERE IdReceta =$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar la receta con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
              // ***** TUPLA ENCONTRADA *****
				// echo "<br>TUPLA <br>";
				// echo "<pre>";
				// 	print_r($row);
				// echo "</pre>";
			
				$this->ConsultaID = $row['IdConsulta'];
				$this->MedicamentoID = $row['IdMedicamento'];
				$this->Cantidad = $row['Cantidad'];
				
				$flag = "disabled";
				$op = "update";
			}
		}

		$html = '
		<div align="center">
		<form name="receta" method="POST" action="recetas.php" enctype="multipart/form-data" >
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">


		<div class="col-md-7 col-lg-8">
		<hr>
        <h2 class="mb-3" align="center">Receta</h2>
		<hr>
        <form class="needs-validation" novalidate>
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Consulta con el medico</label>
			  ' . $this->_get_combo_db("consultas","IdConsulta","IdConsulta","consulta",$this->ConsultaID) . '             
			   
            </div>
			
			<div class="col-sm-6">
			<label class="form-label">Medicamento</label>
				' . $this->_get_combo_db("medicamentos","IdMedicamento","Nombre","medicamento",$this->MedicamentoID) . '
			</div>

			<div class="col-sm-6">
				<label class="form-label">Cantidad</label>
				<input type="number" name="cantidad" class="form-control" min=1 max=12 value="' . $this->Cantidad . '" />
				<div class="invalid-feedback">
					La Cantidad es un dato requerido.
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
			<h2 align="center">Lista de Recetas</h2>
			<hr>
			<div class="d-grid">
				<a href="recetas.php?d=' . $d_new_final . '" class="btn btn-primary btn-block" align="center"><button type="button">Nuevo</button></a>
			</div>
				
			<hr>
			<tr>
				<th>Fecha de la Consulta</th>
				<th>Paciente</th>
				<th>Diagnostico</th>
				<th>Medicamento</th>
				<th colspan="3">Acciones</th>
			</tr>';
		$sql = "SELECT r.IdReceta, c.FechaConsulta, p.Nombre Paciente, c.Diagnostico, m.Nombre Medicamento FROM recetas r JOIN medicamentos m ON (r.IdMedicamento=m.IdMedicamento) 
		JOIN consultas c ON (c.IdConsulta=r.IdConsulta) JOIN pacientes p ON(p.IdPaciente=c.IdPaciente) JOIN medicos me ON (me.IdMedico=c.IdMedico);";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="recetas.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdReceta'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdReceta'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdReceta'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td>' . $row['FechaConsulta'] . '</td>
					<td>' . $row['Paciente'] . '</td>
					<td>' . $row['Diagnostico'] . '</td>
					<td>' . $row['Medicamento'] . '</td>
					<td><a href="recetas.php?d=' . $d_del_final . '"><button type="button" class="btn btn-danger">BORRAR</button></a></td>
					<td><a href="recetas.php?d=' . $d_act_final . '"><button type="button" class="btn btn-warning">ACTUALIZAR</button></a></td>
					<td><a href="recetas.php?d=' . $d_det_final . '"><button type="button" class="btn btn-success">DETALLES</button></a></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
	}
	
	public function get_detail_receta($id){
		$sql = "SELECT c.FechaConsulta, p.Nombre Paciente, me.Nombre Medico, e.Descripcion Especialidad, c.Diagnostico, m.Nombre Medicamento, m.Tipo, r.Cantidad FROM recetas r 
		JOIN medicamentos m ON (r.IdMedicamento=m.IdMedicamento) JOIN consultas c ON (c.IdConsulta =r.IdConsulta) JOIN pacientes p ON (p.IdPaciente=c.IdPaciente) JOIN medicos me 
		ON (me.IdMedico=c.IdMedico) JOIN especialidades e ON (e.IdEsp=me.Especialidad) WHERE IdReceta=$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el receta con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el receta con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<hr>
					<h2 colspan="2" align="center">DATOS DE LA RECETA MEDICA</h2>
				<hr>
				<div class="container text-center">
					<div class="row">
						<div class="col">
							<table border="0" align="center" class="table table-dark table-hover">
								<tr>
									<td>Fecha de la Consulta </td>
									<td>'. $row['FechaConsulta'] .'</td>
								</tr>
								<tr>
									<td>Paciente </td>
									<td>'. $row['Paciente'] .'</td>
								</tr>
								<tr>
									<td>Nombre del Medico </td>
									<td>'. $row['Medico'] .'</td>
								</tr>
								<tr>
									<td>Especialidad </td>
									<td>'. $row['Especialidad'] .'</td>
								</tr>
								<tr>
									<td>Diagnostico </td>
									<td>'. $row['Diagnostico'] .'</td>
								</tr>
								<tr>
									<td>Medicamento </td>
									<td>'. $row['Medicamento'] .'</td>
								</tr>
								<tr>
									<td>Tipo de Medicamento </td>
									<td>'. $row['Tipo'] .'</td>
								</tr>
								<tr>
									<td>Cantidad </td>
									<td>'. $row['Cantidad'] .'</td>
								</tr>																														
							</table>
						</div>
				</div>
				
				<div class="d-grid">
					<a href="recetas.php" class="btn btn-primary btn-block" align="center"><button type="button">Regresar</button></a>
				</div>';
				
				return $html;
		}
	}
	
	
	public function delete_receta($id){
		$sql = "DELETE FROM recetas WHERE IdReceta=$id;";
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
			<strong>Operacion Fallida!</strong> Error al ' . $tipo . '. Favor contactar a .................... <a href="recetas.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '<hr>
		<div class="alert alert-success">
			<strong>Operacion Exitosa!</strong> El registro se  ' . $tipo . ' correctamente <a href="recetas.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

