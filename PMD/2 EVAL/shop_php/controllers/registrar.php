<?php
require '../modelo.php';
require '../utils.php';

$db = new Bd();

/** Se verifica las contraseñas antes de registrar el usuario */
if (isset($_POST['password']) && isset($_POST['password_verify'])) {
    if ($_POST['password'] === $_POST['password_verify']) {
        $url = 'http://localhost/services/clients/addClientController.php';
        $data = array(
            'dni' =>  $_POST['dni'],
            'name' =>  $_POST['name'],
            'address' =>  $_POST['address'],
            'email' =>  $_POST['email'],
            'password' =>  $_POST['password']
        );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $client = json_decode(file_get_contents($url, false, $context));

        if ($client->dni != '') {
            [$dni, $tempClientId] = getDniClientAndTempClientId();
            $soppingCartModel = new ShoppingCart(0, '', $_POST['dni'], $tempClientId, 0, 0);
            $shoppingCartTotal = $soppingCartModel->updateShoppingCartToLoggedClient($db->link);
    
            setcookie('user_name', $_POST['name'], time() + 3600);
            setcookie('dni', $_POST['dni'], time() + 3600);
            header('Location: shop');
        } else {
            $error = 'Ha habido un error al registrate, puede que el dni ya esté registrado';
            require '../views/sign-up.php';
        }        
    } else {
        $error = 'Las contraseñas no coinciden';
        require '../views/sign-up.php';
    }
} else {
    require '../views/sign-up.php';
}

