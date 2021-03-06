<?php

class Bd {

    private $link;
    
	function __construct() {
		if (!isset ($this->link)) {
            $this->link= new mysqli('localhost', 'root', '', 'VIRTUALMARKET_PMD');
            $this->link->set_charset('utf-8'); 
		}
    }
    
    function __get($property) {
		return $this->$property;
	}
}

class Login {

    private $dni;
    private $pws;

    function __construct($dni, $pws) {
        $this->dni = $dni;
        $this->pws = $pws;
    }

    function getOne($link) {
        $queryString = "SELECT * FROM clientes WHERE dniCliente='$this->dni'";
        $result = $link->query($queryString);
        return $result->fetch_object();
    }

}

class Client implements \JsonSerializable {
    use JsonSerializer;

    private $dni;
    private $name;
    private $address;
    private $email;
    private $password;
    private $admin;

    function __construct($dni, $name, $address, $email, $password, $admin) {
        $this->dni = $dni;
        $this->name = $name;
        $this->address = $address;
        $this->email = $email;
        $this->password = $password;
        $this->admin = $admin;
    }

    function __get($property) {
        return $this->$property;
    }

    function __set($property, $value) {
        $this->$property = $value;
    }

    function save($link) {
        $queryString = "INSERT INTO clientes (dniCliente, nombre, direccion, email, pwd, administrador) VALUES
            ('$this->dni', '$this->name', '$this->address', '$this->email', '$this->password', " . (int) $this->admin . ")";
        $result = $link->query($queryString);

        return $result;
    }

    function update($link) {
        $queryString = "UPDATE clientes SET nombre='$this->name', direccion='$this->address', email='$this->email', administrador=" . (int) $this->admin . " WHERE dniCliente='$this->dni'";
        $result = $link->query($queryString);

        return $result;
    }

    function remove($link) {
        $queryString = "DELETE FROM clientes WHERE dniCliente='$this->dni'";
        $result = $link->query($queryString);

        return $result;
    }

    static function getAll($link) {
        $queryString = "SELECT * FROM clientes";
        $result = $link->query($queryString);

        $clients = [];
        while ($row = $result->fetch_assoc()) {
            $client = new Client($row['dniCliente'], $row['nombre'], $row['direccion'], $row['email'], $row['pwd'], $row['administrador']);
            array_push($clients, $client);
        }
        return $clients;
    }

    function getOne($link) {
        $queryString = "SELECT * FROM clientes WHERE dniCliente='$this->dni'";
        $result = $link->query($queryString);

        while ($row = $result->fetch_assoc()) {
            $client = new Client($row['dniCliente'], $row['nombre'], $row['direccion'], $row['email'], $row['pwd'], $row['administrador']);
        }

        return $client;
    }
}

class Product implements \JsonSerializable {
    use JsonSerializer;

    private $id;
    private $name;
    private $photo;
    private $brand;
    private $quantity;
    private $price;

    function __construct($id, $name, $photo, $brand, $quantity, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->photo = $photo;
        $this->brand = $brand;
        $this->quantity = $quantity;
        $this->price = $price;
    }
    
    function __get($property) {
        return $this->$property;
    }

    function __set($property, $value) {
        $this->$property = $value;
    }

    function save($link) {
        $queryString = "INSERT INTO productos (nombre, foto, marca, cantidad, precio) VALUES
            ('$this->name', '$this->photo', '$this->brand', $this->quantity, $this->price)";
        $result = $link->query($queryString);
        if ($result) {
            $result = $this->getLastProduct($link);
        }
        return $result;
    }

    function update($link) {
        if ($this->photo != '') {
            $queryString = "UPDATE productos SET nombre='$this->name', foto='$this->photo', marca='$this->brand', cantidad='$this->quantity', precio='$this->price' WHERE idProducto='$this->id'";
        } else {
            $queryString = "UPDATE productos SET nombre='$this->name', marca='$this->brand', cantidad='$this->quantity', precio='$this->price' WHERE idProducto='$this->id'";
        }
        $result = $link->query($queryString);
        if ($result) {
            $result = $this->getOne($link);
        }
        return $result;
    }

    function remove($link) {
        $queryString = "DELETE FROM productos WHERE idProducto='$this->id'";
        $result = $link->query($queryString);

        return $result;
    }

    static function getAll($link) {
        $queryString = "SELECT * FROM productos";
        $result = $link->query($queryString);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $product = new Product($row['idProducto'], $row['nombre'], $row['foto'], $row['marca'], $row['cantidad'], $row['precio']);
            array_push($products, $product);
        }

        return $products;
    }

    function getOne($link) {
        $queryString = "SELECT * FROM productos WHERE idProducto='$this->id'";
        $result = $link->query($queryString);

        while ($row = $result->fetch_assoc()) {
            $product = new Product($row['idProducto'], $row['nombre'], $row['foto'], $row['marca'], $row['cantidad'], $row['precio']);
        }

        return $product;
    }

    function getLastProduct($link) {
        $queryString = "SELECT * FROM productos ORDER BY idProducto DESC LIMIT 1";
        $result = $link->query($queryString);

        while ($row = $result->fetch_assoc()) {
            $product = new Product($row['idProducto'], $row['nombre'], $row['foto'], $row['marca'], $row['cantidad'], $row['precio']);
        }

        return $product;;
    }

}

class Order implements \JsonSerializable {
    use JsonSerializer;

    private $orderId;
    private $date;
    private $dniClient;

    function __construct($orderId, $date, $dniClient) {
        $this->orderId = $orderId;
        $this->date = $date;
        $this->dniClient = $dniClient;
    }

    function __get($property) {
        return $this->$property;
    }

    function __set($property, $value) {
        $this->$property = $value;
    }

    function saveOrder($link) {
        $queryString = "INSERT INTO pedidos (idPedido, fecha, dniCliente) VALUES
            ($this->orderId, '$this->date', '$this->dniClient')";
        $result = $link->query($queryString);

        if ($result) {
            return new Order($this->orderId, $this->date, $this->dniClient);
        }

        return false;
    }

    function updateOrder($link) {
        $queryString = "UPDATE pedidos SET dniCliente='$this->dniClient' WHERE idPedido=$this->orderId";
        $result = $link->query($queryString);

        if ($result) {
            return $this->getOneOrder($link);
        }

        return false;
    }

    function getNewOrderId($link) {
        $queryString = "SELECT max(idPedido) FROM pedidos";
        $result = $link->query($queryString);
        $maxId = mysqli_fetch_row($result)[0];
        
        if ($maxId === null) {
            $id = 1;
        } else {
            $id = $maxId + 1;
        }

        return $id;
    }

    static function getAllOrder($link) {
        $queryString = "SELECT * FROM pedidos";
        $result = $link->query($queryString);

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $order = new Order($row['idPedido'], $row['fecha'], $row['dniCliente']);
            array_push($orders, $order);
        }

        return $orders;
    }

    function removeOrder($link) {
        $queryString = "DELETE FROM pedidos WHERE idPedido=$this->orderId";
        $result = $link->query($queryString);

        return $result;

    }
    
    function getOneOrder($link) {
        $queryString = "SELECT * FROM pedidos WHERE idPedido=$this->orderId";
        $result = $link->query($queryString);

        while ($row = $result->fetch_assoc()) {
            $order = new Order($row['idPedido'], $row['fecha'], $row['dniCliente']);
        }

        return $order;
    }
}

class Carrito implements \JsonSerializable {
    use JsonSerializer;

    private $orderId;
    private $lineId;
    private $productId;
    private $quantity;

    function __construct($orderId, $lineId, $productId, $quantity) {
        $this->orderId = $orderId;
        $this->lineId = $lineId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
    
    function __get($property) {
        return $this->$property;
    }

    function __set($property, $value) {
        $this->$property = $value;
    }

    function updateLine($link) {
        $queryString = "UPDATE lineas_pedidos SET cantidad=$this->quantity, idProducto=$this->productId WHERE idPedido=$this->orderId AND nLinea=$this->lineId";
        $result = $link->query($queryString);

        if ($result) {
            $result = $this->getAllLineOfOrder($link);
        }

        return $result;
    }

    function saveLineOrder($link) {
        $queryString = "INSERT INTO lineas_pedidos (nLinea, cantidad, idPedido, idProducto) VALUES
            ('$this->lineId', '$this->quantity', '$this->orderId', '$this->productId')";
        $result = $link->query($queryString);

        if ($result) {
            $result = $this->getAllLineOfOrder($link);
        }
        return $result;
    }

    function getNewLineId($link) {
        $queryString = "SELECT max(nLinea) FROM lineas_pedidos WHERE idPedido=$this->orderId";
        $result = $link->query($queryString);
        $maxId = mysqli_fetch_row($result)[0];
        
        if ($maxId === null) {
            $id = 1;
        } else {
            $id = $maxId + 1;
        }

        return $id;
    }

    function getOneLine($link) {
        $queryString = "SELECT * FROM lineas_pedidos WHERE idPedido=$this->orderId AND nLinea=$this->lineId";
        $result = $link->query($queryString);

        while ($row = $result->fetch_assoc()) {
            $order = new Carrito($row['idPedido'], $row['nLinea'], $row['idProducto'], $row['cantidad']);
        }

        return $order;
    }

    function getAllLineOfOrder($link) {
        $queryString = "SELECT * FROM lineas_pedidos WHERE idPedido=$this->orderId";
        $result = $link->query($queryString);

        $lines = [];
        while ($row = $result->fetch_assoc()) {
            $line = new Carrito($this->orderId, $row['nLinea'], $row['idProducto'], $row['cantidad']);
            array_push($lines, $line);
        }

        return $lines;
    }

    function removeLinesOfOrder($link) {
        $queryString = "DELETE FROM lineas_pedidos WHERE idPedido=$this->orderId";
        $result = $link->query($queryString);

        return $result;
    }

    function removeLine($link) {
        $queryString = "DELETE FROM lineas_pedidos WHERE nLinea=$this->lineId AND idPedido=$this->orderId";
        $result = $link->query($queryString);

        return $result;
    }

}

trait JsonSerializer {
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}