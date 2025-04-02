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

try {
    // Preparar a consulta para buscar o produto
    $stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id = :id");
    $stmt->bindParam(":id", $id_produto, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "Produto não encontrado.";
        exit();
    }

    // Excluir a imagem do servidor, se existir
    if (!empty($produto["imagem"]) && file_exists("../Perfil/uploads/" . $produto["imagem"])) {
        unlink("../Perfil/uploads/" . $produto["imagem"]);
    }

    // Preparar a consulta para excluir o produto
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = :id");
    $stmt->bindParam(":id", $id_produto, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: produtos.php");
    exit();
} catch (PDOException $e) {
    echo "Erro ao excluir produto: " . $e->getMessage();
}
?>