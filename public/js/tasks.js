// Función para mostrar alertas
function showAlert(message, type = 'success') {
    const alertContainer = $('#alertContainer');
    alertContainer.text(message).removeClass('d-none').addClass(`alert-${type}`);
    setTimeout(function() {
        alertContainer.addClass('d-none').removeClass(`alert-${type}`);
    }, 3000);
}

// Listar tareas
// GET /api/tasks
let currentPage = 1;
let currentFilter = 'all';

function loadTasks(page = 1, filter = currentFilter) {
    currentPage = page;
    currentFilter = filter;

    $.get(`/api/tasks?page=${page}&filter=${filter}`, function(response) {
        console.log(response);
        const tasks = response.data; // Asegúrate de acceder a la propiedad 'data'
        let rows = '';
        tasks.forEach(task => {
            rows += `
                <tr>
                    <td>${task.id}</td>
                    <td>${task.title}</td>
                    <td>${task.description || ''}</td>
                    <td>${task.status}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="showDetailsModal(${task.id})">
                            <i class="bi bi-eye"></i> Detalles
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="showEditModal(${task.id})">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">
                            <i class="bi bi-trash"></i> 
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#taskTable').html(rows);
        renderPagination(response);
    });
}

function renderPagination(response) {
    const pagination = $('#pagination');
    pagination.html('');
    if (response.prev_page_url) {
        pagination.append(`<li class="page-item"><a class="page-link" href="#" onclick="loadTasks(${response.current_page - 1}, '${currentFilter}')">Anterior</a></li>`);
    }
    for (let i = 1; i <= response.last_page; i++) {
        pagination.append(`<li class="page-item ${i === response.current_page ? 'active' : ''}"><a class="page-link" href="#" onclick="loadTasks(${i}, '${currentFilter}')">${i}</a></li>`);
    }
    if (response.next_page_url) {
        pagination.append(`<li class="page-item"><a class="page-link" href="#" onclick="loadTasks(${response.current_page + 1}, '${currentFilter}')">Siguiente</a></li>`);
    }
}


// Crear una nueva tarea
// POST /api/tasks
$('#addTaskForm').submit(function(e) {
    e.preventDefault();
    const taskData = {
        title: $('#title').val(),
        description: $('#description').val(),
        status: $('#status').val(),
    };
    $.ajax({
        url: '/api/tasks',
        type: 'POST',
        data: taskData,
        success: function() {
            $('#addTaskModal').modal('hide');
            loadTasks();
            showAlert('Tarea creada correctamente');
        },
        error: function() {
            showAlert('Ocurrió un error al crear la tarea', 'danger');
        }
    });
    // limpiar los campos del formulario
    $('#title').val('');
    $('#description').val('');
    $('#status').val('pendiente');
});

// Editar una tarea
// PUT /api/tasks/:id
function showEditModal(id) {
    $.get(`/api/tasks/${id}`, function(task) {
        $('#editTaskId').val(task.id);
        $('#editTitle').val(task.title);
        $('#editDescription').val(task.description);
        $('#editStatus').val(task.status);
        $('#editTaskModal').modal('show');
    });
}

$('#editTaskForm').submit(function(e) {
    e.preventDefault();
    const id = $('#editTaskId').val();
    const taskData = {
        title: $('#editTitle').val(),
        description: $('#editDescription').val(),
        status: $('#editStatus').val(),
    };
    $.ajax({
        url: `/api/tasks/${id}`,
        type: 'PUT',
        data: taskData,
        success: function() {
            $('#editTaskModal').modal('hide');
            loadTasks();
            showAlert('Tarea actualizada correctamente');
        },
        error: function() {
            showAlert('Ocurrió un error al actualizar la tarea', 'danger');
        }
    });
});

// Mostrar detalles de una tarea
function showDetailsModal(id) {
    $.get(`/api/tasks/${id}`, function(task) {
        $('#detailsTaskId').text(task.id);
        $('#detailsTaskTitle').text(task.title);
        $('#detailsTaskDescription').text(task.description || 'No hay descripción');
        $('#detailsTaskStatus').text(task.status);
        $('#detailsTaskModal').modal('show');
    });
}

// Eliminar una tarea
// DELETE /api/tasks/:id
function deleteTask(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
        $.ajax({
            url: `/api/tasks/${id}`,
            type: 'DELETE',
            success: function() {
                loadTasks();
                showAlert('Tarea eliminada correctamente');
            },
            error: function() {
                showAlert('Ocurrió un error al eliminar la tarea', 'danger');
            }
        });
    }
}

// Cargar las tareas al iniciar 
$(document).ready(function() {
    loadTasks();
});