<?php 

namespace Controllers;

use Model\Cita;
use MVC\Router;
use Model\Servicio;
use Model\CitaServicio;


class APIController {

    public static function index() {

        //header('Content-Type: application/json');
        
        $servicios = Servicio::all();

        echo json_encode($servicios);
    }


    public static function guardar() {
        
        // Guarda la cita y devuelve el ID de registro
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        
        $idServicios = explode(",", $_POST['servicios']);

        // Guarda los servicios con el Id de la cita
        foreach ($idServicios as $idServicios) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicios
            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado]);
    }


    public static function eliminar() {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        } 
    }
}