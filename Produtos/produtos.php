<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php"); 
    exit();
}

include("../Conexao/conexao.php");

try {
    // Preparando a consulta para listar os produtos
    $stmt = $conn->prepare("SELECT * FROM produtos");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar produtos: " . $e->getMessage());
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
        <a href="../Perfil/perfil.php"><img src="../imagens/usuario.png" alt="" width="20"></a>
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
    <?php if (!empty($produtos)): ?>
        <?php foreach ($produtos as $produto): ?>
            <div class='produto-container-principal'>
                <div class='produto-principal'>
                    <a href='editproduto.php?id=<?= $produto['id'] ?>' aria-label='Ver detalhes do produto <?= htmlspecialchars($produto['nome']) ?>'>
                        <img src='uploads/<?= htmlspecialchars($produto['imagem']) ?>' alt='<?= htmlspecialchars($produto['nome']) ?>' class='img-fluid' onerror="this.onerror=null; this.src='/path/to/default/image.jpg';" />
                    </a>
                    <span><?= htmlspecialchars($produto['nome']) ?></span>
                    <span>R$ <span class='price'><?= number_format($produto['preco'], 2, ',', '.') ?></span><br>
                    <span class='unit'><?= htmlspecialchars($produto['categoria']) ?></span>
                    <div class='button-group'>
                        <button type='button' class='edit-btn' onclick="location.href='editarproduto.php?id=<?= $produto['id'] ?>'">Editar</button>
                        <button type='button' class='delete-btn' onclick="location.href='excluirproduto.php?id=<?= $produto['id'] ?>'">Excluir</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class='produto-container-principal'><p>Nenhum produto encontrado.</p></div>
    <?php endif; ?>
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