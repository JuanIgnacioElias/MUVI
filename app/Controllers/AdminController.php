<?php
namespace App\Controllers;
use App\Models\UserModel;
class AdminController extends BaseController
{
    // Muestra el dashboard principal del administrador
    public function index()
    {
        // Pagos almacenados, del más nuevo al más viejo, con el usuario que los hizo.
        // (Los pagos anteriores a esta función no tienen usuario asociado.)
        $pagos = db_connect()->table('pagos')
            ->select('pagos.*, users.name AS usuario_nombre, users.last_name AS usuario_apellido, users.email AS usuario_email')
            ->join('users', 'users.id = pagos.id_usuario', 'left')
            ->orderBy('pagos.created_at', 'DESC')
            ->orderBy('pagos.id_pago', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/dashboard', ['pagos' => $pagos]);
    }

    // Lista todos los usuarios registrados
    public function listarUsuarios()
    {
        $userModel = new UserModel();
        
        // Obtenemos todos los usuarios de la base de datos
        $data['usuarios'] = $userModel->findAll();

        return view('admin/usuarios', $data);
    }

    // Procesa el cambio de rol enviado desde el formulario
    public function actualizarRol()
    {
        $userModel = new UserModel();

        $userId = $this->request->getPost('user_id');
        $nuevoRol = strtoupper($this->request->getPost('role')); // Aseguramos mayúsculas

        // Validamos que el rol sea uno de los tres permitidos
        $rolesPermitidos = ['CLIENTE', 'EMPLEADO', 'ADMINISTRADOR'];
        if (!in_array($nuevoRol, $rolesPermitidos)) {
            return redirect()->back()->with('error', 'El rol seleccionado no es válido.');
        }

        // Evitar que el administrador se cambie el rol a sí mismo (para no perder acceso)
        if ($userId == session()->get('id')) {
            return redirect()->back()->with('error', 'No puedes cambiar tu propio rol de Administrador.');
        }

        // Actualizamos el campo 'role' en la base de datos
        $actualizado = $userModel->update($userId, [
            'role' => $nuevoRol
        ]);

        if ($actualizado) {
            return redirect()->to('/admin/usuarios')->with('exito', 'El rol del usuario fue actualizado correctamente.');
        } else {
            return redirect()->back()->with('error', 'No se pudo actualizar el rol.');
        }
    }
}
