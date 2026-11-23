<?php
$host = getenv("DB_HOST") ?: "localhost";
$port = getenv("DB_PORT") ?: "3306";
$dbname = getenv("DB_NAME") ?: "student_internship_tracker";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASSWORD");
if ($password === false) {
    $password = "";
}

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    exit("Database connection failed. Check the deployment database settings.");
}
