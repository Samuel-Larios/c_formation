<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'songhai';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "ALTER TABLE migrations DISCARD TABLESPACE";
if ($conn->query($sql) === TRUE) {
    echo "Tablespace discarded successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
