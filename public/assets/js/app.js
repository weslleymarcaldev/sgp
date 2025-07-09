// ---------------------------------------------------
// Quando o DOM estiver pronto
// ---------------------------------------------------
// 1) Toggle de tema (isolado, sem depender do jQuery nem de API_BASE)
document.addEventListener('DOMContentLoaded', () => {
  const root        = document.documentElement;
  const storedTheme = localStorage.getItem('theme');

  if (
    storedTheme === 'dark' ||
    (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)
  ) {
    root.classList.add('dark');
  }

  const btn = document.getElementById('theme-toggle');
  if (btn) {
    btn.addEventListener('click', () => {
      const isDark = root.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      lucide.createIcons();
    });
  }
});
$(function() {
    const API_BASE = window.APP?.baseUrl || '';
    
    lucide.createIcons();
    
    let currentProjectId = null;
    let technologies     = [];
    let allProjects      = [];
    let availableTechs   = [];
    bindEvents();
    loadAvailableTechs();
    loadProjects();
    
    function bindEvents() {
        $('#new-project-btn').on('click', () => openProjectModal());
        $('#project-form').on('submit', onSubmitProject);
        $('#search-projects, #status-filter').on('input change', filterProjects);
        $('#close-modal, #cancel-form').on('click', closeProjectModal);
        $('#add-technology').click(function() {
          // pega o valor selecionado (no seu select com múltiplos, pode ser um array)
          const selectedId = $('#tech-select').val();
          if (!selectedId) return;
          // Se for múltiplo, é um array; se for simples, é string
          const ids = Array.isArray(selectedId) ? selectedId : [selectedId];
          ids.forEach(id => {
            // só adiciona se ainda não existir
            if (!technologies.some(t => t.id === id)) {
              const tech = availableTechs.find(t => t.id === id);
              if (tech) {
                // push no formato que seu renderTechnologies espera
                technologies.push({
                  id:         tech.id,
                  name:       tech.name,
                  version:    tech.version,
                  type:       tech.type,       // ou tech.tipo, dependendo do JSON
                  is_primary: tech.is_primary  // ou tech.e_primaria
                });
              }
            }
          });
          renderTechnologies();
        });
        // spinner global para requisições AJAX
        $(document)
          .ajaxStart(() => $('#global-spinner').removeClass('hidden'))
          .ajaxStop(()  => $('#global-spinner').addClass('hidden'));
    }
    
    function onSubmitProject(e) {
        e.preventDefault();
        const id = currentProjectId;
        const payload = {
          name:        $('#project-name').val().trim(),
          description: $('#project-description').val().trim(),
          url:         $('#project-url').val().trim(),
          repository:  $('#project-repository').val().trim(),
          status:      $('#project-status').val(),
          technologies: $('#tech-select').val(),
          // technologies já está montado pelo modal
          technologies: technologies.filter(t => t.name.trim() !== '')
        };
        const method = id ? 'PUT' : 'POST';
        const url    = `${API_BASE}/api/projects${id ? '/' + id : ''}`;
    
        $.ajax({ url, method, contentType: 'application/json', data: JSON.stringify(payload) })
          .done(() => {
            closeProjectModal();
            loadProjects();
            if (window.loadDashboardData) loadDashboardData();
          })
          .fail(xhr => {
            console.error('Erro ao salvar projeto:', xhr.responseText);
            alert('Não foi possível salvar o projeto. Confira o console.');
          });
    }
    
    // --- Tecnologias disponí­veis ---
    function loadAvailableTechs() {
        return $.getJSON(`${API_BASE}/api/technologies`)
          .done(data => {
            availableTechs = data;
            renderTechOptions();
          })
          .fail(() => console.error('Não foi possível carregar as tecnologias'));
    }

    // --- Projetos + suas techs ---
    function loadProjects() {
        $.get(`${API_BASE}/api/projects`)
          .done(projects => {
            const promises = projects.map(p =>
              $.getJSON(`${API_BASE}/api/technologies/project/${p.id}`)
                .then(techs => { p.technologies = techs; return p; })
            );
            Promise.all(promises).then(full => {
              allProjects = full;
              filterProjects();
            });
          })
          .fail(() => console.error('Não foi possível carregar os projetos'));
    }
    
    function filterProjects() {
        const term   = $('#search-projects').val().toLowerCase();
        const status = $('#status-filter').val();
        let list = allProjects;
    
        if (term) {
          list = list.filter(p =>
            p.name.toLowerCase().includes(term) ||
            (p.description || '').toLowerCase().includes(term)
          );
        }
        if (status !== 'all') {
          list = list.filter(p => p.status === status);
        }
        displayProjects(list);
    }
    
    function displayProjects(list) {
        const $grid = $('#projects-grid').empty();
        const $none = $('#no-projects-found');
        if (!list.length) {
          $none.removeClass('hidden');
          return;
        }
        $none.addClass('hidden');
        list.forEach(p => $grid.append(createProjectCard(p, true)));
        lucide.createIcons();
    }
    
    // --- Card de projeto ---
    function createProjectCard(project, showActions = false) {
      const actions = showActions ? `
        <div class="flex items-center gap-2">
          <button onclick="editProject('${project.id}')" class="p-2 hover:bg-green-50 rounded" title="Editar projeto">
            <i data-lucide="pencil" class="w-4 h-4 text-gray-500 hover:text-green-600"></i>
          </button>
          <button onclick="deleteProject('${project.id}')" class="p-2 hover:bg-red-50 rounded" title="Excluir projeto">
            <i data-lucide="trash-2" class="w-4 h-4 text-gray-500 hover:text-red-600"></i>
          </button>
        </div>` : '';
    
      const techBadges = Array.isArray(project.technologies) && project.technologies.length
        ? project.technologies.map(t =>
            `<span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                ${t.name}${t.version ? ` v${t.version}` : ''}
             </span>`
          ).join('')
        : `<span class="text-gray-500 text-sm">Sem tecnologias</span>`;
    
      return `
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg p-6">
          <div class="flex justify-between mb-4">
            <div>
              <h3 class="text-xl font-semibold mb-2 dark:text-gray-100">${project.name}</h3>
              <p class="text-gray-600 text-sm mb-3 dark:text-gray-300">${project.description||''}</p>
              <span class="px-2 py-1 text-xs font-medium ${getStatusColor(project.status)}">
                ${project.status}
              </span>
            </div>
            <div class="flex items-center gap-2">
              ${getHealthIcon(project.health_status)}
              ${project.response_time ? `<span class="text-xs text-gray-500 dark:text-gray-300">${project.response_time}ms</span>` : ''}
            </div>
          </div>
          <div class="mb-4 flex flex-wrap gap-2">${techBadges}</div>
          <div class="flex justify-between items-center">
            <div class="flex gap-4 text-sm text-gray-500 dark:text-gray-300">
              ${project.url        ? `<a href="${project.url}"        target="_blank"><i data-lucide="globe"  class="w-4 h-4" title="Site"></i>Site</a>` : ''}
              ${project.repository ? `<a href="${project.repository}" target="_blank"><i data-lucide="github" class="w-4 h-4" title="Repo"></i>Repo</a>` : ''}
              ${project.last_check ? `<span><i data-lucide="clock" class="w-4 h-4" title="Última verificação"></i>${formatTimeAgo(project.last_check)}</span>` : ''}
            </div>
            ${actions}
          </div>
        </div>`;
      lucide.createIcons();
    }
    
    // --- Modal CRUD ---
    function openProjectModal(project = null) {
      currentProjectId = project?.id || null;
    
      // 1) Popula o array de tecnologias com os dados do projeto (se houver)
      technologies = project?.technologies?.map(t => ({
        id:         t.id,
        name:       t.name,
        version:    t.version,
        type:       t.type,
        is_primary: t.is_primary
      })) || [];
    
      // 2) Textos do modal
      $('#modal-title').text(project ? 'Editar Projeto' : 'Novo Projeto');
      $('#submit-form').text(project ? 'Salvar' : 'Criar');
    
      // 3) Zera o form e preenche os campos básicos
      $('#project-form')[0].reset();
      $('#project-id').val(currentProjectId || '');
      $('#project-name').val(project?.name || '');
      $('#project-description').val(project?.description || '');
      $('#project-url').val(project?.url || '');
      $('#project-repository').val(project?.repository || '');
      $('#project-status').val(project?.status || 'development');
    
      // 4) Renderiza o select de tecnologias e marca as que já fazem parte do projeto
      renderTechOptions();  // popula <select id="tech-select">
      $('#tech-select').val(project?.technologies?.map(t => t.id) || []);
    
      // 5) Desenha os blocos de inputs para cada tecnologia já existente
      renderTechnologies();
    
      // 6) Mostra o modal
      $('#project-modal').removeClass('hidden');
    }
    
    function closeProjectModal() {
      $('#project-modal').addClass('hidden');
      currentProjectId = null;
      technologies     = [];
      $('#technologies-container').empty();
    }
    
    function removeTechnology(i) {
      technologies.splice(i, 1);
      renderTechnologies();
    }
    
    function updateTechnology(i, field, val) {
      if (field === 'is_primary') val = $(`#tech-${i}-primary`).is(':checked');
      technologies[i][field] = val;
    }
      
    function renderTechOptions() {
      const $sel = $('#tech-select').empty();
      availableTechs.forEach(tech => {
        $sel.append(
          `<option value="${tech.id}">
             ${tech.name}${tech.version ? ` v${tech.version}` : ''}
           </option>`
        );
      });
      lucide.createIcons();
    }
    
    function renderTechnologies() {
      const $c = $('#technologies-container').empty();
      technologies.forEach((tech, i) => {
        $c.append(`
          <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-2">
              <input id="tech-${i}-name"    value="${tech.name}"    placeholder="Nome"
                     onchange="updateTechnology(${i}, 'name', this.value)"
                     class="px-2 py-1 border rounded text-sm dark:bg-gray-800 dark:text-gray-100">
              <input id="tech-${i}-version" value="${tech.version}" placeholder="Versão"
                     onchange="updateTechnology(${i}, 'version', this.value)"
                     class="px-2 py-1 border rounded text-sm dark:bg-gray-800 dark:text-gray-100">
              <select id="tech-${i}-type"
                      onchange="updateTechnology(${i}, 'type', this.value)"
                      class="px-2 py-1 border rounded text-sm dark:bg-gray-800 dark:text-gray-100">
                <option value="framework"${tech.type==='framework'?' selected':''}>Framework</option>
                <option value="library"${tech.type==='library'?' selected':''}>Library</option>
                <option value="database"${tech.type==='database'?' selected':''}>Database</option>
                <option value="language"${tech.type==='language'?' selected':''}>Language</option>
                <option value="tool"${tech.type==='tool'?' selected':''}>Tool</option>
              </select>
              <label class="flex items-center gap-1 text-sm dark:text-gray-100">
                <input id="tech-${i}-primary" type="checkbox" ${tech.is_primary?'checked':''}
                       onchange="updateTechnology(${i}, 'is_primary')"
                       class="rounded">
                Principal
              </label>
            </div>
            <button type="button" onclick="removeTechnology(${i})"
                    class="p-1 text-red-500 hover:bg-red-50 dark:hover:bg-gray-600 rounded">
              <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
          </div>
        `);
      });
      lucide.createIcons();
    }
    
    // --- Uteis ---
    function formatTimeAgo(dateString) {
        const then = new Date(dateString).getTime();
        const diff = Math.floor((Date.now() - then) / 1000);
        if (diff < 60)    return 'agora mesmo';
        if (diff < 3600)  return `${Math.floor(diff/60)} minutos atrás`;
        if (diff < 86400) return `${Math.floor(diff/3600)} horas atrás`;
        return `${Math.floor(diff/86400)} dias atrás`;
    }
    
    function getStatusColor(s) {
        return {
          active:      'bg-green-100 text-green-800',
          development: 'bg-blue-100  text-blue-800',
          maintenance: 'bg-yellow-100 text-yellow-800',
          inactive:    'bg-gray-100  text-gray-800',
        }[s]||'bg-gray-100 text-gray-800';
    }
    
    function getHealthIcon(h) {
        return {
          healthy: '<i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>',
          warning: '<i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-500"></i>',
          error:   '<i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>',
        }[h]||'<i data-lucide="activity" class="w-5 h-5 text-gray-400"></i>';
    }
    
    // --- Ações globais que o HTML chama diretamente ---
    window.editProject   = id => $.get(`${API_BASE}/api/projects/${id}`).done(openProjectModal);
    window.deleteProject = id => {
      if (confirm('Excluir projeto?')) {
        $.ajax({ url:`${API_BASE}/api/projects/${id}`, type:'DELETE' })
          .done(loadProjects)
          .fail(() => console.error('Erro ao excluir'));
      }
    };
});