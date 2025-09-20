    <!-- Main -->
    
    <main class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Registro de Productos</h1>
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

                                <!-- Botones -->

                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalProducto">
                                        <i class="fas fa-plus"></i> Nuevo Producto
                                    </button>
                                </div>

                            </div>
                            <div class="card-body">

                                <!-- Tabla -->

                                <table id="tablaProducto" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Producto</th>
                                            <th>Categoría</th>
                                            <th>Precio</th>
                                            <th>Maquila</th>
                                            <th>Mayoreo</th>
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

    <div class="modal fade" id="modalProducto" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="tituloModal">Nuevo Producto</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formProducto">
                    <div class="modal-body">
                        <input type="hidden" id="productoId" name="id">
                        <div class="form-group">
                            <label for="producto">Porducto *</label>
                            <input type="text" class="form-control" id="producto" name="producto" required>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría *</label>
                            <select class="form-control" id="categoria" name="categoria" required>
                                <option value="">Selecionar</option>
                                <option value="Diseño">Diseño</option>
                                <option value="Imp Flujo">Imp Flujo</option>
                                <option value="Imp Laser">Imp Laser</option>
                                <option value="Imp Offset">Imp Offset</option>
                                <option value="Plotter HP">Plotter HP</option>
                                <option value="Vinil Corte">Vinil Corte</option>
                                <option value="Textil">Textil</option>
                                <option value="DTF UV">DTF UV</option>
                                <option value="DTF Textil">DTF Textil</option>
                                <option value="Placa">Placa</option>
                                <option value="Promos">Promos</option>
                                <option value="Lona">Lona</option>
                                <option value="Microperforado">Microperforado</option>
                                <option value="Tesis">Tesis</option>
                                <option value="Web">Web</option>
                                <option value="Varios">Varios</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio *</label>
                            <input type="text" class="form-control" id="precio" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="maquila">Maquila</label>
                            <input type="text" class="form-control" id="maquila" name="maquila">
                        </div>
                        <div class="form-group">
                            <label for="mayoreo">Mayoreo</label>
                            <input type="text" class="form-control" id="mayoreo" name="mayoreo">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnEnviar" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

