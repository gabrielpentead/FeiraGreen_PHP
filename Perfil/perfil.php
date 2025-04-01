<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php"); 
    exit();
}

include("../Conexao/conexao.php");

$id = $_SESSION["id"];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nomeUsuario = htmlspecialchars($row["nome"] ?? ''); 
    $emailUsuario = htmlspecialchars($row["email"] ?? ''); 
    $avatarPath = htmlspecialchars($row["avatar"] ?? 'default-avatar.png'); 
} else {
    echo "Usuário não encontrado.";
    header("Location: login.php"); 
    exit();
}
$stmt->close();

// Atualização de dados do usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];

    // Lógica para upload de imagem
    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; 

        // Verifica se o diretório existe, se não, cria
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); 
        }

        $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se o arquivo é uma imagem
        $check = getimagesize($_FILES["avatar"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, avatar = ? WHERE id = ?");
                $stmt->bind_param("sssi", $nome, $email, $target_file, $id);
            } else {
                echo "Erro ao fazer upload da imagem.";
            }
        } else {
            echo "O arquivo não é uma imagem.";
        }
    } else {
    
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $email, $id);
    }

    if ($stmt->execute()) {
        header("Location: perfil.php"); 
        exit();
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Usuário</title>
    
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/perfil.css">
    <link rel="stylesheet" type="text/css" href="../css/defalt.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="../home.php"><img src="../imagens/logoverde.png" alt="Logo" width="200"></a>
        </div>
        <div id="area-menu">
            <a href="#">Frutas</a>
            <a href="#">Verduras</a>
            <a href="#">Hortaliças</a>
            <a href="#">Legumes</a>
            <a href="#">Outros</a>
        </div>
        <nav>
            <img src="../imagens/pesquisa.png" alt="Ícone de pesquisa" width="20">
            <a href="#"><img src="../imagens/usuario.png" alt="Ícone de usuário" width="20"></a>
            <a href=""><img src="../imagens/carrinho-carrinho.png" alt="Ícone de carrinho" width="20"></a>
        </nav>
    </header>

    <div class="line"></div>

    <div class="content">
        <div class="container">
            <form class="form-profile" method="post" enctype="multipart/form-data">
                <label class="label-avatar">
                    <span>
                        <i class="fas fa-upload" style="color: #FFF; font-size: 25px;"></i>
                    </span>
                    <input type="file" accept="image/*" name="avatar" /> <br />
                    <img src="<?php echo $avatarPath ? $avatarPath : 'default-avatar.png'; ?>" alt="Foto de perfil" width="250" height="250" />
                </label>

                <label>Nome</label>
                <input type="text" name="nome" value="<?php echo $nomeUsuario; ?>" required />

                <label>Email</label>
                <input type="email" name="email" value="<?php echo $emailUsuario; ?>" required />

                <button type="submit">Salvar</button>
            </form>
        </div>

        <div class="container">
            <a href="../Produtos/produtos.php">
                <button class="minhas-vendas-btn">Gerenciar Produtos</button>
            </a>
        </div>


        <div class="container">
            
            <a href="excluir.php">
                <button class="minhas-vendas-btn">Excluir Conta</button>
            </a>
            
            <a href="logout.php">
                <button class="minhas-vendas-btn">Sair</button>
            </a>
        </div>
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
                </div>
            </div>
        </footer>
</html>