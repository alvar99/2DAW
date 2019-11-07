<?php

class Bd {

    private $link;
    
	function __construct() {
		if (!isset ($this->link)) {
            $this->link= new mysqli('localhost', 'root', '', 'VIRTUALMARKET_PMD');
            $this->link->set_charset('utf-8'); 
		}
    }
    
    function __get($var) {
		return $this->$var;
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

    function __get($var) {
        $this->$var;
    }

    function __set($key, $value) {
        $this->$key = $value;
    }

    function save($link) {
        $queryString = "INSERT INTO clientes (dniCliente, nombre, direccion, email, pwd, administrador) VALUES
            ('$this->dni', '$this->name', '$this->address', '$this->email', '$this->password', '$this->admin')";
        $result = $link->query($queryString);

        return $result;
    }

    function update($link) {
        $queryString = "UPDATE clientes SET nombre='$this->name', direccion='$this->address', email='$this->email', pwd='$this->password', administrador='$this->admin' WHERE dniCliente='$this->dni'";
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

        return $result->fetch_object();
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

    function __construct() {
    }
    
    function __get($var) {
        $this->$var;
    }

    function __set($key, $value) {
        $this->$key = $value;
    }

    function save($link) {
        $queryString = "INSERT INTO productos (idProducto, nombre, foto, marca, cantidad, precio) VALUES
            ('$this->id', '$this->name', '$this->photo', '$this->brand', '$this->quantity', '$this->price')";
        $result = $link->query($queryString);

        return $result;
    }

    function update($link) {
        $queryString = "UPDATE productos SET nombre='$this->name', foto='$this->photo', marca='$this->brand', cantidad='$this->quantity', precio='$this->price' WHERE idProducto='$this->id'";
        $result = $link->query($queryString);

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
            $product = new Producto($row['idProducto'], $row['nombre'], $row['foto'], $row['marca'], $row['cantidad'], $row['precio']);
            array_push($products, $product);
        }

        return $products;
    }

    function getOne($link) {
        $queryString = "SELECT * FROM productos WHERE idProducto='$this->id'";
        $result = $link->query($queryString);

        return $result->fetch_object();
    }

}

trait JsonSerializer {
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}