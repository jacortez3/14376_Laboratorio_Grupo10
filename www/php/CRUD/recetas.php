<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Matriculas Vehículos</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	

	<!-- Bootstrap core CSS -->
	<link href="../../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  
   <!-- Main Stylesheet File -->

	<link href="../../css/main.css" rel="stylesheet">
            
</head>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Tenth navbar example">
    <div class="container-fluid">
		<a href="" class="brand-logo"><img id="background" src="../../images/Veris2.png" height="65"/> </a>

		

      <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample08">
        <ul class="navbar-nav">
		
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="consultas.php">Consultas</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="recetas.php">Recetas</a>
          </li>
		  <li class="nav-item">
				<a class="nav-link active" aria-current="page" href="../cerrar.php">Cerrar Sesion</a>
			</li>
        </ul>
      </div>
    </div>
  </nav>

<body class="container">


	<?php
		require_once("../class/constantes.php");
		include_once("../class/class.receta.php");
		
		$cn = conectar();
		$v = new receta($cn);
		

		if(isset($_GET['d'])){
			$dato = base64_decode($_GET['d']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $v->delete_receta($id);
			}elseif($op == "det"){
				echo $v->get_detail_receta($id);
			}elseif($op == "new"){
				echo $v->get_form();
			}elseif($op == "act"){
				echo $v->get_form($id);
			}
			
       // PARTE III	
		}else{
			   
				// echo "<br>PETICION POST <br>";
				// echo "<pre>";
				// 	print_r($_POST);
				// echo "</pre>";
		      
			if(isset($_POST['Guardar']) && $_POST['op']=="new"){
				$v->save_receta();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				$v->update_receta();
			}else{
				echo $v->get_list();
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

	<!-- JavaScript Libraries -->
	<script src="lib/jquery/jquery.min.js"></script>
	<script src="lib/bootstrap/js/bootstrap.min.js"></script>
	<script src="lib/owlcarousel/owl.carousel.min.js"></script>
	<script src="lib/venobox/venobox.min.js"></script>
	<script src="lib/knob/jquery.knob.js"></script>
	<script src="lib/wow/wow.min.js"></script>
	<script src="lib/parallax/parallax.js"></script>
	<script src="lib/easing/easing.min.js"></script>
	<script src="lib/nivo-slider/js/jquery.nivo.slider.js" type="text/javascript"></script>
	<script src="lib/appear/jquery.appear.js"></script>
	<script src="lib/isotope/isotope.pkgd.min.js"></script>
  

</body>
<footer>
		
</footer>
</html>
