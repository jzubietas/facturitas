<?php

namespace App\Traits;

trait CommonModel
{
    public function scopeActivo($query, $estado = 1,$boolean = 'and')
    {
        return $query->where($this->qualifyColumn('estado'), '=', $estado,$boolean);
    }
    public function scopeActivoJoin($query,$column, $estado = 1,$boolean = 'and')
    {
        return $query->where($column.'.estado', '=', $estado,$boolean);
    }
}
