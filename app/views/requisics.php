    <main class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Registro de Requisiciones</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <!-- Filtros -->

                                <div class="filter-container">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-4">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="daterange" name="daterange" readonly placeholder="Sel. fechas">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-8">
                                            <select class="form-control" id="estado_filter">
                                                <option value="">Todos</option>
                                                <option value="pendiente">Pendiente</option>
                                                <option value="pedido">Pedido</option>
                                                <option value="archivado">Entregado</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary w-100" id="btn_limpiar">
                                                    <i class="fas fa-eraser"></i> Limpiar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->

                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalRequisic">
                                        <i class="fas fa-plus"></i> Nueva Requisición
                                    </button>
                                </div>

                            </div>
                            <div class="card-body">

                                <!-- Tabla -->

                                <table id="tablaRequisic" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Requisición</th>
                                            <th>Nota</th>
                                            <th>Pedido</th>
                                            <th>Entrega</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Los datos se cargan vía AJAX -->
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal para crear / editar -->

    <div class="modal fade" id="modalRequisic" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloModal">Nueva Requisición</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formRequisic">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="requisicId" name="id">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="requisic">Requisición *</label>
                                    <textarea class="form-control" id="requisic" name="requisic" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nota">Nota</label>
                                    <input type="text" class="form-control" id="nota" name="nota">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_inicial">Fecha Pedido *</label>
                                    <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_final">Fecha Entrega *</label>
                                    <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado *</label>
                                    <select class="form-control" id="estado" name="estado" required>
                                        <option value="">Selecionar</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Pedido">Pedido</option>
                                        <option value="archivado">Entregado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnEnviar" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

