<h1 class='nombre-pagina'>Recuperar Password</h1>

<p class='descripcion-pagina'>Ingresar nuevo password</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>


    
<?php if($error === true) {
    return; 
}

if ($error === 1) { ?>
    
    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Iniciar Sesion</a>
    </div> 

    <?php return;

} ?>
        

<form class='formulario' method='POST'>
    <div class='campo'>
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu nuevo password"
        />
    </div>

    <div class='campo'>
        <label for="confirmar-password">Confirmar Password</label>
        <input 
            type="password"
            id="confirmar-password"
            name="confirmar-password"
            placeholder="Confirmar password"
        />
    </div>

    <input type="submit" class="boton" value="Guardar Nuevo Password">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesion</a>
    <a href="/crear-cuenta">Crear Cuenta</a>
</div> 