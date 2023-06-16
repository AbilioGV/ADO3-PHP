<?php

?>
<!DOCTYPE html>
<html>

<head>
    <title>Página de Login</title>
</head>

<body>
    <h2>Página de Login</h2>
    <?php if (!empty($_REQUEST["mensagem"])) { ?>
        <p><?php echo $_REQUEST["mensagem"]; ?></p>
    <?php } ?>
    <form method="POST" action="autenticar.php">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" id="usuario" required><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required><br>

        <input type="submit" value="Entrar">
    </form>
</body>

</html>