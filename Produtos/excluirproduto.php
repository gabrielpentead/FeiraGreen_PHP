<?php
session_start();
include("../Conexao/conexao.php");

if (!isset($_SESSION["id"])) {
    header("Location: ../Perfil/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    echo "Produto não encontrado.";
    exit();
}

$id_produto = $_GET["id"];

// Busca os dados do produto no banco
$sql = "SELECT * FROM produtos WHERE id = '$id_produto'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Produto não encontrado.";
    exit();
}

$row = $result->fetch_assoc();

// Excluir a imagem do servidor
if (!empty($row["imagem"]) && file_exists("../Perfil/uploads/" . $row["imagem"])) {
    unlink("../Perfil/uploads/" . $row["imagem"]);
}

// Excluir o produto do banco de dados
$sql_delete = "DELETE FROM produtos WHERE id = '$id_produto'";

if ($conn->query($sql_delete) === TRUE) {
    header("Location: produtos.php"); 
    exit();
} else {
    echo "Erro ao excluir produto: " . $conn->error;
}
?>
