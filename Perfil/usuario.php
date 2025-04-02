<?php
session_start();
require_once("../Conexao/conexao.php");

class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function cadastrar($nome, $email, $senha) {
        try {
            // Verifica se o email já está cadastrado
            $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return "Este email já está cadastrado!";
            }

            // Insere o usuário com senha criptografada
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $senhaHash, PDO::PARAM_STR);

            return $stmt->execute() ? "Cadastro realizado com sucesso!" : "Erro ao cadastrar!";
        } catch (PDOException $e) {
            return "Erro no banco de dados: " . $e->getMessage();
        }
    }

    public function login($email, $senha) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($senha, $usuario["senha"])) {
                    $_SESSION["id"] = $usuario["id"];
                    $_SESSION["nome"] = $usuario["nome"];
                    return "sucesso";
                }
                return "Senha incorreta!";
            }
            return "Usuário não encontrado!";
        } catch (PDOException $e) {
            return "Erro no banco de dados: " . $e->getMessage();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = new Usuario($conn);
    $email = $_POST["email"];
    $senha = $_POST["senha"];
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
