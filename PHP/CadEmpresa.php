<?php

session_start();

// i = integer 
// s = string
//tem a protecao contra sql injection
include "conexao.php";

$nome = trim($_POST['nome']);
$telefone = trim($_POST['telefone']);
$cnpj = trim($_POST['cnpj']);

$id_usuario = $_SESSION['id_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

if ($tipo_usuario == 1) {
    //coleta o id do usuari
    $smt = $conn->prepare("SELECT id_usuario FROM usuario WHERE cpf = ?");
    $smt->bind_param("s", $cpf_usuario);
    $smt->execute();
    $result = $smt->get_result();
 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usuario_id = $row['id_usuario'];
         //verifica se o usuario ja possui uma empresa
        $sql2 = $conn->prepare("SELECT id_empresa FROM empresa WHERE cod_usuario = ?");
        $sql2->bind_param("i", $usuario_id);
        $sql2->execute();
        $result2 = $sql2->get_result();
         //se nao tiver nenhuma empresa cadastrada , ai vai para o processo de cadastrar empresa
        if ($result2->num_rows == 0) {
            $sql3 = $conn->prepare("INSERT INTO empresa (cod_usuario, nome_empresa, cnpj_empresa, telefone_empresa) 
                                    VALUES (?, ?, ?, ?)");
            $sql3->bind_param("isss", $usuario_id, $nome, $cnpj, $telefone);

            if ($sql3->execute()) {
                echo "Empresa cadastrada com sucesso!";
            } else {
                echo "Erro ao cadastrar empresa: " . $sql3->error;
            }
        } else if ($result2->num_rows > 0) {
            echo "Usuário já tem empresa cadastrada.";
        }
    } else if($result->num_rows == 0){
        echo "Usuário não encontrado ou n.";
    }
} else if ($tipo_usuario == 0) {
    echo "Você não tem permissão para cadastrar empresa.";
}

$conn->close();
?>


