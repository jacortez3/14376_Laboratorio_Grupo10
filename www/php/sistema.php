<!DOCTYPE html>
<html lang="en">

<head>
	<title>Consultas Veris</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	

	<!-- Bootstrap core CSS -->
	<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  
   <!-- Main Stylesheet File -->

	<link href="../css/main.css" rel="stylesheet">
            
</head>

<?php
	  		require_once("class/constantes.php");
			require_once("class/class.usuario.php");
			include_once("class/class.consulta.php");

			session_start();
			$cn = conectar();
			
			// Obtén el nombre de usuario y los parámetros de la URL si existen
			$user = $_SESSION['listaUser'];
			if (isset($_POST['op'])){
				$dato = $_GET['op'];
					$tmp = explode("/", $dato);

					$op = $tmp[0];
					$rol = $tmp[1];					
					$obj = $user[$op];
			}elseif (isset($_GET['op'])){
					$dato = $_GET['op'];
					$tmp = explode("/", $dato);

					$op = $tmp[0];
					$rol = $tmp[1];
					$obj = $user[$op];
			}
    	?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Tenth navbar example">
    <div class="container-fluid">
		
		<a href="" class="brand-logo"><img id="background" src="../images/Veris2.png" height="65"/> </a>
		<?php
			echo '<p style="font-weight: bold; color: white; text-align: right"><b>Bienvenido <span>'. $obj->getUsuario() .'!</span></p>';
		?>
      <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample08">
        <ul class="navbar-nav">

			<?php
                // Mostrar enlaces según el rol del usuario
                if ($rol == 1) {
                    echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/especialidades.php">Especialidades Medicas</a>
						  </li>';
                    echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/medicamentos.php">Medicamentos</a>
						  </li>';
					echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/medicos.php">Medicos</a>
						  </li>';
					echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/pacientes.php">Pacientes</a>
						  </li>';
					echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/usuarios.php">Usuarios</a>
						  </li>';
					
                } elseif ($rol == 2) {
                    echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/consultas.php">Consultas Medicas</a>
						  </li>';
					echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/recetas.php">Recetas</a>
						  </li>';
                } elseif ($rol == 3) {
                    echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/consultas.php">Consultas Medicas</a>
						  </li>';
					echo '<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="CRUD/recetas.php">Recetas</a>
						  </li>';
                }
            ?>
			<li class="nav-item">
				<a class="nav-link active" aria-current="page" href="cerrar.php">Cerrar Sesion</a>
			</li>
        </ul>
      </div>	  
    </div>
  </nav>
  
<body>
  <!-- ======= About Section ======= -->
  <section id="consulta" class="about">

    <!-- ======= About Me ======= -->
    <div class="about-me container">

    <?php
		require_once("class/constantes.php");
		include_once("class/class.consulta.php");
		
		$cn = conectar();
		$ma = new consulta($cn);
		$ma->printOp();
		if(isset($_GET['a'])){
			$dato = base64_decode($_GET['a']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $ma->delete_consulta($id);
			}elseif($op == "det"){
				echo $ma->get_detail_consulta($id);
			}elseif($op == "new"){
				echo $ma->get_form();
			}elseif($op == "act"){
				echo $ma->get_form($id);
			}			
       // PARTE III	
		}else{
		  
			if(isset($_POST['Guardar']) && $_POST['op']=="new" && $_POST["consulta"] == "consulta"){
				$ma->save_consulta();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update" && $_POST["consulta"] == "consulta"){
				$ma->update_consulta();
			}else{
				echo $ma->get_list();
			}
		}
    		
	//*******************************************************
		function conectar(){
			//echo "<br> CONEXION A LA BASE DE DATOS<br>";
			$c = new mysqli(SERVER,USER,PASS,BD);
			
			if($c->connect_errno) {
				die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
			}else{
				//echo "La conexión tuvo éxito .......<br><br>";
			}
			
			$c->set_charset("utf8");
			return $c;
		}
	//**********************************************************	
	?>
    </div><!-- End About Me -->
  </section><!-- End About Section -->

  <!-- ======= Resume Section ======= -->

    <!-- ======= Services Section ======= -->
	<section id="especialidad" class="services">
    <div class="container">

    <?php
		require_once("class/constantes.php");
		include_once("class/class.especialidad.php");
		
		$cn = conectar();
		$mat = new especialidad($cn);
		$mat->printOp();
		
		if(isset($_GET['b'])){
			$dato = base64_decode($_GET['b']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $mat->delete_especialidad($id);
			}elseif($op == "det"){
				echo $mat->get_detail_especialidad($id);
			}elseif($op == "new"){
				echo $mat->get_form();
			}elseif($op == "act"){
				echo $mat->get_form($id);
			}
       // PARTE III	
		}else{
		  
			if(isset($_POST['Guardar']) && $_POST['op']=="new" && $_POST["especialidad"] == "especialidad"){
				$mat->save_especialidad();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update" && $_POST["especialidad"] == "especialidad"){
				$mat->update_especialidad();
			}else{
				echo $mat->get_list();
			}
		}
	?>
    </div>
  </section>
  <!-- End Services Section -->
  
  <!-- ======= Services Section ======= -->
  <section id="medicamento" class="services">
    <div class="container">

    <?php
		require_once("class/constantes.php");
		include_once("class/class.medicamento.php");
		
		$cn = conectar();
		$mat = new medicamento($cn);
		$mat->printOp();
		
		if(isset($_GET['c'])){
			$dato = base64_decode($_GET['c']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $mat->delete_medicamento($id);
			}elseif($op == "det"){
				echo $mat->get_detail_medicamento($id);
			}elseif($op == "new"){
				echo $mat->get_form();
			}elseif($op == "act"){
				echo $mat->get_form($id);
			}
       // PARTE III	
		}else{

			if(isset($_POST['Guardar']) && $_POST['op']=="new" && $_POST["medicamento"] == "medicamento"){
				$mat->save_medicamento();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update" && $_POST["medicamento"] == "medicamento"){
				$mat->update_medicamento();
			}else{
				echo $mat->get_list();
			}
		}
	?>
    </div>
  </section>
  <!-- End Services Section -->
  
  
  <!-- ======= Services Section ======= -->
  <section id="medico" class="services">
    <div class="container">

    <?php
		require_once("class/constantes.php");
		include_once("class/class.medico.php");
		
		$cn = conectar();
		$mat = new medico($cn);
		$mat->printOp();
		
		if(isset($_GET['d'])){
			$dato = base64_decode($_GET['d']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $mat->delete_medico($id);
			}elseif($op == "det"){
				echo $mat->get_detail_medico($id);
			}elseif($op == "new"){
				echo $mat->get_form();
			}elseif($op == "act"){
				echo $mat->get_form($id);
			}
       // PARTE III	
		}else{

			if(isset($_POST['Guardar']) && $_POST['op']=="new" && $_POST["medico"] == "medico"){
				$mat->save_medico();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update" && $_POST["medico"] == "medico"){
				$mat->update_medico();
			}else{
				echo $mat->get_list();
			}
		}
	?>
    </div>
  </section>
  <!-- End Services Section -->
  
  
  
  <!-- ======= Services Section ======= -->
  <section id="paciente" class="services">
    <div class="container">

    <?php
		require_once("class/constantes.php");
		include_once("class/class.paciente.php");
		
		$cn = conectar();
		$mat = new paciente($cn);
		$mat->printOp();
		
		if(isset($_GET['e'])){
			$dato = base64_decode($_GET['e']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $mat->delete_paciente($id);
			}elseif($op == "det"){
				echo $mat->get_detail_paciente($id);
			}elseif($op == "new"){
				echo $mat->get_form();
			}elseif($op == "act"){
				echo $mat->get_form($id);
			}
       // PARTE III	
		}else{

			if(isset($_POST['Guardar']) && $_POST['op']=="new" && $_POST["paciente"] == "paciente"){
				$mat->save_paciente();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update" && $_POST["paciente"] == "paciente"){
				$mat->update_paciente();
			}else{
				echo $mat->get_list();
			}
		}
	?>
    </div>
  </section>
  <!-- End Services Section -->
  
  
  <!-- ======= Services Section ======= -->
  <section id="receta" class="services">
    <div class="container">

    <?php
		require_once("class/constantes.php");
		include_once("class/class.receta.php");
		
		$cn = conectar();
		$mat = new receta($cn);
		$mat->printOp();
		
		if(isset($_GET['f'])){
			$dato = base64_decode($_GET['f']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $mat->delete_receta($id);
			}elseif($op == "det"){
				echo $mat->get_detail_receta($id);
			}elseif($op == "new"){
				echo $mat->get_form();
			}elseif($op == "act"){
				echo $mat->get_form($id);
			}
       // PARTE III	
		}else{

			if(isset($_POST['Guardar']) && $_POST['op']=="new" && $_POST["receta"] == "receta"){
				$mat->save_receta();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update" && $_POST["receta"] == "receta"){
				$mat->update_receta();
			}else{
				echo $mat->get_list();
			}
		}
	?>
    </div>
  </section>
  <!-- End Services Section -->
  
  
  
  <!-- ======= Services Section ======= -->
  <section id="usuario" class="services">
    <div class="container">

    <?php
		require_once("class/constantes.php");
		include_once("class/class.usuario.php");
		
		$cn = conectar();
		$mat = new usuario($cn);
		$mat->printOp();
		
		if(isset($_GET['g'])){
			$dato = base64_decode($_GET['g']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $mat->delete_usuario($id);
			}elseif($op == "det"){
				echo $mat->get_detail_usuario($id);
			}elseif($op == "new"){
				echo $mat->get_form();
			}elseif($op == "act"){
				echo $mat->get_form($id);
			}
       // PARTE III	
		}else{
			
			if(isset($_POST['Guardar']) && $_POST['op']=="new" && $_POST["usuario"] == "usuario"){
				$mat->save_usuario();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update" && $_POST["usuario"] == "usuario"){
				$mat->update_usuario();
			}else{
				echo $mat->get_list();
			}
		}
	?>
    </div>
  </section>
  <!-- End Services Section -->
  
  
  
  
  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
		  document.addEventListener('DOMContentLoaded', (event) => {
        // Tu código existente aquí...

        // Verifica si hay un fragmento en la URL
        const fragment = window.location.hash;
        if (fragment) {
          // Encuentra el elemento con el ID que coincide con el fragmento
          const targetElement = document.querySelector(fragment);
          
          // Realiza el desplazamiento suave a la sección
          if (targetElement) {
            targetElement.scrollIntoView({
              behavior: 'smooth'
            });
          }
        }
      });
	</script>

</body>

</html>