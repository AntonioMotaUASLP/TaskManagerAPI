<!doctype html>
<html lang="en">
    <head>
        <title>Task Manager Home</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <!-- Bootstrap Icons CSS v1.8.1 -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
            rel="stylesheet"
        />

    </head>

    <body>
        <header>
            <nav class="navbar bg-body-tertiary">
                <div class="container">
                    <a class="navbar-brand" href="#">
                    <img src="{{ asset('img/Tekleen-Logo_Full-Color-Small.png') }}" alt="Tekleen"  height="32">
                    </a>
                </div>
            </nav>
        </header>
        <main>
            <div class="container mt-5">
                <h1 class="text-center">Administrador de Tareas</h1>
                <!-- div para Alerta -->
                <div class="container mt-5">
                    <div id="alertContainer" class="alert d-none" role="alert"></div>
                </div>
                <!-- Filtros -->
                <!-- Tabla de Tareas -->
                <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addTaskModal">Agregar Tarea</button>
                <!-- Filtros de tareas -->
                <div class="btn-group mb-1 mt-1 my-3" role="group" aria-label="Filtros de tareas">
                    <button type="button" class="btn btn-secondary" onclick="loadTasks(1, 'all')">Todas</button>
                    <button type="button" class="btn btn-secondary" onclick="loadTasks(1, 'pendiente')">Pendientes</button>
                    <button type="button" class="btn btn-secondary" onclick="loadTasks(1, 'en progreso')">En Progreso</button>
                    <button type="button" class="btn btn-secondary" onclick="loadTasks(1, 'completada')">Completadas</button>
                </div>
                <!-- Tabla de Tareas -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                            
                        </tr>
                    </thead>
                    <tbody id="taskTable">
                        <!-- Aquí se cargarán las tareas con AJAX -->
                    </tbody>
                </table>   
                <nav>
                    <ul id="pagination" class="pagination justify-content-center">
                        <!-- Aquí se cargarán los enlaces de paginación con AJAX -->
                    </ul>
                </nav> 
            </div>

                                                <!-- MODALES --> 
            <!-- Modal para Crear Tarea -->
            <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTaskModalLabel">Agregar Tarea</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addTaskForm">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Título</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="en progreso">En Progreso</option>
                                        <option value="completada">Completada</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Editar Tarea -->
            <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"> 
                            <h5 class="modal-title" id="editTaskModalLabel">Editar Tarea</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editTaskForm">
                                <input type="hidden" id="editTaskId">
                                <div class="mb-3">
                                    <label for="editTitle" class="form-label">Título</label>
                                    <input type="text" class="form-control" id="editTitle" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editDescription" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="editDescription" name="description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editStatus" class="form-label">Estado</label>
                                    <select class="form-control" id="editStatus" name="status" required>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="en progreso">En Progreso</option>
                                        <option value="completada">Completada</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Ver Detalles de Tarea -->
            <div class="modal fade" id="detailsTaskModal" tabindex="-1" aria-labelledby="detailsTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsTaskModalLabel">Detalles de la Tarea</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>ID:</strong> <span id="detailsTaskId"></span></p>
                            <p><strong>Título:</strong> <span id="detailsTaskTitle"></span></p>
                            <p><strong>Descripción:</strong> <span id="detailsTaskDescription"></span></p>
                            <p><strong>Estado:</strong> <span id="detailsTaskStatus"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>

        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- tasks.js -->
        <script src="{{ asset('js/tasks.js') }}"></script>
    </body>
</html>
