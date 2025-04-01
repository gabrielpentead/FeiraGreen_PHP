<?php
session_start();
include("../Conexao/conexao.php");
$id = $_SESSION["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];

    $sql = "UPDATE usuarios SET nome = '$nome', email = '$email' WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: perfil.php"); 
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
}

$sql = "SELECT * FROM usuarios WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>

<form method="post" action="editar.php">
    Nome: <input type="text" name="nome" value="<?php echo $row['nome']; ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br>
    <input type="submit" value="Salvar">
</form>

<?php
}
?>