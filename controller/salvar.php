<?php

session_start();

include("../conexao.php");
include("../model/Cliente.php");
include("../model/Fornecedor.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$senha = $_POST['senha'];
$tipo = $_POST['tipo'];

if ($tipo == "cliente") {

    $usuario = new Cliente($conn);
    $redirect = "../view/cadastroCliente.php";

} else if ($tipo == "fornecedor") {

    $usuario = new Fornecedor($conn);
    $redirect = "../view/cadastroFornecedor.php";

} else {

    die("Tipo inválido");
}

if ($usuario->emailExiste($email)) {

    $_SESSION['erro'] = "Email já cadastrado!";

    header("Location: $redirect");
    exit;
}

$result = $usuario->cadastrar(
    $nome,
    $telefone,
    $email,
    $senha
);

if ($result) {
    $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao realizar cadastro.";
}

header("Location: $redirect");
exit;

?>