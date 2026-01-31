<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Valor recibido: " . htmlspecialchars($_POST['data']);
}
?>

<form method="POST">
    <input type="text" name="data">
    <input type="submit" value="Enviar">
</form>

