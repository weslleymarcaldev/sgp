<?php

namespace App\Controllers\Api;

use App\Models\ProjectModel;
use App\Models\TechnologyModel;
use CodeIgniter\RESTful\ResourceController;

class Projects extends ResourceController
{
    protected $modelName = ProjectModel::class;
    protected $format    = 'json';

    public function index()
    {
        $projects = $this->model->orderBy('updated_at', 'DESC')->findAll();
        return $this->respond($projects);
    }

    public function show($id = null)
    {
        // 1) Busca o projeto
        $project = $this->model->find($id);
        if (! $project) {
            return $this->failNotFound("Projeto com ID '{$id}' não encontrado.");
        }

        // 2) Carrega tecnologias
        $techModel     = new TechnologyModel();
        $technologies  = $techModel
            ->where('project_id', $id)
            ->orderBy('is_primary', 'DESC')
            ->orderBy('name')
            ->findAll();

        // 3) Monta o array traduzido
        $response = [
            'id'                  => $project['id'],
            'nome'                => $this->translateName($project['name']),
            'descrição'           => $project['description'],
            'url'                 => $project['url'],
            'repositório'         => $project['repository'],
            'status'              => $this->translateStatus($project['status']),
            'status_de_saúde'     => $this->translateHealthStatus($project['health_status']),
            'criado_em'           => $project['created_at'],
            'atualizado_em'       => $project['updated_at'],
            'última_verificação'  => $project['last_check'],
            'hora_de_resposta'    => $project['response_time'],
            'tecnologias'         => $technologies,
        ];

        return $this->respond($response);
    }

    /** Exemplo de tradutor de status do projeto */
    private function translateStatus(string $status): string
    {
        $map = [
            'active'      => 'ativo',
            'inactive'    => 'inativo',
            'development' => 'em desenvolvimento',
            'maintenance' => 'manutenção',
        ];
        return $map[$status] ?? $status;
    }

    /** Tradutor de status de saúde */
    private function translateHealthStatus(string $hs): string
    {
        $map = [
            'unknown' => 'desconhecido',
            'healthy' => 'saudável',
            'warning' => 'aviso',
            'error'   => 'erro',
        ];
        return $map[$hs] ?? $hs;
    }

    /** Se quiser traduzir dinamicamente o nome */
    private function translateName(string $name): string
    {
        // Ou retorna direto, se você já armazenou em pt no DB
        //return $name;

        $map = [
            'Analytics Service'   => 'Serviço de Análise',
            'E-commerce Platform' => 'Plataforma de E-commerce',
            'Task Management API' => 'API de Gerenciamento de Tarefas',
            // … demais traduções
        ];
        return $map[$name] ?? $name;
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        $projectData = [
            'id'            => uniqid('proj_'),
            'name'          => $data['name'],
            'description'   => $data['description'] ?? '',
            'url'           => $data['url']         ?? null,
            'repository'    => $data['repository']  ?? null,
            'status'        => $data['status']      ?? 'development',
            'health_status' => 'unknown',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        if ($this->model->insert($projectData)) {
            // Insert technologies if provided
            if (isset($data['technologies']) && is_array($data['technologies'])) {
                $technologyModel = new TechnologyModel();
                foreach ($data['technologies'] as $tech) {
                    if (!empty($tech['name'])) {
                        $techData = [
                            'id'         => uniqid('tech_'),
                            'project_id' => $projectData['id'],
                            'name'       => $tech['name'],
                            'version'    => $tech['version']    ?? '',
                            'type'       => $tech['type']       ?? 'framework',
                            'is_primary' => $tech['is_primary'] ?? false
                        ];
                        $technologyModel->insert($techData);
                    }
                }
            }

            return $this->respondCreated(['id' => $projectData['id'], 'message' => 'Project created successfully']);
        }

        return $this->fail('Failed to create project');
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        $updateData = [
            'name'        => $data['name'],
            'description' => $data['description'] ?? '',
            'url'         => $data['url']         ?? null,
            'repository'  => $data['repository']  ?? null,
            'status'      => $data['status'],
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        if ($this->model->update($id, $updateData)) {
            return $this->respond(['message' => 'Project updated successfully']);
        }

        return $this->failNotFound('Project not found');
    }

    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return $this->respond(['message' => 'Project deleted successfully']);
        }

        return $this->failNotFound('Project not found');
    }

    public function stats()
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT
                COUNT(*)                                            AS `total_de_projetos`,
                COUNT(CASE WHEN status = 'active' THEN 1 END)       AS `projetos_ativos`,
                COUNT(CASE WHEN health_status = 'healthy' THEN 1 END)AS `projetos_saudaveis`,
                COUNT(CASE WHEN health_status = 'warning' THEN 1 END)AS `projetos_em_alerta`,
                COUNT(CASE WHEN health_status = 'error' THEN 1 END)  AS `projetos_com_erro`
            FROM projects
        ");

        return $this->respond($query->getRow());
    }
}
