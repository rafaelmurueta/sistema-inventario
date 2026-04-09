<?php
/**
 * Plataforma SaaS de Diagnóstico SST con IA Normativa
 * Compatible con XAMPP (Apache/PHP)
 * Autor: Senior Software Architect AI
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SST Diagnostic Pro | Plataforma Inteligente</title>
    <!-- Tailwind CSS para UI Moderna -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- PDF Generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        corporate: {
                            blue: '#1e3a8a',
                            lightBlue: '#3b82f6',
                            orange: '#f97316',
                            gray: '#f3f4f6',
                            darkGray: '#374151'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .tab-active { border-bottom: 3px solid #f97316; color: #1e3a8a; font-weight: bold; }
        .tab-inactive { color: #6b7280; }
        .tab-inactive:hover { color: #1e3a8a; background-color: #e5e7eb; }
        input, select { width: 100%; border: 1px solid #d1d5db; padding: 6px; border-radius: 4px; font-size: 0.875rem; }
        input:focus { outline: 2px solid #3b82f6; border-color: transparent; }
        .ai-input { background-color: #fffbeb; border-left: 3px solid #f97316; }
        .ai-output { background-color: #eff6ff; border-left: 3px solid #3b82f6; color: #1e3a8a; font-weight: 500; }
        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        th { background-color: #1e3a8a; color: white; padding: 8px; text-align: left; }
        td { border: 1px solid #e5e7eb; padding: 4px; }
        .btn-action { padding: 6px 12px; border-radius: 4px; font-weight: bold; cursor: pointer; transition: all 0.2s; }
        .btn-add { background-color: #10b981; color: white; }
        .btn-del { background-color: #ef4444; color: white; }
        .btn-primary { background-color: #1e3a8a; color: white; }
        .btn-secondary { background-color: #f97316; color: white; }
        
        /* Animación de carga IA */
        .loading-ai { animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-corporate-blue text-white flex flex-col shadow-xl">
        <div class="p-6 border-b border-blue-800">
            <h1 class="text-xl font-bold"><i class="fa-solid fa-shield-halved mr-2"></i>SST Pro</h1>
            <p class="text-xs text-blue-300 mt-1">Diagnóstico Inteligente</p>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <button onclick="switchTab('condiciones-inseguras')" class="w-full text-left px-6 py-3 hover:bg-blue-800 transition nav-item tab-active" data-tab="condiciones-inseguras">
                <i class="fa-solid fa-triangle-exclamation w-6"></i> Cond. Inseguras
            </button>
            <button onclick="switchTab('condiciones-peligrosas')" class="w-full text-left px-6 py-3 hover:bg-blue-800 transition nav-item" data-tab="condiciones-peligrosas">
                <i class="fa-solid fa-biohazard w-6"></i> Cond. Peligrosas
            </button>
            <button onclick="switchTab('agentes-fisicos')" class="w-full text-left px-6 py-3 hover:bg-blue-800 transition nav-item" data-tab="agentes-fisicos">
                <i class="fa-solid fa-volume-high w-6"></i> Agentes Físicos
            </button>
            <button onclick="switchTab('agentes-quimicos')" class="w-full text-left px-6 py-3 hover:bg-blue-800 transition nav-item" data-tab="agentes-quimicos">
                <i class="fa-solid fa-flask w-6"></i> Agentes Químicos
            </button>
            <button onclick="switchTab('agentes-biologicos')" class="w-full text-left px-6 py-3 hover:bg-blue-800 transition nav-item" data-tab="agentes-biologicos">
                <i class="fa-solid fa-virus w-6"></i> Agentes Biológicos
            </button>
            <button onclick="switchTab('peligros-circundantes')" class="w-full text-left px-6 py-3 hover:bg-blue-800 transition nav-item" data-tab="peligros-circundantes">
                <i class="fa-solid fa-house-chimney-crack w-6"></i> Peligros Circ.
            </button>
            <button onclick="switchTab('cumplimiento-normativo')" class="w-full text-left px-6 py-3 hover:bg-blue-800 transition nav-item" data-tab="cumplimiento-normativo">
                <i class="fa-solid fa-scale-balanced w-6"></i> Cumplimiento
            </button>
            <div class="border-t border-blue-800 my-2"></div>
            <button onclick="switchTab('programa-sst')" class="w-full text-left px-6 py-3 hover:bg-blue-800 transition nav-item" data-tab="programa-sst">
                <i class="fa-solid fa-calendar-days w-6"></i> Programa SST
            </button>
        </nav>
        <div class="p-4 bg-blue-900 text-xs text-center">
            v1.0.0 Enterprise
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center z-10">
            <h2 id="page-title" class="text-2xl font-bold text-corporate-darkGray">Condiciones Inseguras</h2>
            <div class="flex space-x-3">
                <button onclick="saveData()" class="btn-action btn-primary"><i class="fa-solid fa-save mr-2"></i>Guardar</button>
                <button onclick="generateProgram()" class="btn-action btn-secondary"><i class="fa-solid fa-wand-magic-sparkles mr-2"></i>Generar Programa</button>
                <button onclick="exportPDF()" class="btn-action bg-gray-800 text-white"><i class="fa-solid fa-file-pdf mr-2"></i>Descargar PDF</button>
            </div>
        </header>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-auto p-6 relative" id="main-container">
            
            <!-- Tab: Condiciones Inseguras -->
            <div id="tab-condiciones-inseguras" class="tab-content block">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between mb-4">
                        <h3 class="font-bold text-lg">Registro de Condiciones Inseguras</h3>
                        <button onclick="addRow('tabla-ci')" class="btn-action btn-add"><i class="fa-solid fa-plus"></i> Agregar Fila</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-ci">
                            <thead>
                                <tr>
                                    <th width="10%">Área</th>
                                    <th width="10%"><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Máquina</th>
                                    <th width="10%"><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Equipo</th>
                                    <th width="10%"><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Material</th>
                                    <th width="15%"><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>Condición Insegura</th>
                                    <th width="15%"><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>Incumplimiento Legal</th>
                                    <th width="10%">Evidencia</th>
                                    <th width="5%">Acción</th>
                                </tr>
                            </thead>
                            <tbody><!-- Rows generated by JS --></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Condiciones Peligrosas -->
            <div id="tab-condiciones-peligrosas" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between mb-4">
                        <h3 class="font-bold text-lg">Registro de Condiciones Peligrosas</h3>
                        <button onclick="addRow('tabla-cp')" class="btn-action btn-add"><i class="fa-solid fa-plus"></i> Agregar Fila</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-cp">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Máquina</th>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Equipo</th>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Herramienta</th>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Material</th>
                                    <th><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>Condición Peligrosa</th>
                                    <th>Evidencia</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Agentes Físicos -->
            <div id="tab-agentes-fisicos" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between mb-4">
                        <h3 class="font-bold text-lg">Agentes Físicos</h3>
                        <button onclick="addRow('tabla-af')" class="btn-action btn-add"><i class="fa-solid fa-plus"></i> Agregar Fila</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-af">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Proceso</th>
                                    <th>Personal Expuesto</th>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Agente Físico</th>
                                    <th>Fuente Generadora</th>
                                    <th><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>Efectos Salud</th>
                                    <th>Resultado Medición</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Agentes Químicos -->
            <div id="tab-agentes-quimicos" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between mb-4">
                        <h3 class="font-bold text-lg">Agentes Químicos</h3>
                        <button onclick="addRow('tabla-aq')" class="btn-action btn-add"><i class="fa-solid fa-plus"></i> Agregar Fila</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-aq">
                            <thead>
                                <tr>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Agente Químico</th>
                                    <th>Propiedades</th>
                                    <th>Proceso/Tarea</th>
                                    <th>Personal Expuesto</th>
                                    <th>Frecuencia</th>
                                    <th><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>Riesgos Salud</th>
                                    <th>Resultado Medición</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Agentes Biológicos -->
            <div id="tab-agentes-biologicos" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between mb-4">
                        <h3 class="font-bold text-lg">Agentes Biológicos</h3>
                        <button onclick="addRow('tabla-ab')" class="btn-action btn-add"><i class="fa-solid fa-plus"></i> Agregar Fila</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-ab">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Lugar Específico</th>
                                    <th>Tarea</th>
                                    <th>Trabajador Expuesto</th>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Agente Biológico</th>
                                    <th><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>Riesgos Salud</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Peligros Circundantes -->
            <div id="tab-peligros-circundantes" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between mb-4">
                        <h3 class="font-bold text-lg">Peligros Circundantes</h3>
                        <button onclick="addRow('tabla-pc')" class="btn-action btn-add"><i class="fa-solid fa-plus"></i> Agregar Fila</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-pc">
                            <thead>
                                <tr>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Peligro Identificado</th>
                                    <th>Distancia</th>
                                    <th>Descripción Amenaza</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Cumplimiento Normativo -->
            <div id="tab-cumplimiento-normativo" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between mb-4">
                        <h3 class="font-bold text-lg">Matriz de Cumplimiento Normativo</h3>
                        <button onclick="addRow('tabla-cn')" class="btn-action btn-add"><i class="fa-solid fa-plus"></i> Agregar Fila</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-cn">
                            <thead>
                                <tr>
                                    <th><i class="fa-solid fa-magnifying-glass text-orange-500 mr-1"></i>Instalación/Proceso</th>
                                    <th><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>NOM-STPS</th>
                                    <th><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>Título</th>
                                    <th>Fecha Pub.</th>
                                    <th>Artículos</th>
                                    <th><i class="fa-solid fa-wand-magic-sparkles text-blue-500 mr-1"></i>Requisito</th>
                                    <th>Responsable</th>
                                    <th>% Cumplimiento</th>
                                    <th>Evidencia</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Programa SST -->
            <div id="tab-programa-sst" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-6 bg-blue-50 p-4 rounded border border-blue-200">
                        <div>
                            <h3 class="font-bold text-lg text-corporate-blue">Programa Anual de SST</h3>
                            <p class="text-sm text-gray-600">Generado automáticamente basado en el diagnóstico y normativa vigente.</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="font-bold">Año:</label>
                            <input type="number" id="program-year" value="2024" class="w-24 font-bold text-center" style="width: 80px;">
                            <button onclick="generateProgram()" class="btn-action btn-secondary"><i class="fa-solid fa-rotate-right"></i> Regenerar</button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table id="tabla-programa">
                            <thead>
                                <tr>
                                    <th width="25%">Actividad Preventiva/Correctiva</th>
                                    <th width="15%">Ubicación (Área/Máq)</th>
                                    <th width="15%">Responsable</th>
                                    <th width="15%">Referencia Normativa</th>
                                    <th width="25%">Cronograma (Ene-Dic)</th>
                                    <th width="5%">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">
                                        <i class="fa-solid fa-chart-gantt text-4xl mb-2"></i>
                                        <p>Haga clic en "Generar Programa" en el menú superior para crear el plan anual.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Hidden Templates for PDF -->
    <div id="pdf-content" class="hidden p-8 bg-white">
        <!-- Content injected via JS -->
    </div>

<script>
    // --- BASE DE CONOCIMIENTO IA (NOM-STPS & Riesgos) ---
    const knowledgeBase = {
        keywords: {
            machines: ['prensa', 'torno', 'fresadora', 'sierra', 'lijadora', 'compresor', 'caldera', 'grúa', 'montacargas'],
            agents_physical: ['ruido', 'vibración', 'iluminación', 'temperatura', 'radiación', 'presión'],
            agents_chemical: ['solvente', 'ácido', 'base', 'gas', 'vapor', 'polvo', 'sílice', 'asbesto', 'pintura', 'thinner'],
            agents_biological: ['virus', 'bacteria', 'hongo', 'sangre', 'orina', 'residuos', 'moho'],
            dangers: ['desnivel', 'borde', 'altura', 'electricidad', 'incendio', 'sismo']
        },
        rules: [
            {
                trigger: ['prensa', 'torno', 'fresadora', 'sierra', 'maquinaria'],
                nom: 'NOM-004-STPS-1999',
                title: 'Sistemas de protección y dispositivos de seguridad en la maquinaria y equipo',
                article: '5.1',
                risk: 'Atrapamiento, aplastamiento, corte',
                condition: 'Falta de guardas o protecciones en partes móviles',
                requirement: 'Contar con guardas fijas o móviles que protejan las zonas de peligro.'
            },
            {
                trigger: ['ruido', 'sonido', 'decibeles'],
                nom: 'NOM-011-STPS-2001',
                title: 'Condiciones de seguridad e higiene en los centros de trabajo donde se genere ruido',
                article: '6.1',
                risk: 'Hipoacusia, pérdida auditiva, estrés',
                condition: 'Exposición a niveles superiores a 85 dB(A)',
                requirement: 'Implementar programa de conservación de la audición y EPP adecuado.'
            },
            {
                trigger: ['químico', 'solvente', 'ácido', 'gas', 'sustancia'],
                nom: 'NOM-018-STPS-2015',
                title: 'Sistema para la identificación y comunicación de peligros y riesgos por sustancias químicas peligrosas',
                article: '7.2',
                risk: 'Quemaduras, intoxicación, cáncer ocupacional',
                condition: 'Ausencia de etiquetas o Hojas de Seguridad (SDS)',
                requirement: 'Identificar contenedores con etiquetas normalizadas y contar con SDS actualizadas.'
            },
            {
                trigger: ['altura', 'techo', 'escalera', 'andamio'],
                nom: 'NOM-009-STPS-2011',
                title: 'Condiciones de seguridad para trabajar en altura',
                article: '8.1',
                risk: 'Caída a diferente nivel, golpes',
                condition: 'Trabajo en altura sin línea de vida o arnés',
                requirement: 'Uso de arnés de cuerpo completo anclado a punto seguro.'
            },
            {
                trigger: ['electricidad', 'tablero', 'cable', 'voltaje'],
                nom: 'NOM-029-STPS-2011',
                title: 'Mantenimiento de las instalaciones eléctricas en los centros de trabajo',
                article: '5.4',
                risk: 'Electrocución, arco eléctrico, quemaduras',
                condition: 'Instalaciones expuestas o sin señalización',
                requirement: 'Señalización de riesgo eléctrico y mantenimiento preventivo documentado.'
            },
            {
                trigger: ['extintor', 'fogo', 'incendio', 'combustible'],
                nom: 'NOM-002-STPS-2010',
                title: 'Prevención y protección contra incendios en los centros de trabajo',
                article: '6.3',
                risk: 'Quemaduras, asfixia, muerte',
                condition: 'Extintores vencidos o vías de evacuación bloqueadas',
                requirement: 'Contar con extintores vigentes y señalización de rutas de evacuación.'
            }
        ]
    };

    // --- ESTADO DE LA APLICACIÓN ---
    let appData = {
        'tabla-ci': [],
        'tabla-cp': [],
        'tabla-af': [],
        'tabla-aq': [],
        'tabla-ab': [],
        'tabla-pc': [],
        'tabla-cn': [],
        'programa': []
    };

    // --- INICIALIZACIÓN ---
    document.addEventListener('DOMContentLoaded', () => {
        loadData();
        renderAllTables();
        switchTab('condiciones-inseguras');
    });

    // --- NAVEGACIÓN ---
    function switchTab(tabId) {
        // Ocultar todos los contenidos
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));
        
        // Mostrar seleccionado
        const selected = document.getElementById(`tab-${tabId}`);
        if(selected) {
            selected.classList.remove('hidden');
            selected.classList.add('block');
        }

        // Actualizar sidebar
        document.querySelectorAll('.nav-item').forEach(el => {
            el.classList.remove('tab-active', 'text-corporate-blue', 'font-bold');
            el.classList.add('tab-inactive');
            if(el.dataset.tab === tabId) {
                el.classList.add('tab-active');
                el.classList.remove('tab-inactive');
            }
        });

        // Actualizar título
        const titles = {
            'condiciones-inseguras': 'Condiciones Inseguras',
            'condiciones-peligrosas': 'Condiciones Peligrosas',
            'agentes-fisicos': 'Agentes Físicos',
            'agentes-quimicos': 'Agentes Químicos',
            'agentes-biologicos': 'Agentes Biológicos',
            'peligros-circundantes': 'Peligros Circundantes',
            'cumplimiento-normativo': 'Cumplimiento Normativo',
            'programa-sst': 'Programa Anual SST'
        };
        document.getElementById('page-title').innerText = titles[tabId] || 'SST Pro';
    }

    // --- LÓGICA DE TABLAS ---
    function createInput(value, type, tableId, rowId, field, isAI = false, isOutput = false) {
        const input = document.createElement('input');
        input.value = value || '';
        input.type = type || 'text';
        
        if (isAI) {
            input.classList.add('ai-input');
            input.addEventListener('blur', () => analyzeAI(tableId, rowId, field, input.value));
            input.placeholder = "Escribe para analizar...";
        } else if (isOutput) {
            input.classList.add('ai-output');
            input.readOnly = true;
            input.placeholder = "Generado por IA";
        }

        input.addEventListener('input', (e) => {
            updateData(tableId, rowId, field, e.target.value);
        });
        return input;
    }

    function addRow(tableId) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        const rowId = Date.now().toString();
        
        // Definir columnas según tabla
        let columns = [];
        if(tableId === 'tabla-ci') columns = ['area', 'maquina', 'equipo', 'material', 'condicion', 'incumplimiento', 'evidencia'];
        if(tableId === 'tabla-cp') columns = ['area', 'maquina', 'equipo', 'herramienta', 'material', 'condicion', 'evidencia'];
        if(tableId === 'tabla-af') columns = ['area', 'proceso', 'personal', 'agente', 'fuente', 'efectos', 'resultado'];
        if(tableId === 'tabla-aq') columns = ['agente', 'propiedades', 'proceso', 'personal', 'frecuencia', 'riesgos', 'resultado'];
        if(tableId === 'tabla-ab') columns = ['area', 'lugar', 'tarea', 'trabajador', 'agente', 'riesgos'];
        if(tableId === 'tabla-pc') columns = ['peligro', 'distancia', 'descripcion'];
        if(tableId === 'tabla-cn') columns = ['instalacion', 'nom', 'titulo', 'fecha', 'articulos', 'requisito', 'responsable', 'cumplimiento', 'evidencia'];

        // Inicializar datos si no existen
        if (!appData[tableId]) appData[tableId] = [];
        
        const newRowData = { id: rowId };
        columns.forEach(col => newRowData[col] = '');
        appData[tableId].push(newRowData);

        renderTable(tableId);
    }

    function deleteRow(tableId, rowId) {
        appData[tableId] = appData[tableId].filter(r => r.id !== rowId);
        renderTable(tableId);
        saveData();
    }

    function updateData(tableId, rowId, field, value) {
        const row = appData[tableId].find(r => r.id === rowId);
        if(row) row[field] = value;
    }

    function renderAllTables() {
        Object.keys(appData).forEach(key => {
            if(key !== 'programa') renderTable(key);
        });
    }

    function renderTable(tableId) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        if(!tbody) return;
        tbody.innerHTML = '';

        appData[tableId].forEach(row => {
            const tr = document.createElement('tr');
            
            // Generar celdas dinámicamente según el tipo de tabla
            if(tableId === 'tabla-ci') {
                tr.innerHTML = `
                    <td>${createInput(row.area, 'text', tableId, row.id, 'area')}</td>
                    <td>${createInput(row.maquina, 'text', tableId, row.id, 'maquina', true)}</td>
                    <td>${createInput(row.equipo, 'text', tableId, row.id, 'equipo', true)}</td>
                    <td>${createInput(row.material, 'text', tableId, row.id, 'material', true)}</td>
                    <td>${createInput(row.condicion, 'text', tableId, row.id, 'condicion', false, true)}</td>
                    <td>${createInput(row.incumplimiento, 'text', tableId, row.id, 'incumplimiento', false, true)}</td>
                    <td>${createInput(row.evidencia, 'text', tableId, row.id, 'evidencia')}</td>
                    <td class="text-center"><button onclick="deleteRow('${tableId}', '${row.id}')" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button></td>
                `;
            } else if(tableId === 'tabla-cp') {
                tr.innerHTML = `
                    <td>${createInput(row.area, 'text', tableId, row.id, 'area')}</td>
                    <td>${createInput(row.maquina, 'text', tableId, row.id, 'maquina', true)}</td>
                    <td>${createInput(row.equipo, 'text', tableId, row.id, 'equipo', true)}</td>
                    <td>${createInput(row.herramienta, 'text', tableId, row.id, 'herramienta', true)}</td>
                    <td>${createInput(row.material, 'text', tableId, row.id, 'material', true)}</td>
                    <td>${createInput(row.condicion, 'text', tableId, row.id, 'condicion', false, true)}</td>
                    <td>${createInput(row.evidencia, 'text', tableId, row.id, 'evidencia')}</td>
                    <td class="text-center"><button onclick="deleteRow('${tableId}', '${row.id}')" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button></td>
                `;
            } else if(tableId === 'tabla-af') {
                tr.innerHTML = `
                    <td>${createInput(row.area, 'text', tableId, row.id, 'area')}</td>
                    <td>${createInput(row.proceso, 'text', tableId, row.id, 'proceso')}</td>
                    <td>${createInput(row.personal, 'text', tableId, row.id, 'personal')}</td>
                    <td>${createInput(row.agente, 'text', tableId, row.id, 'agente', true)}</td>
                    <td>${createInput(row.fuente, 'text', tableId, row.id, 'fuente')}</td>
                    <td>${createInput(row.efectos, 'text', tableId, row.id, 'efectos', false, true)}</td>
                    <td>${createInput(row.resultado, 'text', tableId, row.id, 'resultado')}</td>
                    <td class="text-center"><button onclick="deleteRow('${tableId}', '${row.id}')" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button></td>
                `;
            } else if(tableId === 'tabla-aq') {
                tr.innerHTML = `
                    <td>${createInput(row.agente, 'text', tableId, row.id, 'agente', true)}</td>
                    <td>${createInput(row.propiedades, 'text', tableId, row.id, 'propiedades')}</td>
                    <td>${createInput(row.proceso, 'text', tableId, row.id, 'proceso')}</td>
                    <td>${createInput(row.personal, 'text', tableId, row.id, 'personal')}</td>
                    <td>${createInput(row.frecuencia, 'text', tableId, row.id, 'frecuencia')}</td>
                    <td>${createInput(row.riesgos, 'text', tableId, row.id, 'riesgos', false, true)}</td>
                    <td>${createInput(row.resultado, 'text', tableId, row.id, 'resultado')}</td>
                    <td class="text-center"><button onclick="deleteRow('${tableId}', '${row.id}')" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button></td>
                `;
            } else if(tableId === 'tabla-ab') {
                tr.innerHTML = `
                    <td>${createInput(row.area, 'text', tableId, row.id, 'area')}</td>
                    <td>${createInput(row.lugar, 'text', tableId, row.id, 'lugar')}</td>
                    <td>${createInput(row.tarea, 'text', tableId, row.id, 'tarea')}</td>
                    <td>${createInput(row.trabajador, 'text', tableId, row.id, 'trabajador')}</td>
                    <td>${createInput(row.agente, 'text', tableId, row.id, 'agente', true)}</td>
                    <td>${createInput(row.riesgos, 'text', tableId, row.id, 'riesgos', false, true)}</td>
                    <td class="text-center"><button onclick="deleteRow('${tableId}', '${row.id}')" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button></td>
                `;
            } else if(tableId === 'tabla-pc') {
                tr.innerHTML = `
                    <td>${createInput(row.peligro, 'text', tableId, row.id, 'peligro', true)}</td>
                    <td>${createInput(row.distancia, 'text', tableId, row.id, 'distancia')}</td>
                    <td>${createInput(row.descripcion, 'text', tableId, row.id, 'descripcion')}</td>
                    <td class="text-center"><button onclick="deleteRow('${tableId}', '${row.id}')" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button></td>
                `;
            } else if(tableId === 'tabla-cn') {
                tr.innerHTML = `
                    <td>${createInput(row.instalacion, 'text', tableId, row.id, 'instalacion', true)}</td>
                    <td>${createInput(row.nom, 'text', tableId, row.id, 'nom', false, true)}</td>
                    <td>${createInput(row.titulo, 'text', tableId, row.id, 'titulo', false, true)}</td>
                    <td>${createInput(row.fecha, 'date', tableId, row.id, 'fecha')}</td>
                    <td>${createInput(row.articulos, 'text', tableId, row.id, 'articulos')}</td>
                    <td>${createInput(row.requisito, 'text', tableId, row.id, 'requisito', false, true)}</td>
                    <td>${createInput(row.responsable, 'text', tableId, row.id, 'responsable')}</td>
                    <td>${createInput(row.cumplimiento, 'number', tableId, row.id, 'cumplimiento')}</td>
                    <td>${createInput(row.evidencia, 'text', tableId, row.id, 'evidencia')}</td>
                    <td class="text-center"><button onclick="deleteRow('${tableId}', '${row.id}')" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button></td>
                `;
            }

            tbody.appendChild(tr);
        });
    }

    // --- MOTOR DE IA SEMÁNTICA ---
    function analyzeAI(tableId, rowId, field, value) {
        if(!value || value.length < 3) return;
        
        const lowerValue = value.toLowerCase();
        let match = null;

        // Buscar coincidencias en la base de conocimiento
        for(const rule of knowledgeBase.rules) {
            if(rule.trigger.some(keyword => lowerValue.includes(keyword))) {
                match = rule;
                break;
            }
        }

        if(match) {
            // Simular pequeño delay de "pensamiento"
            const row = appData[tableId].find(r => r.id === rowId);
            
            // Actualizar campos según el contexto de la tabla
            if(tableId === 'tabla-ci' || tableId === 'tabla-cp') {
                row.condicion = match.condition;
                row.incumplimiento = `${match.nom} Art. ${match.article}: ${match.requirement}`;
            } else if(tableId === 'tabla-af') {
                row.efectos = match.risk;
            } else if(tableId === 'tabla-aq' || tableId === 'tabla-ab') {
                row.riesgos = match.risk;
            } else if(tableId === 'tabla-cn') {
                row.nom = match.nom;
                row.titulo = match.title;
                row.articulos = match.article;
                row.requisito = match.requirement;
            } else if(tableId === 'tabla-pc') {
                // Para peligros circundantes, usamos la descripción de riesgo
                row.descripcion = `Riesgo detectado: ${match.risk}. Norma aplicable: ${match.nom}`;
            }

            renderTable(tableId);
            saveData();
        }
    }

    // --- GENERADOR DE PROGRAMA SST ---
    function generateProgram() {
        const year = document.getElementById('program-year').value;
        const activities = [];
        const months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

        // Procesar Condiciones Inseguras
        appData['tabla-ci'].forEach(row => {
            if(row.condicion || row.maquina) {
                activities.push({
                    activity: `Corregir: ${row.condicion || 'Condición insegura en ' + (row.maquina || 'área general')}`,
                    location: `${row.area} - ${row.maquina || ''}`,
                    responsible: "Jefe de Mantenimiento",
                    norm: row.incumplimiento.split(':')[0] || "NOM-004-STPS",
                    schedule: months.map(() => Math.random() > 0.8 ? 'X' : '') // Distribución aleatoria simple para demo
                });
            }
        });

        // Procesar Agentes Físicos
        appData['tabla-af'].forEach(row => {
            if(row.agente) {
                activities.push({
                    activity: `Monitorear y controlar: ${row.agente}`,
                    location: `${row.area} - ${row.proceso}`,
                    responsible: "Higiene Industrial",
                    norm: "NOM-011-STPS",
                    schedule: ['X','','','','X','','','','','X','',''] // Trimestral ejemplo
                });
            }
        });

        // Procesar Agentes Químicos
        appData['tabla-aq'].forEach(row => {
            if(row.agente) {
                activities.push({
                    activity: `Control de exposición: ${row.agente}`,
                    location: row.proceso,
                    responsible: "Seguridad Industrial",
                    norm: "NOM-018-STPS",
                    schedule: months.map((_, i) => i % 3 === 0 ? 'X' : '')
                });
            }
        });

        // Guardar y Renderizar
        appData['programa'] = activities;
        saveData();
        renderProgramTable();
        switchTab('programa-sst');
        alert(`Programa SST ${year} generado con ${activities.length} actividades.`);
    }

    function renderProgramTable() {
        const tbody = document.querySelector('#tabla-programa tbody');
        tbody.innerHTML = '';
        
        if(appData['programa'].length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-8">No hay actividades generadas. Ejecute el generador.</td></tr>`;
            return;
        }

        appData['programa'].forEach((act, index) => {
            const tr = document.createElement('tr');
            const scheduleHtml = act.schedule.map(s => s === 'X' ? '<div class="w-4 h-4 bg-orange-500 rounded mx-auto"></div>' : '<div class="w-4 h-4 bg-gray-200 rounded mx-auto"></div>').join('');
            
            tr.innerHTML = `
                <td class="font-medium">${act.activity}</td>
                <td>${act.location}</td>
                <td>${act.responsible}</td>
                <td class="text-xs">${act.norm}</td>
                <td><div class="flex justify-between text-xs text-gray-500 mb-1"><span>Ene</span><span>Dic</span></div><div class="grid grid-cols-12 gap-1">${scheduleHtml}</div></td>
                <td class="text-center"><button onclick="deleteProgramItem(${index})" class="text-red-500"><i class="fa-solid fa-trash"></i></button></td>
            `;
            tbody.appendChild(tr);
        });
    }

    function deleteProgramItem(index) {
        appData['programa'].splice(index, 1);
        saveData();
        renderProgramTable();
    }

    // --- PERSISTENCIA ---
    function saveData() {
        localStorage.setItem('sst_platform_data', JSON.stringify(appData));
    }

    function loadData() {
        const saved = localStorage.getItem('sst_platform_data');
        if(saved) {
            appData = JSON.parse(saved);
        }
    }

    // --- EXPORTAR PDF ---
    function exportPDF() {
        const element = document.getElementById('pdf-content');
        const year = document.getElementById('program-year').value;
        
        // Construir contenido HTML para el PDF
        let html = `
            <div style="font-family: Arial, sans-serif; color: #333;">
                <div style="border-bottom: 2px solid #1e3a8a; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="color: #1e3a8a; margin: 0;">Informe de Diagnóstico SST</h1>
                        <p style="margin: 5px 0; color: #666;">Plataforma Inteligente de Seguridad y Salud en el Trabajo</p>
                    </div>
                    <div style="text-align: right;">
                        <p><strong>Fecha:</strong> ${new Date().toLocaleDateString()}</p>
                        <p><strong>Año Programa:</strong> ${year}</p>
                    </div>
                </div>

                <h2 style="background-color: #f3f4f6; padding: 10px; color: #1e3a8a;">1. Resumen de Hallazgos</h2>
                <p>El presente documento detalla las condiciones inseguras, agentes y cumplimiento normativo detectados.</p>
                
                <h3 style="color: #f97316; margin-top: 20px;">Condiciones Inseguras Críticas</h3>
                <ul style="list-style-type: none; padding: 0;">
                    ${appData['tabla-ci'].map(r => `<li style="border-bottom: 1px solid #eee; padding: 5px 0;">• <strong>${r.area || 'General'}:</strong> ${r.condicion || 'Sin detectar'} <em>(${r.incumplimiento || ''})</em></li>`).join('')}
                </ul>

                <h3 style="color: #f97316; margin-top: 20px;">Agentes Físicos y Químicos</h3>
                <ul style="list-style-type: none; padding: 0;">
                    ${appData['tabla-af'].map(r => `<li>• <strong>${r.agente || 'N/A'}:</strong> ${r.efectos || 'Sin efectos registrados'}</li>`).join('')}
                    ${appData['tabla-aq'].map(r => `<li>• <strong>${r.agente || 'N/A'}:</strong> ${r.riesgos || 'Sin riesgos registrados'}</li>`).join('')}
                </ul>

                <h2 style="background-color: #f3f4f6; padding: 10px; color: #1e3a8a; margin-top: 30px;">2. Programa Anual de SST ${year}</h2>
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px;">
                    <thead>
                        <tr style="background-color: #1e3a8a; color: white;">
                            <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Actividad</th>
                            <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Responsable</th>
                            <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Normativa</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${appData['programa'].map(p => `
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">${p.activity}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${p.responsible}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${p.norm}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #999;">
                    <p>Documento generado automáticamente por SST Pro Platform.</p>
                    <p>Basado en normativa STPS vigente (México).</p>
                </div>
            </div>
        `;

        element.innerHTML = html;
        
        const opt = {
            margin:       10,
            filename:     `Diagnostico_SST_${year}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>
</body>
</html>