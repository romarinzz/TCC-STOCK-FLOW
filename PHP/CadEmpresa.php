<?php

session_start();

// i = integer 
// s = string
//tem a protecao contra sql injection
include "conexao.php";

$nome = $_POST['nome'];
$telefone = trim($_POST['telefone']);
$cnpj = trim($_POST['cnpj']);
$email =trim($_POST['email']);
$senha =trim($_POST['senha']);
$cep = trim($_POST['cep']);
$endereco =$_POST['endereco'];
$numero =trim($_POST['numero']);
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado =$_POST['estado'];

        if ($result2->num_rows == 0) {
            $sql3 = $conn->prepare("INSERT INTO empresa (nome_empresa, cnpj, telefone,cep,endereco,numero,bairro,estado,cidade ,email ,senha) 
                                    VALUES (?, ?, ?, ?,?,?,?,?,?,?,?,?)");
            $sql3->bind_param("sssssisssss",$nome, $cnpj, $telefone,$cep,$endereco,$numero,$bairro,$estado,$cidade,$email,$senha);

            if ($sql3->execute()) {
                echo "Empresa cadastrada com sucesso!";
            } else {
                echo "Erro ao cadastrar empresa: " . $sql3->error;
            }
        }
    

$conn->close();
?>


