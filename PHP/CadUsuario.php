<?php

session_start();
//i = integer
//s = string
require "conexao.php";


$nome= trim($_POST['nome']);
$email= trim($_POST['email']);
$senha_original = trim($_POST['senha']); //senha depois vai ser criptografad
$telefone= trim($_POST['telefone']);
$tipo= trim($_POST['tipo']);
$cpf= trim($_POST['cpf']);

//verificacao dos campos
if (empty($nome) || empty($email) || empty($senha) || empty($telefone) || empty($tipo) || empty($cpf)) {
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
    header("Location: login.php");
    exit;
} else {
    
    $stmt2 = $conn->prepare("INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario, tipo_usuario, cpf_usuario, telefone_usuario)
    VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("sssiss", $nome, $email, $senha, $tipo, $cpf, $telefone);

    if ($stmt2->execute()) {
        echo "ok";
    } else {
        echo "Erro ao cadastrar o usuário: " . $stmt2->error;
    }

    $stmt2->close();
}

$stmt->close();
$conn->close();




?>


