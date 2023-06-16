<?php
include_once "conectar.php";
$pdo = conectar();
$transacaoOk = false;
$pdo->beginTransaction();