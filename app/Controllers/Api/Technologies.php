<?php

namespace App\Controllers\Api;

use App\Models\TechnologyModel;
use CodeIgniter\RESTful\ResourceController;

class Technologies extends ResourceController
{
    protected $modelName = TechnologyModel::class;
    protected $format    = 'json';
    /** LISTAGEM GERAL (GET /api/technologies) */
    public function index()
    {
        $techs = $this->model->findAll();

        // aplica a mesma tradução que no getByProject
        $translated = array_map(function ($tech) {
            return [
                'id'       => $tech['id'],
                'name'     => $tech['name'],     // _MANTÉM_ esses nomes
                'version'  => $tech['version'],  // _MANTÉM_ esses nomes
                'type'     => $tech['type'],
                'is_primary' => (bool) $tech['is_primary'],
              ];
        }, $techs);

        return $this->respond($translated);
    }

    public function getByProject($projectId)
    {
        $techs = $this->model
                      ->where('project_id', $projectId)
                      ->orderBy('is_primary', 'DESC')
                      ->orderBy('name')
                      ->findAll();

        $translated = array_map(function ($tech) {
            return [
                'id'            => $tech['id'],
                'project_id'    => $tech['project_id'],
                'name'          => $tech['name'],
                'version'       => $tech['version'],
                'type'          => $tech['type'],
                'is_primary'    => (bool) $tech['is_primary'],
            ];
        }, $techs);

        return $this->respond($translated);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        $techData = [
            'id'        => uniqid('tech_'),
            'project_id' => $data['project_id'],
            'name'      => $data['name'],
            'version'   => $data['version'],
            'type'      => $data['type'],
            'is_primary' => $data['is_primary'] ?? false
        ];

        if ($this->model->insert($techData)) {
            return $this->respondCreated(['id' => $techData['id'], 'message' => 'Technology added successfully']);
        }

        return $this->fail('Failed to add technology');
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        $updateData = [
            'name'      => $data['name'],
            'version'   => $data['version'],
            'type'      => $data['type'],
            'is_primary' => $data['is_primary']
        ];

        if ($this->model->update($id, $updateData)) {
            return $this->respond(['message' => 'Technology updated successfully']);
        }

        return $this->failNotFound('Technology not found');
    }

    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return $this->respond(['message' => 'Technology deleted successfully']);
        }

        return $this->failNotFound('Technology not found');
    }
}
