    <main class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Registro de Placas</h1>
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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPlaca">
                                    <i class="fas fa-plus"></i> Nueva Placa
                                </button>
                                <div class="card-tools">
                                    
                                </div>
                            </div>
                            <div class="card-body">

                                <!-- Filtros -->
                                <div class="filter-container">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="daterange" name="daterange" placeholder="Seleccionar fechas">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6">
                                            <select class="form-control" id="estado_filter">
                                                <option value="">Todos</option>
                                                <option value="pendiente">Pendiente</option>
                                                <option value="terminado">Terminado</option>
                                                <option value="entregado">Entregado</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-12">
                                            <button type="button" class="btn btn-secondary" id="btn_limpiar">
                                                <i class="fas fa-eraser"></i> Limpiar
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabla -->
                                <table id="tablaPlacas" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Diseño</th>
                                            <th>Cantidad</th>
                                            <th>Tamaño</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
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
    <div class="modal fade" id="modalPlaca" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="tituloModal">Nueva Placa</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formPlaca">
                    <div class="modal-body">
                        <input type="hidden" id="placaId" name="id">
                        <div class="form-group">
                            <input type="text" class="form-control" id="placa" name="placa" placeholder="Cliente *" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="diseno" name="diseno" placeholder="Diseño *" required>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad *" required>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="tamano" name="tamano" required>
                                <option value="">Seleccionar Tamaño *</option>
                                <option value="34x47">34x47</option>
                                <option value="51x40">51x40</option>
                                <option value="65x55">65x55</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="">Selecionar Estado</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Terminado">Terminado</option>
                                <option value="Entregado">Entregado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

