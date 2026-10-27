<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pago</title>
</head>
<body>

    <h2>Registrar Pago</h2>

    <form action="http://localhost:8080/index.php/pago/guardarWeb" method="POST">
    <?= csrf_field() ?>
 
        <p>
            <label>Monto:</label><br>
            <input type="number" name="monto" required>
        </p>

        <p>
            <label>Moneda:</label><br>
            <input type="text" name="moneda" placeholder="Ej: ARS" required>
        </p>

        <p>
            <label>Método de Pago:</label><br>
            <input type="text" name="metodo_pago" placeholder="Ej: Tarjeta" required>
        </p>

        <p>
        <input type="hidden" name="estado" value="Pendiente">
        </p>

        <button type="submit">Guardar Pago</button>
        <a href="http://localhost:8080/index.php/Pago">Volver atrás</a>
    </form>

</body>
</html>
