<?php

namespace App\Models;

use CodeIgniter\Model;

class PagoModel extends Model
{
    protected $table            = 'pagos';
    protected $primaryKey       = 'id_pago';
    protected $returnType       = 'array';
    protected $allowedFields    = ['monto', 'moneda', 'metodo_pago', 'estado', 'id_usuario'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'monto'       => 'required|numeric',
        'moneda'      => 'required|alpha_space|max_length[20]',
        'metodo_pago' => 'required|max_length[20]',
        'estado'      => 'required|max_length[20]',
        'id_usuario'  => 'permit_empty|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'monto' => [
            'required' => 'El monto es obligatorio.',
            'numeric'  => 'El monto debe ser un número válido.'
        ],
        'moneda' => [
            'required'   => 'La moneda es obligatoria.',
            'max_length' => 'La moneda no puede superar los 20 caracteres.'
        ],
        'metodo_pago' => [
            'required'   => 'El método de pago es obligatorio.',
            'max_length' => 'El método de pago no puede superar los 20 caracteres.'
        ],
        'estado' => [
            'required'   => 'El estado es obligatorio.',
            'max_length' => 'El estado no puede superar los 20 caracteres.'
        ],
    ];

    // =========================================================================
    // MÉTODOS DE CÁLCULO Y CONSULTAS (Lógica de negocio fuera del Controlador y Vista)
    // =========================================================================

    /**
     * Obtiene los datos de la sesión y la BD, y los devuelve ya procesados para la vista.
     */
    public function obtenerResumenDesdeSesion($session): array
    {
        $db = db_connect();

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
        $butacas       = $session->get('butacas_seleccionadas') ?? [];
        $entradasCant  = (int) ($session->get('entradas_cantidad') ?? 0);
        $entradaUnit   = (float) ($session->get('entrada_precio_unit') ?? 0);
        $entradasEs4D  = (bool) ($session->get('entradas_es_4d') ?? false);
        $entradasTotal = (float) ($session->get('entradas_total') ?? 0);

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

        $resumenCrudo = [
            'funcion'        => $funcionDetalle,
            'butacas'        => $butacas,
            'entradas_cant'  => $entradasCant,
            'entrada_unit'   => $entradaUnit,
            'entradas_es_4d' => $entradasEs4D,
            'entradas_total' => $entradasTotal,
            'combos'         => $combosDetalle,
            'combos_total'   => $combosTotal,
            'total_general'  => $totalGeneral,
        ];

        // Llamamos a tu método para que aplique todos los formatos y redondeos
        return $this->procesarResumenParaVista($resumenCrudo);
    }

    /**
     * Toma el arreglo $resumen, realiza todos los cálculos y devuelve
     * los valores listos y formateados para imprimir directo en la vista.
     */
    public function procesarResumenParaVista(array $resumen): array
    {
        $totalGeneral = (int) round($resumen['total_general'] ?? 0);
        $entradasCant = (int) ($resumen['entradas_cant'] ?? 0);
        $combos       = $resumen['combos'] ?? [];

        // Formateamos cada combo y calculamos su subtotal si no viene listo
        foreach ($combos as &$c) {
            $cantidad = (int) ($c['cantidad'] ?? 1);
            $precio   = (float) ($c['precio'] ?? 0);
            $subtotal = $c['subtotal'] ?? ($cantidad * $precio);
            
            $c['cantidad_limpia']     = $cantidad;
            $c['subtotal_formateado'] = number_format($subtotal, 0, ',', '.');
        }
        unset($c);

        // Devolvemos el mismo array pero con los cálculos y formatos ya resueltos
        $resumen['total_redondeado']          = $totalGeneral;
        $resumen['entrada_unit_formateado']   = number_format($resumen['entrada_unit'] ?? 0, 0, ',', '.');
        $resumen['entradas_total_formateado'] = number_format($resumen['entradas_total'] ?? 0, 0, ',', '.');
        $resumen['butacas_texto']             = implode(', ', $resumen['butacas'] ?? []);
        $resumen['combos']                    = $combos;
        $resumen['tiene_items']               = ($entradasCant > 0 || !empty($combos));
        $resumen['boton_deshabilitado']       = ($totalGeneral <= 0) ? 'disabled' : '';

        if (!empty($resumen['funcion'])) {
            $resumen['fecha_formateada'] = date('d/m/Y', strtotime($resumen['funcion']['fecha']));
            $resumen['hora_formateada']  = substr($resumen['funcion']['hora_ingreso'], 0, 5);
        }

        return $resumen;
    }
}