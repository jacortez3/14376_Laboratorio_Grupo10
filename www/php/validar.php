<?php
	require_once("class/constantes.php");
	include_once("class/class.usuario.php");
	
	session_start();
	
	// echo "<br>VARIABLE SESSION: <br>";
	// echo "<pre>";
	// print_r($_SESSION);
	// echo "</pre>";
	
	// echo "<br>VARIABLE POST: <br>";
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";
	
	if (isset($_POST['usuario']) && isset($_POST['clave'])) {
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];

        $cn = conectar();
        $users = new Usuario($cn);

        // Utiliza el método validarUsuario para verificar el usuario y la contraseña
        if ($users->validarUsuario($usuario, $clave)) {
            // Autenticación exitosa
            echo "Exito";
            header("location:sistema.php?op=$usuario/{$users->getIdRol()}");
            
            exit();
        } else {
            // Error en la autenticación
            header("location:ErrorAutentificacion.php");
            exit();
        }
    } else {
        // Manejar el caso en que no se proporcionen las variables esperadas
        header("location:ErrorAutentificacion.php");
        exit();
    }

    function conectar() {
        $c = new mysqli(SERVER, USER, PASS, BD);

        if ($c->connect_errno) {
            die("Error de conexión: " . $c->mysql_connect_error() . ", " . $c->connect_error());
        }

        $c->set_charset("utf8");
        return $c;
    }
?>