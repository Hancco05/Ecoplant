<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $confirmar_correo = $_POST['confirmar_correo'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if ($correo != $confirmar_correo) {
        die("Los correos electrónicos no coinciden. Por favor, intenta de nuevo.");
    }

    if ($contrasena != $confirmar_contrasena) {
        die("Las contraseñas no coinciden. Por favor, intenta de nuevo.");
    }

    if (strlen($contrasena) < 8 || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[0-9]/', $contrasena)) {
        die("La contraseña debe tener al menos 8 caracteres, una mayúscula y un número.");
    }

    $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

    $stmt_check_email = $conn->prepare("SELECT correo_electronico FROM usuarios WHERE correo_electronico = ?");
    $stmt_check_email->bind_param("s", $correo);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();
    if ($stmt_check_email->num_rows > 0) {
        die("El correo electrónico ya está en uso.");
    }
    $stmt_check_email->close();

    $stmt_insert_user = $conn->prepare("INSERT INTO usuarios (nombre, apellido, correo_electronico, contrasena) VALUES (?, ?, ?, ?)");
    $stmt_insert_user->bind_param("ssss", $nombre, $apellido, $correo, $contrasena_encriptada);
    if ($stmt_insert_user->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }
    $stmt_insert_user->close();
}
?>
