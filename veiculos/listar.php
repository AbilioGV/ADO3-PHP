<?php

session_start();


// verificando se o usuario está logado.
if (!isset($_SESSION["usuario_logado"]))
    header("Location: ../login/login.php");

// Para fazer o Logout.
if (isset($_GET["sair"])) {
    unset($_SESSION["usuario_logado"]);
    header("Location: ../login/login.php");
}

try {

    include "conectar.php";

    $pdo = conectar();

    $stmt = $pdo->prepare("SELECT nome FROM usuario WHERE chave= :id ");

    $stmt->execute(["id" => $_SESSION["usuario_logado"]]);

    $dados_do_usuario = $stmt->fetch();
} catch (Exception $e) {

    echo $e->getMessage();
}

try {
    include "abrir_transacao.php";
    include_once "operacoes.php";

?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="utf-8">
        <title>Listagem de veiculos</title>
    </head>

    <body>
        <h2>Seja bem-vindo <b><?= $dados_do_usuario["nome"] ?></b></h2>
        <?php $resultado = listar_todos_veiculos(); ?>
        <table>
            <tr>
                <th scope="column">Chave</th>
                <th scope="column">Marca</th>
                <th scope="column">Modelo</th>
                <th scope="column">Ano</th>
                <th scope="column">Placa</th>
                <th scope="column">Cor</th>
                <th scope="column">Tipo</th>
                <th scope="column"></th>
                <th scope="column"></th>
            </tr>
            <?php foreach ($resultado as $linha) { ?>
                <tr>
                    <td><?= $linha["chave"] ?></td>
                    <td><?= $linha["marca"] ?></td>
                    <td><?= $linha["modelo"] ?></td>
                    <td><?= $linha["ano"] ?></td>
                    <td><?= $linha["placa"] ?></td>
                    <td><?= $linha["cor"] ?></td>
                    <td><?= $linha["tipo"] ?></td>
                    <td>
                        <button type="button">
                            <a href="cadastrar.php?chave=<?= $linha["chave"] ?>">Editar</a>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <button type="button"><a href="cadastrar.php">Criar novo</a></button>
        <a href="listar.php?sair=true">Sair</a>
    </body>

    </html>

<?php

    $transacaoOk = true;
} finally {
    include "fechar_transacao.php";
}
?>