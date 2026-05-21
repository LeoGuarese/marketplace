<?php
include("../conexao.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$senha = $_POST['senha'];

$fornecedor = new Fornecedor($conn);

$result = $fornecedor->cadastrar(
    $nome,
    $telefone,
    $email,
    $senha
);

if ($result) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro ao cadastrar.";
}
?>