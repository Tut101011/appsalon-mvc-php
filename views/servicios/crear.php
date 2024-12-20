<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Llena todos los campos para añadir un nuevo servicios</p>

<?php
    require_once __DIR__ . '/../templates/barra.php';
    require_once __DIR__ . '/../templates/alertas.php';

?>

<form action="/servicios/crear" method="POST" class="formulario">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" class="boton" value="Guardar Servicio">

</form>