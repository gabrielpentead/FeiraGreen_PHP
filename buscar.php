<?php
include("Conexao/conexao.php");

$produtos = [];
$palavra = "";

if (isset($_GET['palavra'])) {
    $palavra = trim($_GET['palavra']);
    
    try {
        $sql = "SELECT * FROM produtos WHERE nome LIKE :palavra";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $palavra . "%";
        $stmt->bindParam(':palavra', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao buscar produtos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loja Virtual de Produtos Naturais</title>
    <link rel="stylesheet" href="css/produtos.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<header>
    <div class="logo">
        <a href="home.php"><img src="imagens/logoverde.png" alt="Logo" width="200"></a>
    </div>
    <div id="area-menu">
        <a href="">Frutas</a>
        <a href="">Verduras</a>
        <a href="">Hortaliças</a>
        <a href="">Legumes</a>
        <a href="">Outros</a>
    </div>
    <nav>
        <form method="GET" action="buscar.php" class="barra-pesquisa">
            <input type="text" name="palavra" placeholder="Buscar produto..." required>
            <button type="submit" class="botao-pesquisa">
                <img src="imagens/pesquisa.png" alt="Pesquisar" width="17">
            </button>
        </form>
        <a href="Perfil/perfil.php"><img src="imagens/usuario.png" alt="Usuário" width="20"></a>
        <a href=""><img src="imagens/carrinho-carrinho.png" alt="Carrinho" width="20"></a>
    </nav>
</header>
<div class="line"></div>
<main class="produto-page">
    <div class="container-principal">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class='produto-container-principal'>
                    <div class='produto-principal'>
                        <a href='paginapd.php?id=<?= htmlspecialchars($produto['id']) ?>' aria-label='Ver detalhes do produto <?= htmlspecialchars($produto['nome']) ?>'>
                            <img src='Produtos/uploads/<?= htmlspecialchars($produto['imagem']) ?>' alt='<?= htmlspecialchars($produto['nome']) ?>' class='img-fluid' onerror="this.onerror=null; this.src='path/to/default/image.jpg';" />
                        </a>
                        <span><?= htmlspecialchars($produto['nome']) ?></span><br>
                        <span>R$ <span class='price'><?= number_format($produto['preco'], 2, ',', '.') ?></span></span><br>
                        <span class='unit'><?= htmlspecialchars($produto['categoria']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto encontrado com a palavra: <?= htmlspecialchars($palavra) ?></p>
        <?php endif; ?>
    </div>
</main>
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
                &copy; 2024 FeiraGreen. Todos os direitos reservados.
            </div>
            <div class="footer-bottom--right">
                <img class="footer-image" src="assets/imagens/mastercard.png" alt="">
                <img class="footer-image" src="assets/imagens/paypal.png" alt="">
                <img class="footer-image" src="assets/imagens/visa.png" alt="">
            </div>
        </div>
    </div>
</footer>
</body>
</html>