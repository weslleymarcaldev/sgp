<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        // 1) Trunca health_checks (filha de projects)
        $this->db->table('health_checks')->truncate();
        // 2) Trunca technologies (filha de projects)
        $this->db->table('technologies')->truncate();
        // 3) Só então trunca projects (tabela principal)
        $this->db->table('projects')->truncate();
        // Sample projects
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        $projects = [
            ['id' => 'proj-1', 'name' => 'E-commerce Platform',  'description' => 'Sistema completo de e-commerce com painel administrativo', 'url' => 'https://jsonplaceholder.typicode.com/posts/1', 'repository' => 'https://github.com/user/ecommerce',   'status' => 'active',       'health_status' => 'healthy',  'created_at' => '2024-01-15 10:00:00', 'updated_at' => '2024-01-20 14:30:00', 'last_check' => '2024-01-20 14:30:00', 'response_time' => 245],
            ['id' => 'proj-2', 'name' => 'Task Management API',  'description' => 'API REST para gerenciamento de tarefas e projetos',        'url' => 'https://jsonplaceholder.typicode.com/posts/2', 'repository' => 'https://github.com/user/taskapi',     'status' => 'development',  'health_status' => 'warning',  'created_at' => '2024-01-10 09:00:00', 'updated_at' => '2024-01-19 16:45:00', 'last_check' => '2024-01-19 16:45:00', 'response_time' => 1200],
            ['id' => 'proj-3', 'name' => 'Mobile App Dashboard', 'description' => 'Dashboard mobile para monitoramento de vendas',            'url' => 'https://jsonplaceholder.typicode.com/posts/3', 'repository' => 'https://github.com/user/mobile-dash', 'status' => 'maintenance',  'health_status' => 'error',    'created_at' => '2024-01-05 11:30:00', 'updated_at' => '2024-01-18 08:20:00', 'last_check' => '2024-01-18 08:20:00', 'response_time' => null],
            ['id' => 'proj-4', 'name' => 'Analytics Service',    'description' => 'Serviço de análise de métricas',                           'url' => 'https://jsonplaceholder.typicode.com/posts/4', 'repository' => 'https://github.com/user/analytics',   'status' => 'inactive',     'health_status' => 'unknown',   'created_at' => '2024-01-02 08:00:00', 'updated_at' => '2024-01-18 12:00:00', 'last_check' => null,                  'response_time' =>  null]
        ];
        // Agora insere em projects...
        $this->db->table('projects')->insertBatch($projects);

        // Tecnologias de amostra
        $technologies = [
            ['id' => 'tec-1', 'project_id' => 'proj-1', 'name' => 'CodeIgniter',    'version' => '4.4.0',    'type' => 'framework', 'is_primary' => true],
            ['id' => 'tec-2', 'project_id' => 'proj-1', 'name' => 'MySQL',          'version' => '8.0.35',   'type' => 'database',  'is_primary' => false],
            ['id' => 'tec-3', 'project_id' => 'proj-1', 'name' => 'jQuery',         'version' => '3.7.1',    'type' => 'library',   'is_primary' => false],
            ['id' => 'tec-4', 'project_id' => 'proj-1', 'name' => 'Tailwind CSS',   'version' => '3.4.1',    'type' => 'framework', 'is_primary' => false],
            ['id' => 'tec-5', 'project_id' => 'proj-2', 'name' => 'Node.js',        'version' => '20.10.0',  'type' => 'language',  'is_primary' => true],
            ['id' => 'tec-6', 'project_id' => 'proj-2', 'name' => 'Express',        'version' => '4.18.2',   'type' => 'framework', 'is_primary' => false],
            ['id' => 'tec-7', 'project_id' => 'proj-2', 'name' => 'PostgreSQL',     'version' => '15.3',     'type' => 'database',  'is_primary' => false],
            ['id' => 'tec-8', 'project_id' => 'proj-3', 'name' => 'React Native',   'version' => '0.72.6',   'type' => 'framework', 'is_primary' => true],
            ['id' => 'tec-9', 'project_id' => 'proj-3', 'name' => 'TypeScript',     'version' => '5.2.2',    'type' => 'language',  'is_primary' => false]
        ];
        // Agora insere em technologies...
        $this->db->table('technologies')->insertBatch($technologies);

        // Exemplos de verificações de saúde
        $healthChecks = [
            ['id' => 'check-1', 'project_id' => 'proj-1', 'url' => 'https://jsonplaceholder.typicode.com/posts/1', 'status' => 'active',      'health_status' => 'healthy', 'error_message' => 'Service Unavailable', 'created_at' => '2024-01-15 10:00:00', 'updated_at' => '2024-01-20 14:30:00', 'last_check' => '2024-01-20 14:30:00', 'response_time' => 245],
            ['id' => 'check-2', 'project_id' => 'proj-2', 'url' => 'https://jsonplaceholder.typicode.com/posts/2', 'status' => 'development', 'health_status' => 'warning', 'error_message' => 'Service Unavailable', 'created_at' => '2024-01-10 09:00:00', 'updated_at' => '2024-01-19 16:45:00', 'last_check' => '2024-01-19 16:45:00', 'response_time' => 1200],
            ['id' => 'check-3', 'project_id' => 'proj-3', 'url' => 'https://jsonplaceholder.typicode.com/posts/3', 'status' => 'maintenance', 'health_status' => 'error',   'error_message' => 'Service Unavailable', 'created_at' => '2024-01-05 11:30:00', 'updated_at' => '2024-01-18 08:20:00', 'last_check' => '2024-01-18 08:20:00', 'response_time' => null],
            ['id' => 'check-4', 'project_id' => 'proj-4', 'url' => 'https://jsonplaceholder.typicode.com/posts/4', 'status' => 'inactive',    'health_status' => 'error',   'error_message' => 'Service Unavailable', 'created_at' => '2024-01-05 11:30:00', 'updated_at' => '2024-01-18 08:20:00', 'last_check' => '2024-01-18 08:20:00', 'response_time' => null]
        ];

        $validChecks = [];
        foreach ($healthChecks as $hc) {
            $exists = $this->db
                        ->table('projects')
                        ->where('id', $hc['project_id'])
                        ->countAllResults() > 0;

            if ($exists) {
                $validChecks[] = $hc;
            } else {
                echo "⚠️ Projeto '{$hc['project_id']}' não existe. Ignorando health_check '{$hc['id']}'.\n";
            }
        }
        if (! empty($validChecks)) {
            // Agora insere em health_checks...
            $this->db->table('health_checks')->insertBatch($validChecks);
        }
    }
}
