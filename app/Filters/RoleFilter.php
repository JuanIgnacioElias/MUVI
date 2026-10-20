<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Verificar sesión activa
        if (!$session->has('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión.');
        }

        // 2. Obtener el rol guardado (vendrá como 'CLIENTE', 'ADMINISTRADOR', etc.)
        $rolUsuario = $session->get('role'); 

        // 3. Validar contra los argumentos permitidos pasados a mayúsculas
        if (!empty($arguments)) {
            $rolesPermitidos = array_map('strtoupper', $arguments);

            if (!in_array($rolUsuario, $rolesPermitidos)) {
                return redirect()->to('/dashboard')->with('error', 'No tienes permisos.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
