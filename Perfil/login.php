<?php
// Inicia a sessão apenas se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui a conexão com o banco de dados
require_once("../Conexao/conexao.php");

class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $senha) {
        try {
            $stmt = $this->conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($senha, $usuario["senha"])) {
                    $_SESSION["id"] = $usuario["id"];
                    $_SESSION["nome"] = $usuario["nome"];
                    return "sucesso";
                } else {
                    return "Senha incorreta!";
                }
            } else {
                return "Usuário não encontrado!";
            }
        } catch (PDOException $e) {
            return "Erro no banco de dados: " . $e->getMessage();
        }
    }
}

$error = "";

// Se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = new Usuario($conn);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);
    $resultado = $usuario->login($email, $senha);

    if ($resultado === "sucesso") {
        header("Location: ../home.php");
        exit();
    } else {
        $error = $resultado;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Feira Green</title>
    <link rel="stylesheet" href="../css/Login.css">
</head>
<body>
    <div class="formulario-login">
        <div class="container">
            <div class="centralizar">
                <div class="imgen">
                    <img src="../imagens/LogoFeiraGreen.png">
                </div>
                <!-- Exibe o erro caso exista -->
                <?php if (!empty($error)): ?>
                    <p class='error'><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="post" action="login.php">
                    <label for="email"><b>Email</b></label>
                    <input type="email" placeholder="Insira seu email" name="email" required>
                    
                    <label for="senha" class="local-senha"><b>Senha</b></label>
                    <input type="password" placeholder="Insira sua senha" name="senha" required>
                    
                    <button type="submit">Entrar</button>
                </form>
                <a href="#">Esqueceu a senha?</a>
                <a href="cadastro.php">Criar uma conta</a>
            </div>
        </div>
    </div>
</body>
</html>
