<?php

namespace Controllers;

use MVC\Router;
use Model\AdminCita;

class AdminController {

    public static function index (Router $router) {
        
        session_start();

        isAdmin();

        $fecha = $_GET['fecha'] ?? Date('Y-m-d');
        $fechas = explode('-', $fecha);
    
        if (count($fechas) === 3 && is_numeric($fechas[0]) && is_numeric($fechas[1]) && is_numeric($fechas[2])) {
            if (!checkdate($fechas[1], $fechas[2], $fechas[0]) ) {
                $fecha = Date('Y-m-d');
            }
        } else {
            $fecha = Date('Y-m-d');
        }
        
 

        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";


        $citas = AdminCita::SQL($consulta);
        
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}