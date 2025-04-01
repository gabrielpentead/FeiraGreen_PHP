<?php
include("../Conexao/conexao.php");

$erro = ""; // Inicializa variável de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    // Verifica se o email já está cadastrado
    $consulta = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        $erro = "Este email já está cadastrado. Tente outro.";
    } else {
        $insercao = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $insercao->bind_param("sss", $nome, $email, $senha);

        if ($insercao->execute()) {
            header("Location: login.php?sucesso=1");
            exit();
        } else {
            $erro = "Erro ao cadastrar: " . $insercao->error;
        }
    }
    $consulta->close();
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

            <!-- Exibe mensagem de erro aqui -->
            <?php if(!empty($erro)): ?>
                <div class="error"><?= $erro ?></div>
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