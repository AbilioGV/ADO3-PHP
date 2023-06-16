<?php
try {
    include "abrir_transacao.php";
include_once "operacoes.php";

$tipos = listar_todos_tipos();

function validar($veiculo) {
    global $tipos;

    $carro = buscar_veiculo_por_placa($veiculo["placa"]);
    if ($carro) {
        if (!isset ($veiculo["chave"]) || $veiculo["chave"] != $carro["chave"]) {
            die ("Já existe um veículo com essa placa");  
        }
    }

    return $veiculo["ano"] >= 1900
        && strlen($veiculo["placa"]) == 7
        && strlen($veiculo["modelo"]) <= 50
        && strlen($veiculo["cor"]) <= 50
        && strlen($veiculo["marca"]) <= 50
        && in_array($veiculo["tipo"], $tipos, true);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $alterar = isset($_GET["chave"]);
    if ($alterar) {
        $chave = $_GET["chave"];
        $veiculo = buscar_veiculo($chave);
        if ($veiculo == null) die("Não existe!");
    } else {
        $chave = "";
        $veiculo = [
            "chave" => "",
            "marca" => "",
            "modelo" => "",
            "ano" => "",
            "placa" => "",
            "cor" => "",
            "tipo" => ""
        ];
    }
    $validacaoOk = true;

} else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $alterar = isset($_POST["chave"]);

    if ($alterar) {
        $veiculo = [
            "chave" => $_POST["chave"],
            "marca" => $_POST["marca"],
            "modelo" => $_POST["modelo"],
            "ano" => $_POST["ano"],
            "placa" => $_POST["placa"],
            "cor" => $_POST["cor"],
            "tipo" => $_POST["tipo"]
        
        ];
        $validacaoOk = validar($veiculo);
        if ($validacaoOk) alterar_veiculo($veiculo);
    } else {
        $veiculo = [
            "marca" => $_POST["marca"],
            "modelo" => $_POST["modelo"],
            "ano" => $_POST["ano"],
            "placa" => $_POST["placa"],
            "cor" => $_POST["cor"],
            "tipo" => $_POST["tipo"]
        ];
        $validacaoOk = validar($veiculo);
        if ($validacaoOk) $id = inserir_veiculo($veiculo);
    }

    if ($validacaoOk) {
        header("Location: listar.php");
        $transacaoOk = true;
    }
} else {
    die("Método não aceito");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Cadastro de veículos</title>
        <script>
            function confirmar() {
                if (!confirm("Tem certeza que deseja salvar os dados?")) return;
                document.getElementById("formulario").submit();
            }

            function excluir() {
                if (!confirm("Tem certeza que deseja excluir o veículo?")) return;
                document.getElementById("excluir-veiculo").submit();
            }
        </script>
    </head>
    <body>
        <form method="POST" action="cadastrar.php" id="formulario">
            <?php if (!$validacaoOk) {?>
                <div>
                    <p>Preencha os campos corretamente!</p>
                </div>
            <?php } ?>
            <?php if ($alterar) { ?>
                <div>
                    <label for="chave">Chave:</label>
                    <input type="text" id="chave" name="chave" value="<?= $veiculo["chave"] ?>" readonly>
                </div>
            <?php } ?>
            <div>
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" value="<?= $veiculo["marca"] ?>">
            </div>
            <div>
                <label for="modelo">Modelo:</label>
                <input type="text" id="modelo" name="modelo" value="<?= $veiculo["modelo"] ?>">
            </div>
            <div>
                <label for="ano">Ano:</label>
                <input type="text" id="ano" name="ano" value="<?= $veiculo["ano"] ?>">
            </div>
            <div>
                <label for="placa">Placa:</label>
                <input type="text" id="placa" name="placa" value="<?= $veiculo["placa"] ?>">
            </div>
            <div>
                <label for="cor">Cor:</label>
                <input type="text" id="cor" name="cor" value="<?= $veiculo["cor"] ?>">
            </div>
            <div>
                <label for="tipo">Tipo de veículo:</label>
                <select id="tipo" name="tipo">
                    <option>Escolha...</option>
                    <?php foreach ($tipos as $tipo) { ?>
                        <option value="<?= $tipo ?>"
                            <?php if ($veiculo["tipo"] === $tipo) { ?>
                            selected
                            <?php } ?>
                        >
                            <?= $tipo ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <button type="button" onclick="confirmar()">Salvar</button>
            </div>
        </form>
        <?php if ($alterar) { ?>
            <form action="excluir.php"
                    method="POST"
                    style="display: none"
                    id="excluir-veiculo">
                <input type="hidden" name="chave" value="<?= $veiculo["chave"] ?>" >
            </form>
            <button type="button" onclick="excluir()">Excluir</button>
        <?php } ?>
    </body>
</html>

<?php
$transacaoOk = true;

} finally {
    include "fechar_transacao.php";
}
?>