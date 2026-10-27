<?php
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (
            ! $session->get('isLoggedIn') ||
            ! $session->get('id')
        ) {
            return redirect()->to('/login')
                ->with('error', 'Iniciá sesión para continuar.');
        }
        return null;
    }
    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
    }
}
