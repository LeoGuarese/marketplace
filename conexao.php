<?php
$conn = pg_connect("host=localhost dbname=projetoWeb user=postgres password=123");

if (!$conn) {
    die("Erro na conexão");
}
?>