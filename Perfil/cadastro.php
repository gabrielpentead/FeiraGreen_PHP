<?php
include("../Conexao/conexao.php");

$erro = ""; // Inicializa variável de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    try {
        // Verifica se o e-mail já existe no banco de dados
        $consulta = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
        $consulta->bindParam(":email", $email, PDO::PARAM_STR);
        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            $erro = "Este e-mail já está cadastrado. Tente outro.";
        } else {
            // Insere o novo usuário
            $insercao = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $insercao->bindParam(":nome", $nome, PDO::PARAM_STR);
            $insercao->bindParam(":email", $email, PDO::PARAM_STR);
            $insercao->bindParam(":senha", $senha, PDO::PARAM_STR);

            if ($insercao->execute()) {
                header("Location: login.php?sucesso=1");
                exit();
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    } catch (PDOException $e) {
        $erro = "Erro no banco de dados: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Feira Green</title>
    <link rel="stylesheet" type="text/css" href="../css/Login.css">
</head>
<body>
<div class="formulario-Cadastro">
    <div class="container">
        <div class="centralizar">
            <div class="imgen">
                <img src="../imagens/LogoFeiraGreen.png" alt="Logo Feira Green">
            </div>

            <!-- Exibe mensagem de erro -->
            <?php if(!empty($erro)): ?>
                <div class="error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="post" action="cadastro.php">
                <label for="nome"><b>Nome</b></label>
                <input type="text" placeholder="Insira seu nome" name="nome" required>

                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Insira seu email" name="email" required>

                <label for="senha"><b>Senha</b></label>
                <input type="password" placeholder="Crie uma senha" name="senha" required>

                <button type="submit">Cadastrar</button>
            </form>

            <a href="login.php">Já tem uma conta? Faça login</a>
        </div>
    </div>
</div>
</body>
</html>
