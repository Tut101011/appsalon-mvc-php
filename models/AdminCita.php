<?php

namespace Model;


class AdminCita extends ActiveRecord {
    
    // Base de datos
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id', 'hora', 'cliente', 'email', 'telefono', 'servicio', 'precio'];

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;


    public function __construct($args = []) {

        $this->id = $args['id'] ?? null;
        $this->fecha = $args['hora'] ?? '';
        $this->hora = $args['cliente'] ?? '';
        $this->usuarioId = $args['email'] ?? '';
        $this->usuarioId = $args['telefono'] ?? '';
        $this->usuarioId = $args['servicio'] ?? '';
        $this->usuarioId = $args['precio'] ?? '';
    }
}