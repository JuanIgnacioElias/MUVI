<?php
namespace App\Controllers;
use App\Models\CompraModel;
use CodeIgniter\Exceptions\PageNotFoundException;
class Compras extends BaseController
{ 
    private CompraModel $compras; 
    
    public function __construct() 
        { 
            $this->compras = new CompraModel(); 
        } 
 // R: listado
    public function index()
    {
        $rol = session()->get('role');

        $builder = $this->compras
            ->orderBy('fecha_compra', 'DESC');

        if ($rol === 'CLIENTE') {
            $builder->where('user_id', session()->get('id'));
        } elseif (! in_array($rol, ['EMPLEADO', 'ADMINISTRADOR'], true)) {
            return redirect()->to('/login');
        }

        return view('compras/index', [
            'compras' => $builder->findAll(),
        ]);
    }

    // C: formulario vacío 
    public function nuevo() 
    { 
        return view('compras/form', ['compra' => null]); 
    } 

    // C: recibe el formulario y guarda 
    public function guardar() 
    { 
        $datos = $this->request->getPost(['total', 'estado', 'fecha_compra', 'codigo_qr']); 

        if (! $this->compras->insert($datos)) { 
            return redirect()->back()->withInput() 
                ->with('errores', $this->compras->errors()); 
        } 

        return redirect()->to('/compras')->with('mensaje', 'Compra creada.'); 
    } 

    // U: formulario con los datos actuales 
    public function editar(int $id_compra) 
    { 
        return view('compras/form', ['compra' => $this->buscar($id_compra)]); 
    } 

    // U: recibe los cambios y actualiza 
    public function actualizar(int $id_compra) 
    { 
        $this->buscar($id_compra); 
        
        $datos = $this->request->getPost(['total', 'estado', 'fecha_compra', 'codigo_qr']); 

        if (! $this->compras->update($id_compra, $datos)) { 
            return redirect()->back()->withInput() 
                ->with('errores', $this->compras->errors()); 
        } 

        return redirect()->to('/compras')->with('mensaje', 'Compra actualizada.'); 
    } 

    // D: borra 
    public function eliminar(int $id_compra) 
    { 
        $this->buscar($id_compra); 
        $this->compras->delete($id_compra); 
        return redirect()->to('/compras')->with('mensaje', 'Compra eliminada.'); 
    } 

    // Si el id no existe, corta con un 404 
    private function buscar(int $id_compra): array 
    { 
        $compra = $this->compras->find($id_compra); 

        if ($compra === null) { 
            throw PageNotFoundException::forPageNotFound('No existe la compra #' . $id_compra); 
        } 

        return $compra; 
    } 
}