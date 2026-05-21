<?php

session_start();

include("../conexao.php");
include("../model/Cliente.php");
include("../model/Fornecedor.php");
include("../model/Admin.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../view/login1.php");
    exit;
}

$email = $_POST['email'];
$senha = $_POST['senha'];

$adminModel = new Admin($conn);

$admin = $adminModel->buscarPorEmail($email);

if ($admin) {

    if (password_verify($senha, $admin['senha'])) {

        $_SESSION['id'] = $admin['id'];
        $_SESSION['nome'] = $admin['nome'];
        $_SESSION['tipo'] = "admin";

        header("Location: ../view/homeAdmin.php");
        exit;
    }
}



$tipo = $_POST['tipo'];

if ($tipo == "cliente") {

    $usuarioModel = new Cliente($conn);
    $redirect = "../view/homeCliente.php";

} else if ($tipo == "fornecedor") {

    $usuarioModel = new Fornecedor($conn);
    $redirect = "../view/homeFornecedor.php";

}
else {

    $_SESSION['erro'] = "Tipo inválido";

    header("Location: ../view/login1.php");
    exit;
}

$user = $usuarioModel->buscarPorEmail($email);

if ($user) {

    if (password_verify($senha, $user['senha'])) {

        $_SESSION['id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['tipo'] = $tipo;

        header("Location: $redirect");
        exit;

    } else {

        $_SESSION['erro'] = "Credencial(is) incorreta(s)";
    }

} else {

    $_SESSION['erro'] = "Credencial(is) incorreta(s)";
}

header("Location: ../view/login1.php");
exit;

?>