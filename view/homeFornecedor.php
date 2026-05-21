<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login1.php");
    exit;
}
?>

<?php include("../styles/navbar.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
    <link rel="stylesheet" href="../styles/styleHomeFornecedor.css">
</head>
<body>

</body>
</html>