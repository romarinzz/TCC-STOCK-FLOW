<?php

session_start();
//i = integer
//s = string
require "conexao.php";


$nome= trim($_POST['nome']);
$email= trim($_POST['email']);
$senha_original = trim($_POST['senha']); //senha depois vai ser criptografad
$telefone= trim($_POST['telefone']);
$cpf= trim($_POST['cpf']);

//verificacao dos campos
if (empty($nome) || empty($email) || empty($senha) || empty($telefone) ||  empty($cpf)) {
    echo "Todos os campos são obrigatórios.";
    exit;
}

//valida o email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "E-mail inválido.";
    exit;
}

//verifica o cpf
if (!preg_match('/^\d{11}$/', $cpf)) {
    echo "CPF inválido. Deve conter 11 dígitos.";
    exit;
}

$senha = password_hash($senha_original, PASSWORD_DEFAULT); // criptografia da senha

$stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE cpf_usuario = ? OR email_usuario = ?");
$stmt->bind_param("ss", $cpf, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $_SESSION['mensagem'] = "CPF ou e-mail já está cadastrado. Faça login.";
    header("Location: pagina-cadastroFuncionario.html");
    exit;
} else {
    
    $stmt2 = $conn->prepare("INSERT INTO funcionario (nome_func, email_func, senha_func, tipo_usuario, cpf_func, telefone_func)
    VALUES (?, ?, ?, '2', ?, ?)");
    $stmt2->bind_param("sssiss", $nome, $email, $senha,$cpf, $telefone);

    if ($stmt2->execute()) {
        echo "ok";
    } else {
        echo "Erro ao cadastrar o funcionário: " . $stmt2->error;
    }

    $stmt2->close();
}

$stmt->close();
$conn->close();




?>


