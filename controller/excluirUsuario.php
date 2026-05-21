<?php

session_start();

if (!isset($_SESSION['id']) ||
    $_SESSION['tipo'] != 'admin') {

    header("Location: ../view/login1.php");
    exit;
}

include("../conexao.php");
include("../model/Admin.php");

$id = $_GET['id'];
$tipo = $_GET['tipo'];

$admin = new Admin($conn);

if ($tipo == "cliente") {

    $admin->excluirCliente($id);

} else if ($tipo == "fornecedor") {

    $admin->excluirFornecedor($id);
}

header("Location: ../view/homeAdmin.php");
exit;

?>