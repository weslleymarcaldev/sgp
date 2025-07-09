<?php

#Modelo de tecnologia

namespace App\Models;

use CodeIgniter\Model;

class TechnologyModel extends Model
{
    protected $table            = 'technologies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'project_id', 'name', 'version', 'type', 'is_primary'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'project_id' => 'required',
        'name'       => 'required|min_length[2]|max_length[100]',
        'version'    => 'required|max_length[50]',
        'type'       => 'required|in_list[framework,library,database,language,tool]'
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'O nome da tecnologia é obrigatório',
            'min_length' => 'O nome deve ter pelo menos 2 caracteres'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
