<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pago</title>
</head>
<body>

    <h2>Registrar Pago</h2>

    <!-- El action apunta directo a la ruta de tu controlador -->
    <form action="http://localhost:8080/pago" method="POST">
        
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
            <label>Estado:</label><br>
            <select name="estado" required>
                <option value="Pendiente">Pendiente</option>
                <option value="Aprobado">Aprobado</option>
            </select>
        </p>

        <button type="submit">Guardar Pago</button>
        <a href="index.php">Volver atrás</a>
    </form>

</body>
</html>
