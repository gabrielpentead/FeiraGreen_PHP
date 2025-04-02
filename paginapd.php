<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: Perfil/login.php"); 
    exit();
}

include("Conexao/conexao.php");

// Verifica se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Converte o ID para um inteiro para segurança

    // Busca o produto pelo ID
    $sql = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o produto foi encontrado
    if (!$produto) {
        echo "<p>Produto não encontrado.</p>";
        exit();
    }
} else {
    echo "<p>ID do produto não especificado.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - Loja Virtual de Produtos Naturais</title>
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
            <img src="imagens/pesquisa.png" alt="Pesquisa" width="20">
            <a href="Perfil/perfil.php"><img src="imagens/usuario.png" alt="Usuário" width="20"></a>
            <a href=""><img src="imagens/carrinho-carrinho.png" alt="Carrinho" width="20"></a>
        </nav>
    </header>
    <hr>

    <div class="item-container">
        <div class="item-box">
            <div class="image-box">
                <img src="Produtos/uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
            </div>
            <div class="text-container">
                <div class="category"><?php echo htmlspecialchars($produto['categoria']); ?></div>
                <div class="name"><?php echo htmlspecialchars($produto['nome']); ?></div>
                <div class="price-product">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?> Unidade</div>
                <hr>
                <div class="button-container">
                    <button class="buy-button">Comprar</button>
                    <button class="add-button">Adicionar ao Carrinho</button>
                </div>
            </div>
        </div>
    </div>
    <hr>

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
                <div>
                    &copy; 2025 FeiraGreen. Todos os direitos reservados.
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
