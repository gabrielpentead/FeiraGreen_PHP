<?php
session_start();
include("../Conexao/conexao.php");

if (!isset($_SESSION["id"])) {
    header("Location: ../Perfil/login.php"); 
    exit();
}

// Verifica se um ID de produto foi passado via GET
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

// Se o formulário for enviado, atualiza os dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $preco = $_POST["preco"];
    $categoria = $_POST["categoria"];
    $descricao = $_POST["descricao"];
    $imagemNome = $row["imagem"]; 

    if (!empty($_FILES["imagem"]["name"])) {
        $imagem = $_FILES["imagem"];
        $imagemNome = uniqid() . "-" . basename($imagem["name"]);
        $imagemDestino = "../Perfil/uploads/" . $imagemNome;

    }
    
    // Atualiza o produto no banco
    $sql = "UPDATE produtos SET 
                nome = '$nome', 
                preco = '$preco', 
                categoria = '$categoria', 
                descricao = '$descricao', 
                imagem = '$imagemNome' 
            WHERE id = '$id_produto'";

    if ($conn->query($sql) === TRUE) {
        header("Location: produtos.php"); 
        exit();
    } else {
        echo "Erro ao atualizar produto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/produtos.css">
    <link rel="stylesheet" type="text/css" href="../css/defalt.css">
</head>
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
<body>
    

<div class="container">
<h2>Editar Produto</h2>
    <form action="editarproduto.php?id=<?php echo $id_produto; ?>" method="post" enctype="multipart/form-data">
        <label for="nome">Nome do Produto:</label>
        <input type="text" name="nome" value="<?php echo $row['nome']; ?>" required><br><br>

        <label for="preco">Preço:</label>
        <input type="number" name="preco" step="0.01" value="<?php echo $row['preco']; ?>" required><br><br>

        <label for="categoria">Categoria:</label>
        <select name="categoria">
            <option value="frutas" <?php echo ($row['categoria'] == "frutas") ? "selected" : ""; ?>>Frutas</option>
            <option value="verduras" <?php echo ($row['categoria'] == "verduras") ? "selected" : ""; ?>>Verduras</option>
            <option value="hortalicas" <?php echo ($row['categoria'] == "hortalicas") ? "selected" : ""; ?>>Hortaliças</option>
            <option value="legumes" <?php echo ($row['categoria'] == "legumes") ? "selected" : ""; ?>>Legumes</option>
            <option value="outros" <?php echo ($row['categoria'] == "outros") ? "selected" : ""; ?>>Outros</option>
        </select><br><br>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" required><?php echo $row['descricao']; ?></textarea><br><br>

        <label for="imagem">Imagem Atual:</label><br>
        <img src="../Produtos/uploads/<?php echo $row['imagem']; ?>" width="150"><br><br>

        <label for="imagem">Nova Imagem (opcional):</label>
        <input type="file" name="imagem" accept="image/*"><br><br>

        <button type="submit">Salvar Alterações</button><br><br>
        <a href="produtos.php">
            <button type="button">Cancelar</button>
        </a>
        </form>
    </div>
</body>
<footer>
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
        </footer>
</html>
