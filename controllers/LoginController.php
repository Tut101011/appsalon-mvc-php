<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {

        $alertas = [];

       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    //Verificar el password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {

                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre ." ". $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                        

                    }
                    
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
            
        }    

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();

        $_SESSION = [];
    
        header('Location: /');
    }

    public static function olvide(Router $router) {
        
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $auth = new Usuario($_POST);
            

            $alertas = $auth->validarEmail();

            if (empty($alertas)) {

                $usuario = Usuario::where('email', $auth->email);
                
                if ($usuario && $usuario->confirmado === '1') {
                    
                    // Generar Token
                    $usuario->crearToken();
                    $usuario->guardar();
                    
                    // Enviar E-mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu E-mail');

                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');

                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('/auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar Usuario por Token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::Setalerta('error', 'Token No Valido');
            $error = true;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (s($_POST['password']) !== s($_POST['confirmar-password'])) {
                
                Usuario::setAlerta('error', 'Password no coincide');

            } else {

                // Leer el nuevo password y guardarlo
                $password = new Usuario($_POST);
                $alertas = $password->validarPassword();
                
                if(empty($alertas)) {
                    $usuario->password = null;

                    $usuario->password = $password->password;
                    $usuario->hashPassword();
                    $usuario->token = null;

                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Password Reestablecido');
                        $error = 1;
                    }
                }
            }

            
        }

        $alertas = Usuario::getAlertas();

        $router->render('/auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {

        $usuario = new Usuario;

        // Alertas Vacias
        $alertas = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta este vacio
            if(empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Generar Token
                    $usuario->crearToken();

                    //Enviar E-mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion(); 

                    // Crear Usuario
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
            
        }

        $router->render('/auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    public static function mensaje(Router $router) {
       
        $router->render('/auth/mensaje');

    }


    public static function confirmar(Router $router) {
       
        $alertas = [];

        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)) {
            // Mostrar Mensaje de error
            Usuario::setAlerta('error', 'token no valido');
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = '1';
            $usuario->token = null;
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Confirmada Correctamente');

        }

        // Obtener Alertas
        $alertas = Usuario::getAlertas();


        // Renderizar la vista
        $router->render('/auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);

    }

}