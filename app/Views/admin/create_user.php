<form action="/admin/processCreateUser" method="post">
    <!-- Campos de nombre, apellido, email, password, cuil... -->
    
    <label for="role">Rol del Usuario:</label>
    <select name="role" id="role" required>
        <option value="EMPLEADO">Empleado</option>
        <option value="ADMINISTRADOR">Administrador</option>
        <option value="CLIENTE">Cliente</option>
    </select>

    <button type="submit">Registrar Usuario de Sistema</button>
</form>
