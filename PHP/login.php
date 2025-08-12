<?php 
session_start();

include "conexao.php";

$email =$_POST['email'];
$senha =$_POST['senha'];

$stmt = $con->prepare("SELECT * FROM funcionario WHERE email = ?");
if (empty($_POST['email']) || empty($_POST['senha'])) {
    echo "<script type='text/javascript'>
    alert('Preencha Todos os campos!');
    window.location.href = '../pagina-login.html';
    </script>";
    exit();
}
//verifica se existe o email primeiro

$stmt = $con->prepare("SELECT * FROM funcionario WHERE email = ?");

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();




if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if ($senha === $user['senha']) {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
        $_SESSION['cpf_usuario'] = $user['cpf'];
        header('Location: ../pagina-inicial.html');//tudo certo
        exit();
    }
}
echo "<script type='text/javascript'>
    alert('Usuario não encontrado!');
    window.location.href = '../pagina-login.html';
    </script>"; //nao encontrou
exit();
?>







