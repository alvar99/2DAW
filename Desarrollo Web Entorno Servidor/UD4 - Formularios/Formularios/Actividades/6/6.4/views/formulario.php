<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subir foto</title>
</head>
<body>
    <form action="controller.php" method="post" enctype="multipart/form-data">
        Fichero: <input type="file" name="file"><br><br>
        <?php echo lista('directorio', $options) ?><br><br>

        <input type="submit" name="sendFile" value="Enviar">
    </form>
</body>
</html>