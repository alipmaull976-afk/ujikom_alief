<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query ke tabel 'user'
    $sql = "SELECT * FROM user WHERE username='$username'";
    $query = mysqli_query($conn, $sql);

    if (!$query) {
        die("Query Error: " . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($query);

    if ($data) {
        if ($password == $data['password']) {
            // Set Session berdasarkan database kamu
            $_SESSION['id_user']  = $data['id_user'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role']; 

            if ($data['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else if ($data['role'] == 'kasir') {
                header("Location: kasir_dashboard.php");
            }
            exit;
        } else {
            header("Location: login.php?pesan=password_salah");
        }
    } else {
        header("Location: login.php?pesan=user_tidak_ada");
    }
}
?>