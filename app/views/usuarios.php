    <main class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Registro de Usuarios</h1>
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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalUsuario">
                                    <i class="fas fa-plus"></i> Nuevo Usuario
                                </button>
                                <div class="card-tools">
                                    
                                </div>
                            </div>
                            <div class="card-body">

                                <!-- Tabla -->
                                <table id="tablaUsuarios" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Usuario</th>
                                            <th>Perfil</th>
                                            <th>Estado</th>
                                            <th>Último Login</th>
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
    <div class="modal fade" id="modalUsuario" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="tituloModal">Nuevo Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formUsuario">
                    <div class="modal-body">
                        <input type="hidden" id="usuarioId" name="id">
                        <div class="form-group">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre *" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario *" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="clave" name="clave" placeholder="Clave *" required>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="perfil" name="perfil" required>
                                <option value="">Seleccionar Perfil *</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Especial">Especial</option>
                                <option value="Vendedor">Vendedor</option>
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

