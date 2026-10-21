<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pagos</title>
</head>
<body>

    <h2>Historial de Pagos</h2>
    <!-- Botón para ir al formulario -->
    <a href="form.php">Registrar Nuevo Pago</a>
    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Monto</th>
                <th>Moneda</th>
                <th>Método</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Conexión simple a la API para traer los datos
            $json = file_get_contents('http://localhost:8080/pago');
            $pagos = json_decode($json, true);

            if (!empty($pagos)) {
                foreach ($pagos as $pago) {
                    echo "<tr>";
                    echo "<td>" . $pago['id_pago'] . "</td>";
                    echo "<td>" . $pago['monto'] . "</td>";
                    echo "<td>" . $pago['moneda'] . "</td>";
                    echo "<td>" . $pago['metodo_pago'] . "</td>";
                    echo "<td>" . $pago['estado'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay pagos grabados.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
