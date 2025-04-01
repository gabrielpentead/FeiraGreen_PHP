<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php"); 
    exit();
}

include("../Conexao/conexao.php");

// Listando produtos
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);

if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    
    $sql_check = "SELECT imagem FROM produtos WHERE id = '$id_produto'";
    $check_result = $conn->query($sql_check);
    
    if ($check_result && $check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();
        
        // Verifica se a imagem existe e a exclui
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
    } else {
        echo "Produto não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/produtos.css">
</head>
<body>
<header>
    <div class="logo">
        <a href="../home.php"><img src="../imagens/logoverde.png" alt="" width="200"></a>
    </div>
    <div id="area-menu">
        <a href="">Frutas</a>
        <a href="">Verduras</a>
        <a href="">Hortaliças</a>
        <a href="">Legumes</a>
        <a href="">Outros</a>
    </div>
    <nav>
        <img src="../imagens/pesquisa.png" alt="" width="20">
        <a href="/Perfil/perfil.php"><img src="../imagens/usuario.png" alt="" width="20"></a>
        <a href=""><img src="../imagens/carrinho-carrinho.png" alt="" width="20"></a>
    </nav>
</header>
<div class="line"></div>
<div class="container">
    <h2>Produtos Cadastrados</h2>
    <br>
    <a href="addproduto.php"><button type="button">Adicionar Produto</button></a>
</div>
<div class="container-principal">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='produto-container-principal'>";
                echo "<div class='produto-principal'>";
                echo "<a href='editproduto.php?id=" . $row['id'] . "' aria-label='Ver detalhes do produto " . htmlspecialchars($row['nome']) . "'>";
                echo "<img src='uploads/" . htmlspecialchars($row['imagem']) . "' alt='" . htmlspecialchars($row['nome']) . "' class='img-fluid' onerror=\"this.onerror=null; this.src='/path/to/default/image.jpg';\" />";
                echo "</a>";
                echo "<span>" . htmlspecialchars($row['nome']) . "</span>";
                echo "<span>R$ <span class='price'>" . number_format($row['preco'], 2, ',', '.') . "</span><br>";
                echo "<span class='unit'>" . htmlspecialchars($row['categoria']) . "</span>";
                echo "<div class='button-group'>";
                echo "<button type='button' class='edit-btn' onclick=\"location.href='editarproduto.php?id=" . $row['id'] . "'\">Editar</button>";
                echo "<button type='button' class='delete-btn' onclick=\"location.href='excluirproduto.php?id=" . $row['id'] . "'\">Excluir</button>";
                echo "</div>";
                echo "</div></div>";
            }
        } else {
            echo "<div class='produto-container-principal'><p>Nenhum produto encontrado.</p></div>";
        }
        ?>
</div>
</body>
<footer>
    <div class="footer">
    <div class="footer-top">
        <div class="footer-top--left">
            <a href="#">Contato</a>
            <a href="#">Termos de Serviço</a>
            <a href="#">Política de Privacidade</a>
            <a href="#">Cancelamento, Troca e Reembolso</a>
        </div>
        <div class="footer-top--right">
            <span>Boletim de Notícias</span>
            <div class="footer-news-letter">
                <input class="footer-input" type="email" placeholder="Digite o seu e-mail">
                <button class="footer-button" type="button">Inscrever</button>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-bottom--left">
            <a href="#"><img class="footer-image" src="assets/imagens/instagram.png" alt=""></a>
            <a href="#"><img class="footer-image" src="assets/imagens/facebook.png" alt=""></a>
        </div>
        <div>
            &copy; 2025 FeiraGreen. Todos os direitos reservados.
        </div>
        <div class="footer-bottom--right">
            <img class="footer-image" src="assets/imagens/mastercard.png" alt="">
            <img class="footer-image" src="assets/imagens/paypal.png" alt="">
            <img class="footer-image" src="assets/imagens/visa.png" alt="">
        </div>
    </div>
    </div>
</footer>
</html>