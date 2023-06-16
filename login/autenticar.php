<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        include "conectar.php";
        $pdo = conectar();

        $stmt = $pdo->prepare("SELECT CHAVE AS id, SENHA AS senha FROM USUARIO WHERE login = :login");
        $stmt->execute(["login" => $_POST["usuario"]]);
        $dados_do_usuario = $stmt->fetchObject();

        if ($dados_do_usuario && $_POST["senha"] === $dados_do_usuario->senha) {
            $_SESSION["usuario_logado"] = $dados_do_usuario->id;
            header("Location: ../veiculos/listar.php");
            exit();
        } else {
            header("Location: Login.php?mensagem="."Usuário ou senha incorretos.");
            exit();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>