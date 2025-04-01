<?php
session_start();
include("../Conexao/conexao.php");
$id = $_SESSION["id"];

$sql = "DELETE FROM usuarios WHERE id = '$id'";

if ($conn->query($sql) === TRUE) {
    session_destroy(); 
    header("Location: login.php"); 
} else {
    echo "Erro ao excluir: " . $conn->error;
}
?>