<?php

function dbg($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}


function esUltimo (string $actual, string $proximo) : bool {
    if ($actual !== $proximo) {
        return true;
    }

    return false;
}


// Verifica si el usuario esta Autenticado
function isAuth() : void {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}


// Protege la ruta, Admin
function isAdmin() : void {
    if (!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}