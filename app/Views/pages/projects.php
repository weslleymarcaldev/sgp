<div class="space-y-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 
              bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
      Projetos
    </h2>
    <button id="new-project-btn"
            class="flex items-center gap-2 px-4 py-2 
                   bg-blue-600 hover:bg-blue-700 text-white 
                   dark:bg-blue-500 dark:hover:bg-blue-600 
                   rounded-lg transition-colors"
            title="Novo Projeto">
      <i data-lucide="plus" class="w-4 h-4"></i>
      Novo Projeto
    </button>
  </div>

  <!-- Filters -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <div class="flex flex-col sm:flex-row gap-4">
      <div class="flex-1 relative">
        <i data-lucide="search"
           class="absolute left-3 top-1/2 transform -translate-y-1/2 
                  h-4 w-4 text-gray-400 dark:text-gray-500"></i>
        <input type="text" id="search-projects" placeholder="Pesquisar projetos..."
               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg 
                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                      dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100
                      dark:focus:ring-blue-600 dark:focus:border-blue-600 transition-colors">
      </div>
      <div class="flex items-center gap-2">
        <i data-lucide="filter"
           class="h-4 w-4 text-gray-400 dark:text-gray-500"></i>
        <label for="status-filter" class="sr-only">Filtrar por status</label>
        <select id="status-filter"
                aria-label="Filtrar projetos por status"
                title="Filtrar projetos por status"
                class="px-3 py-2 border border-gray-300 rounded-lg 
                       focus:ring-2 focus:ring-blue-500 focus:border-transparent
                       dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100
                       dark:focus:ring-blue-600 dark:focus:border-blue-600 transition-colors">
          <option value="all">Todos os Status</option>
          <option value="active">Ativo</option>
          <option value="development">Desenvolvimento</option>
          <option value="maintenance">Manutenção</option>
          <option value="inactive">Inativo</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Projects Grid -->
  <div id="projects-grid"
       class="grid grid-cols-1 lg:grid-cols-2 gap-6 
              bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <!-- Aqui os cards serão inseridos -->
  </div>

  <!-- Fallback “Nenhum projeto encontrado” -->
  <div id="no-projects-found"
       class="text-center py-12 hidden 
              bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <div class="text-gray-500 dark:text-gray-400">
      Nenhum projeto encontrado
    </div>
  </div>
</div>
