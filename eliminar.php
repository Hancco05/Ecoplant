<?php
// Establecer conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecoplant";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no ha iniciado sesión, redirigir al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario
$usuario_id = $_SESSION['usuario_id'];

// Consulta SQL para eliminar al usuario
$sql = "DELETE FROM usuarios WHERE id = $usuario_id";

if ($conn->query($sql) === TRUE) {
    // Eliminación exitosa, destruir la sesión y redirigir al usuario a la página de inicio de sesión
    session_destroy();
    header("Location: login.php");
    exit();
} else {
    // Error al eliminar el usuario
    echo "Error al intentar eliminar el usuario: " . $conn->error;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
