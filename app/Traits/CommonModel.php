<?php

namespace App\Traits;

trait CommonModel
{
    public function scopeActivo($query, $estado = 1,$boolean = 'and')
    {
        return $query->where($this->qualifyColumn('estado'), '=', $estado,$boolean);
    }
    public function scopeActivoJoin($query, $table, $estado = 1, $boolean = 'and')
    {
        return $query->where($table.'.estado', '=', $estado,$boolean);
    }
}
