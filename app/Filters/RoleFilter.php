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
        if (
            ! $session->get('isLoggedIn') ||
            ! $session->get('id')
        ) {
            return redirect()->to('/login')
                ->with('error', 'Iniciá sesión para continuar.');
        }
        $rol = strtoupper((string) $session->get('role'));
        $permitidos = array_map(
            'strtoupper',
            $arguments ?? []
        );
        if (! in_array($rol, $permitidos, true)) {
            return redirect()->to($this->panelSegunRol($rol))
                ->with('error', 'No tenés permiso para acceder a esa sección.');
        }
        return null;
    }
    private function panelSegunRol(string $rol): string
    {
        switch ($rol) {
            case 'ADMINISTRADOR':
                return '/admin/dashboard';
            case 'EMPLEADO':
                return '/empleado/panel';
            case 'CLIENTE':
                return '/cliente/mi-cuenta';
            default:
                session()->destroy();
                return '/login';
        }
    }
    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // Sin acciones posteriores.
    }
}