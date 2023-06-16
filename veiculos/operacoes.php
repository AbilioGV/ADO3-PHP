<?php

include_once "conectar.php";

function inserir_veiculo($veiculo) {
    global $pdo;
    $sql = "INSERT INTO veiculo (marca, modelo, ano, placa, cor, tipo) " .
            "VALUES (:marca, :modelo, :ano, :placa, :cor, :tipo)";
    $pdo->prepare($sql)->execute($veiculo);
    return $pdo->lastInsertId();
}

function alterar_veiculo($veiculo) {
    global $pdo;
    $sql = "UPDATE veiculo SET " .
            "marca = :marca, " .
            "modelo = :modelo, " .
            "ano = :ano, " .
            "placa = :placa, ".
            "cor = :cor, " .
            "tipo = :tipo " .
            "WHERE chave = :chave";
    $pdo->prepare($sql)->execute($veiculo);
}

function excluir_veiculo($chave) {
    global $pdo;
    $sql = "DELETE FROM veiculo WHERE chave = :chave";
    $pdo->prepare($sql)->execute(["chave" => $chave]);
}

function listar_todos_veiculos() {
    global $pdo;
    $sql = "SELECT * FROM veiculo";
    $resultados = [];
    $consulta = $pdo->query($sql);
    while ($linha = $consulta->fetch()) {
        $resultados[] = $linha;
    }
    return $resultados;
}

function buscar_veiculo($chave) {
    global $pdo;
    $sql = "SELECT * FROM veiculo WHERE chave = :chave";
    $resultados = [];
    $consulta = $pdo->prepare($sql);
    $consulta->execute(["chave" => $chave]);
    return $consulta->fetch();
}

function listar_todos_tipos() {
    global $pdo;
    $sql = "SELECT * FROM tipo_veiculo";
    $resultados = [];
    $consulta = $pdo->query($sql);
    while ($linha = $consulta->fetch()) {
        $resultados[] = $linha["tipo"];
    }
    return $resultados;
}

function buscar_veiculo_por_placa($placa) {
    global $pdo;
    $sql = "SELECT * FROM veiculo WHERE placa = :placa";
    $resultados = [];
    $consulta = $pdo->prepare($sql);
    $consulta->execute(["placa" => $placa]);
    return $consulta->fetch();
}