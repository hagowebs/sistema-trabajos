<!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Gestión de Trabajos</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Trabajos</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTrabajo">
                                        <i class="fas fa-plus"></i> Nuevo Trabajo
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tablaTrabajo" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Empresa</th>
                                            <th>Ubicación</th>
                                            <th>Tipo</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Los datos se cargarán vía AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal para agregar/editar trabajo -->
    <div class="modal fade" id="modalTrabajo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloModal">Nuevo Trabajo</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formTrabajo">
                    <div class="modal-body">
                        <input type="hidden" id="trabajoId" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titulo">Título del Trabajo *</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="empresa">Empresa *</label>
                                    <input type="text" class="form-control" id="empresa" name="empresa" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ubicacion">Ubicación *</label>
                                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_empleo">Tipo de Empleo *</label>
                                    <select class="form-control" id="tipo_empleo" name="tipo_empleo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Tiempo Completo">Tiempo Completo</option>
                                        <option value="Tiempo Parcial">Tiempo Parcial</option>
                                        <option value="Freelance">Freelance</option>
                                        <option value="Remoto">Remoto</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salario">Salario</label>
                                    <input type="number" class="form-control" id="salario" name="salario" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select class="form-control" id="estado" name="estado">
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción *</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
