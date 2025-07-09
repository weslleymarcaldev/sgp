<?php

namespace App\Controllers\Api;

use App\Models\HealthCheckModel;
use App\Models\ProjectModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class HealthCheck extends ResourceController
{
    protected $format = 'json';

    public function checkAll()
    {
        $projectModel = new ProjectModel();
        $projects     = $projectModel->where('url IS NOT NULL')->findAll();

        $results = [];
        foreach ($projects as $project) {
            $result    = $this->checkProjectHealth($project['id'], $project['url']);
            $results[] = $result;
        }

        return $this->respond($results);
    }

    public function checkProject($projectId)
    {
        $projectModel = new ProjectModel();
        $project      = $projectModel->find($projectId);

        if (!$project) {
            return $this->failNotFound('Project not found');
        }

        if (empty($project['url'])) {
            return $this->fail('Project has no URL to check');
        }

        $result = $this->checkProjectHealth($project['id'], $project['url']);
        return $this->respond($result);
    }

    public function getHistory($projectId)
    {
        $model   = new HealthCheckModel();
        $history = $model
            ->where('project_id', $projectId)
            ->orderBy('last_check', 'DESC')
            ->limit(10)
            ->findAll();

        // Mapeia cada registro traduzindo chaves e valores
        $response = array_map(function ($item) {
            // Converte e formata cada data
            $fmtDate = function ($dateStr) {
                if (empty($dateStr)) {
                    return null;
                }
                $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $dateStr);
                return $dt ? $dt->format('d-m-Y H:i:s') : $dateStr;
            };
            return [
                'id'                  => $item['id'],
                'id_projeto'          => $item['project_id'],
                'url'                 => $item['url'], // ou 'url_de_verificacao'
                'status'              => $this->translateStatus($item['status']),
                'tempo_de_resposta'   => $item['response_time'],
                'status_de_saude'     => $this->translateHealthStatus($item['health_status']),
                'criado_em'           => $fmtDate($item['created_at']),
                'atualizado_em'       => $fmtDate($item['updated_at']),
                'ultima_verificacao'  => $fmtDate($item['last_check']),
                'mensagem_de_erro'    => $item['error_message'],
            ];
        }, $history);

        return $this->respond($response);
    }

    /** Traduz valores de status HTTP ou custom */
    private function translateStatus(string $status): string
    {
        $map = [
            ''         => '',
            'active'   => 'ativo',
            'inactive' => 'inativo',
        ];
        return $map[$status] ?? $status;
    }

    /** Traduz health_status */
    private function translateHealthStatus(string $hs): string
    {
        $map = [
            'healthy' => 'saudável',
            'warning' => 'aviso',
            'error'   => 'erro',
            ''        => 'desconhecido',
        ];
        return $map[$hs] ?? $hs;
    }


    private function checkProjectHealth($projectId, $url)
    {
        $startTime    = microtime(true);
        $status       = 'error';
        $responseTime = null;
        $errorMessage = null;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response     = curl_exec($ch);
            $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $responseTime = round((microtime(true) - $startTime) * 1000);

            if (curl_errno($ch)) {
                $errorMessage = curl_error($ch);
            } elseif ($httpCode === 200) {
                $status = $responseTime < 1000 ? 'healthy' : 'warning';
            } else {
                $status       = 'warning';
                $errorMessage = "HTTP $httpCode";
            }

            curl_close($ch);
        } catch (Exception $e) {
            $responseTime = round((microtime(true) - $startTime) * 1000);
            $errorMessage = $e->getMessage();
        }

        $checkedAt = date('Y-m-d H:i:s');

        // Save health check
        $healthCheckModel = new HealthCheckModel();
        $healthCheckData  = [
            'id'            => uniqid('health_'),
            'project_id'    => $projectId,
            'health_status' => $status,
            'response_time' => $responseTime,
            'checked_at'    => $checkedAt,
            'error_message' => $errorMessage
        ];

        $healthCheckModel->insert($healthCheckData);

        // Update project health status
        $projectModel = new ProjectModel();
        $projectModel->update($projectId, [
            'health_status' => $status,
            'last_check'    => $checkedAt,
            'response_time' => $responseTime,
            'updated_at'    => $checkedAt
        ]);

        return [
            'project_id'    => $projectId,
            'status'        => $status,
            'response_time' => $responseTime,
            'error_message' => $errorMessage,
            'checked_at'    => $checkedAt
        ];
    }
}
