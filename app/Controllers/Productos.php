<?php
namespace App\Controllers;
use App\Models\ProductoModel;
use CodeIgniter\Exceptions\PageNotFoundException;
class Productos extends BaseController
{
 private ProductoModel $productos;
 public function __construct()
 {
 $this->productos = new ProductoModel();
 }
 // R: listado
 public function index()
 {
 return view('productos/index', [
 'productos' => $this->productos->orderBy('nombre')->findAll(),
 ]);
 }
 // C: formulario vacío
 public function nuevo()
 {
 return view('productos/form', ['producto' => null]);
 }
 // C: recibe el formulario y guarda
 public function guardar()
 {
 $datos = $this->request->getPost(['nombre', 'descripcion', 'precio', 'stock']);
 if (! $this->productos->insert($datos)) {
 return redirect()->back()->withInput()
 ->with('errores', $this->productos->errors());
 }
 return redirect()->to('/productos')->with('mensaje', 'Producto creado.');
 }
 // U: formulario con los datos actuales
 public function editar(int $id)
 {
 return view('productos/form', ['producto' => $this->buscar($id)]);
 }
 // U: recibe los cambios y actualiza
 public function actualizar(int $id)
 {
 $this->buscar($id);
 $datos = $this->request->getPost(['nombre', 'descripcion', 'precio', 'stock']);
 if (! $this->productos->update($id, $datos)) {
 return redirect()->back()->withInput()
 ->with('errores', $this->productos->errors());
 }
 return redirect()->to('/productos')->with('mensaje', 'Producto actualizado.');
 }
 // D: borra
 public function eliminar(int $id)
 {
 $this->buscar($id);
 $this->productos->delete($id);
 return redirect()->to('/productos')->with('mensaje', 'Producto eliminado.');
 }
 // Si el id no existe, corta con un 404
 private function buscar(int $id): array
 {
 $producto = $this->productos->find($id);
 if ($producto === null) {
 throw PageNotFoundException::forPageNotFound('No existe el producto ' . $id);
 }
 return $producto;
 }
}