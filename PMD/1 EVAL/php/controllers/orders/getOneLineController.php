<?php
require '../../modelo.php';

$db = new Bd();

if (isset($_GET)) {
    $idOrder = $_GET['idOrder'];
    $idLine = $_GET['idLine'];

    $cartModel = new Carrito($idOrder, $idLine, '', '');
    $result = $cartModel->getOneLine($db->link);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode('ERROR');
    }
}