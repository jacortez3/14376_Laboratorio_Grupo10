<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    
    <title>Login</title>
</head>
<body>


    <?php
        require_once("class/constantes.php");
        include_once("class/class.usuario.php");

            $cn = conectar();

            $users = new usuario($cn);
            $listaUsers = $users->obtenerUsuarios();

            // echo "<pre>";
            //     print_r($listaUsers);
            // echo "</pre>";
                    
            $html = "
                <div class='login-container'>
                <form class='form-login' action='validar.php' method='POST'>
                    <ul class='login-nav'>
                        <li class='login-nav__item active'>
                            <a href='#'>Ingreso</a>
                        </li>
                        
                    </ul>
                    <label for='login-input-user' class='login__label'>
                        Usuario
                    </label>
                    <input id='login-input-user' class='login__input' type='text' name='usuario'/>
                    <label for='login-input-password' class='login__label'>
                        Contraseña
                    </label>
                    <input id='login-input-password' class='login__input' type='password' name='clave'/>
                    <label for='login-sign-up' class='login__label--checkbox'>
                        <input id='login-sign-up' type='checkbox' class='login__input--checkbox'/>
                        Recordarme
                    </label>
                    <button class='login__submit' type='submit' name='Login' value='LOGIN'>Ingresar</button>
                </form>
            </div>>";
            echo $html;

                session_start();
                $_SESSION['listaUser']=$listaUsers;

                // echo "<pre>";
                //     print_r($_SESSION);
                // echo "</pre>";

            function conectar(){
                //echo "<br> CONEXION A LA BASE DE DATOS<br>";
                $c = new mysqli(SERVER,USER,PASS,BD);
                
                if($c->connect_errno) {
                    die("Error de conexión: " . $c->mysql_connect_error() . ", " . $c->connect_error());
                }else{
                    //echo "La conexión tuvo éxito .......<br><br>";
                }
                
                $c->set_charset("utf8");
                return $c;
            }

    ?>
</body>
</html>