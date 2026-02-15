<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "cadastro";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão");
}

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha_form = $_POST['senha'];

$senha_hash = password_hash($senha_form, PASSWORD_DEFAULT);

// VERIFICAR EMAIL (SEM ID)
$check = $conn->prepare("SELECT email FROM usuarios WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('Este email já está cadastrado!'); window.history.back();</script>";
    exit;
}
$check->close();

// INSERIR USUÁRIO
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senha_hash);

if ($stmt->execute()) {
    echo "<script>alert('Cadastro realizado com sucesso!');</script>";
} else {
    echo "<script>alert('Erro ao cadastrar');</script>";
}

$stmt->close();
$conn->close();
?>
