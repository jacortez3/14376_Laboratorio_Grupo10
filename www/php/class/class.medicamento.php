<?php
class medicamento{
	private $MedicamentoID;
	private $GrupoID;
	private $Descripcion;
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
		
//*********************** 3.1 METODO update_medicamento() **************************************************	
	
	public function update_medicamento(){
		$this->MedicamentoID = $_POST['id'];
		$this->GrupoID = $_POST['grupo'];
		$this->Descripcion = $_POST['descripcion'];
		
		$sql = "UPDATE medicamentos SET Tipo='$this->GrupoID',
									Nombre='$this->Descripcion'
				WHERE IdMedicamento=$this->MedicamentoID;";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}																
	}	

//*********************** 3.2 METODO save_medicamento() **************************************************	

	public function save_medicamento(){
		
		$this->MedicamentoID = $_POST['id'];
		$this->GrupoID = $_POST['grupo'];
		$this->Descripcion = $_POST['descripcion'];
		 
				// echo "<br> FILES <br>";
				// echo "<pre>";
				// 	print_r($_FILES);
				// echo "</pre>";
		      
		$sql = "INSERT INTO medicamentos VALUES(NULL,
											'$this->Descripcion',
											'$this->GrupoID');";
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
			$this->GrupoID = NULL;
			$this->Descripcion = NULL;

			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM medicamentos WHERE IdMedicamento=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar la medicamento con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
              // ***** TUPLA ENCONTRADA *****
				// echo "<br>TUPLA <br>";
				// echo "<pre>";
				// 	print_r($row);
				// echo "</pre>";
			
				$this->GrupoID = $row['Tipo'];
				$this->Descripcion = $row['Nombre'];
				
				$flag = "disabled";
				$op = "update";
			}
		}

		$html = '
		<div align="center">
		<form name="medicamento" method="POST" action="medicamentos.php" enctype="multipart/form-data" >
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">

		<div class="col-md-7 col-lg-8">
		<hr>
        <h2 class="mb-3" align="center">medicamento</h2>
		<hr>
        <form class="needs-validation" novalidate>
          <div class="row g-3">
		  	<div class="col-sm-6">
				<label class="form-label">Nombre del Medicamento</label>
				<input type="text" class="form-control" size="15" name="grupo" value="' . $this->GrupoID . '" required>
				<div class="invalid-feedback">
					El Tipo del Medicamento es un dato requerido.
				</div>
			</div>
			
			<div class="col-sm-6">
			<label class="form-label">Tipo de Medicamento</label>
			<input type="text" class="form-control" size="15" name="descripcion" value="' . $this->Descripcion . '" required>
			<div class="invalid-feedback">
				El Nombre del Medicamento es un dato requerido.
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
			<h2 align="center">Lista de medicamentos</h2>
			<hr>
			<div class="d-grid">
				<a href="medicamentos.php?d=' . $d_new_final . '" class="btn btn-primary btn-block" align="center"><button type="button">Nuevo</button></a>
			</div>
				
			<hr>
			<tr>
				<th>Nombre del Medicamento</th>
				<th>Tipo de Medicamento</th>
				<th colspan="3">Acciones</th>
			</tr>';
		$sql = "SELECT * FROM medicamentos;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="medicamentos.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdMedicamento'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdMedicamento'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdMedicamento'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td>' . $row['Nombre'] . '</td>
					<td>' . $row['Tipo'] . '</td>
					<td><a href="medicamentos.php?d=' . $d_del_final . '"><button type="button" class="btn btn-danger">BORRAR</button></a></td>
					<td><a href="medicamentos.php?d=' . $d_act_final . '"><button type="button" class="btn btn-warning">ACTUALIZAR</button></a></td>
					<td><a href="medicamentos.php?d=' . $d_det_final . '"><button type="button" class="btn btn-success">DETALLES</button></a></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	public function get_detail_medicamento($id){
		$sql = "SELECT * FROM medicamentos WHERE IdMedicamento =$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el medicamento con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el medicamento con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<hr>
					<h2 colspan="2" align="center">DATOS DEL MEDICAMENTO</h2>
				<hr>
				<div class="container text-center">
					<div class="row">
						<div class="col">
							<table border="0" align="center" class="table table-dark table-hover">
								
								<tr>
									<td>Nombre del Medicamento </td>
									<td>'. $row['Nombre'] .'</td>
								</tr>
								<tr>
									<td>Tipo de Medicamento </td>
									<td>'. $row['Tipo'] .'</td>
								</tr>																											
							</table>
						</div>
				</div>
				
				<div class="d-grid">
					<a href="medicamentos.php" class="btn btn-primary btn-block" align="center"><button type="button">Regresar</button></a>
				</div>';
				
				return $html;
		}
	}
	
	public function delete_medicamento($id){
		$sql = "DELETE FROM medicamentos WHERE IdMedicamento=$id;";
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
			<strong>Operacion Fallida!</strong> Error al ' . $tipo . '. Favor contactar a .................... <a href="medicamentos.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '<hr>
		<div class="alert alert-success">
			<strong>Operacion Exitosa!</strong> El registro se  ' . $tipo . ' correctamente <a href="medicamentos.php" class="alert-link">Regresar</a>.
		</div>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

