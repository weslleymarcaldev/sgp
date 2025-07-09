<?php

#Modelo de verificação de saúde

namespace App\Models;

use CodeIgniter\Model;

class HealthCheckModel extends Model
{
    protected $table            = 'health_checks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'project_id', 'status', 'response_time', 'last_check', 'error_message',
    ];


    protected $useTimestamps    = false;

    protected $validationRules = [
        'project_id' => 'required',
        'status'     => 'required|in_list[healthy,warning,error]'
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
