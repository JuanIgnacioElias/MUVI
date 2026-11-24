<?php

namespace App\Models;

use CodeIgniter\Model;

class PeliculaModel extends Model
{
    protected $table            = 'peliculas';
    protected $primaryKey       = 'id_pelicula';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'titulo', 'sinopsis', 'duracion', 'clasificacion',
        'trailer_url', 'imagen', 'id_categoria',
    ];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'titulo'        => 'required|max_length[40]',
        'sinopsis'      => 'permit_empty',
        'duracion'      => 'required|is_natural_no_zero',
        'clasificacion' => 'required|max_length[40]',
        'trailer_url'   => 'permit_empty|valid_url',
        'imagen'        => 'permit_empty|max_length[255]',
        'id_categoria'  => 'permit_empty|is_natural_no_zero|is_not_unique[categorias.id_categoria]',
    ];

    protected $validationMessages = [
        'titulo'        => ['max_length' => 'El título no puede superar los 40 caracteres.'],
        'duracion'      => ['is_natural_no_zero' => 'La duración debe ser un número de minutos válido.'],
        'clasificacion' => ['max_length' => 'La clasificación no puede superar los 40 caracteres.'],
        'trailer_url'   => ['valid_url' => 'Debe ingresar una URL válida para el tráiler.'],
        'id_categoria'  => [
            'is_natural_no_zero' => 'La categoría elegida no es válida.',
            'is_not_unique'      => 'La categoría elegida no existe.',
        ],
    ];

    /**
     * Devuelve el modelo con la categoría ya unida (columna "categoria").
     * Se encadena: $modelo->conCategoria()->where(...)->findAll().
     */
    public function conCategoria(): static
    {
        return $this->select('peliculas.*, categorias.nombre AS categoria')
                    ->join('categorias', 'categorias.id_categoria = peliculas.id_categoria', 'left');
    }
}
