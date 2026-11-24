<?php

namespace App\Controllers;

use App\Models\PagoModel;
use CodeIgniter\API\ResponseTrait;

class Pago extends BaseController
{
    use ResponseTrait;

    protected $pagoModel;

    public function __construct()
    {
        $this->pagoModel = new PagoModel();
    }

    // =========================================================================
    // VISTAS WEB (Control de flujo e Interfaz del Navegador)
    // =========================================================================

    /**
     * Muestra la vista principal. 
     * Con la inversión de flujos, esta ruta carga el Formulario de Pago para Clientes.
     * Si se pasa el parámetro ?admin=true en la URL, renderiza la tabla de auditoría.
     */
    public function index()
    {
        $db = db_connect();
        $session = session();

        // 1. Datos de la Función, Película y Sala
        $funcionDetalle = null;
        $idFuncion = $session->get('id_funcion');
        if ($idFuncion) {
            $funcionDetalle = $db->table('funciones f')
                ->select('f.*, p.titulo AS pelicula_titulo, s.nombre AS sala_nombre, s.tipo_sala')
                ->join('peliculas p', 'p.id_pelicula = f.id_pelicula', 'left')
                ->join('salas s', 's.id_sala = f.id_sala', 'left')
                ->where('f.id_funcion', $idFuncion)
                ->get()
                ->getRowArray();
        }

        // 2. Datos de las Entradas / Butacas
        $butacas         = $session->get('butacas_seleccionadas') ?? [];
        $entradasCant    = (int) ($session->get('entradas_cantidad') ?? 0);
        $entradaUnit     = (float) ($session->get('entrada_precio_unit') ?? 0);
        $entradasEs4D    = (bool) ($session->get('entradas_es_4d') ?? false);
        $entradasTotal   = (float) ($session->get('entradas_total') ?? 0);

                // 3. Datos de los Combos elegidos
                $combosDetalle = [];
                $combosSeleccionados = $session->get('combos_seleccionados') ?? [];
                if (!empty($combosSeleccionados) && is_array($combosSeleccionados)) {
                    $idsCombos = array_keys($combosSeleccionados);
                    $combosDb = $db->table('combo')
                        ->whereIn('id_combo', $idsCombos)
                        ->get()
                        ->getResultArray();
        
                    foreach ($combosDb as $c) {
                        $id   = (int) $c['id_combo'];
                        $cant = (int) ($combosSeleccionados[$id] ?? 1);
                        $c['cantidad'] = $cant;
                        $c['subtotal'] = (float) $c['precio'] * $cant;
                        $combosDetalle[] = $c;
                    }
                }
                $combosTotal = (float) ($session->get('combos_total') ?? 0);
        
        // 4. Suma Total Final
        $totalGeneral = $entradasTotal + $combosTotal;

        $data = [
            'pagos' => $this->pagoModel->findAll(),
            'resumen' => [
                'funcion'        => $funcionDetalle,
                'butacas'        => $butacas,
                'entradas_cant'  => $entradasCant,
                'entrada_unit'   => $entradaUnit,
                'entradas_es_4d' => $entradasEs4D,
                'entradas_total' => $entradasTotal,
                'combos'         => $combosDetalle,
                'combos_total'   => $combosTotal,
                'total_general'  => $totalGeneral,
            ]
        ];

        return view('Pago/index', $data); 
    }
    /**
     * Muestra el formulario alternativo para crear un pago.
     * Nota: Mantenido por compatibilidad estructural del sistema.
     */
    public function nuevo()
    {
        return view('Pago/form');
    }

    /**
     * Procesa el formulario enviado por el cliente, inserta la orden en la BD
     * y retorna de forma segura limpiando la sesión local del carrito.
     */
    public function guardarWeb()
    {
        $metodoPago = $this->request->getPost('metodo_pago');

        // Si paga en efectivo (boletería) queda Pendiente; si paga con tarjeta o MP queda Pagado
        $estado = ($metodoPago === 'efectivo') ? 'Pendiente' : 'Pagado';

        $data = [
            'monto'       => $this->request->getPost('monto'),
            'moneda'      => $this->request->getPost('moneda'),
            'metodo_pago' => $metodoPago,
            'estado'      => $estado,
        ];


        if ($this->pagoModel->insert($data)) {
            // Limpiamos el carrito de la sesión tras confirmar el pago
            session()->remove([
                'id_funcion', 'id_sala', 'butacas_seleccionadas',
                'entradas_cantidad', 'entrada_precio_unit', 'entradas_es_4d',
                'entradas_total', 'combos_seleccionados', 'combos_total'
            ]);

            return redirect()->to(base_url('funcion'))->with('success', '¡Su pago ha sido registrado con éxito! Disfrute la función.');
        }

        return redirect()->back()->withInput()->with('errors', $this->pagoModel->errors());
    

        if ($this->pagoModel->insert($data)) {
            // Guardamos una señal flash temporal que el JavaScript de la vista leerá para vaciar el localStorage
            session()->setFlashdata('clear_cart', true);
            
            // Redirección fluida y segura a la cartelera pública del cine con mensaje de éxito
            return redirect()->to(base_url('cartelera'))->with('success', '¡Su pago ha sido registrado con éxito! Disfrute la función.');
        }

        return redirect()->back()->withInput()->with('errors', $this->pagoModel->errors());
    }

    /**============
     * OBTENER TODOS LOS PAGOS VIA API
     */
    public function apiIndex()
    {
        $pagos = $this->pagoModel->findAll();
        return $this->respond($pagos, 200);
    }

    /**
     * OBTENER UN PAGO POR SU ID
     */
    public function show($id = null)
    {
        $pago = $this->pagoModel->find($id);

        if (!$pago) {
            return $this->failNotFound('El pago solicitado no existe.');
        }

        return $this->respond($pago, 200);
    }

    /**
     * REGISTRAR UN NUEVO PAGO VIA API
     */
    public function create()
    {
        $data = [
            'monto'       => $this->request->getVar('monto'),
            'moneda'      => $this->request->getVar('moneda'),
            'metodo_pago' => $this->request->getVar('metodo_pago'),
            'estado'      => $this->request->getVar('estado'),
        ];

        if (!$this->pagoModel->insert($data)) {
            return $this->respond(['errors' => $this->pagoModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 201,
            'message' => 'Pago registrado con éxito.',
            'id_pago' => $this->pagoModel->getInsertID()
        ], 201);
    }

    /**
     * ACTUALIZA EL ESTADO DE UN PAGO VIA API
     */
    public function update($id = null)
    {
        if (!$this->pagoModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. El pago no existe.');
        }

        $data = [
            'estado' => $this->request->getVar('estado')
        ];

        if (!$this->pagoModel->update($id, $data)) {
            return $this->respond(['errors' => $this->pagoModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Estado del pago actualizado correctamente.'
        ], 200);
    }
}
