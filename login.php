<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gtargxd";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Revisar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Consultar usuario
    $sql = "SELECT * FROM wcf1_user WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar contraseña
        $hashed_password = hash('sha256', $row['salt'] . $pass);
        if ($hashed_password === $row['password']) {
            // Guardar sesión y redirigir
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['username'] = $row['username'];
            header("Location: welcome.php");
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>

