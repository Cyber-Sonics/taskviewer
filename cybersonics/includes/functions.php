<?php
session_start();
include('config.php');

function login($username, $password) {
    global $pdo;
    $sql = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function logout() {
    session_destroy();
    redirect('../index.php');
}
?>
