    <!-- Main -->
    
    <main class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Registro de Trabajos</h1>
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
                                        <?php if ($_SESSION['perfil'] == 'Administrador' || $_SESSION['perfil'] == 'Especial') { ?><div class="col-lg-2 col-md-8">
                                            <select class="form-control" id="encargado_filter">
                                                <option value="">Todos los encargados</option>
                                            </select>
                                        </div>
                                        <?php } ?><div class="col-lg-2 col-md-8">
                                            <select class="form-control" id="estado_filter">
                                                <option value="">Todos los estados</option>
                                                <option value="Pendiente">Pendiente</option>
                                                <option value="Cotización">Cotización</option>
                                                <option value="Diseño">Diseño</option>
                                                <option value="Taller">Taller</option>
                                                <option value="Producción">Producción</option>
                                                <option value="Terminado">Terminado</option>
                                                <option value="Archivado">Archivado</option>
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
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTrabajo">
                                        <i class="fas fa-plus"></i> Nuevo Trabajo
                                    </button>
                                </div>

                            </div>
                            <div class="card-body">

                                <!-- Tabla -->

                                <table id="tablaTrabajo" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Encargado</th>
                                            <th>Cliente</th>
                                            <th>Teléfono</th>
                                            <th>Trabajo</th>
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
                        <div class="row">
                            <input type="hidden" id="trabajoId" name="id">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vendedor">Vendedor</label>
                                    <input type="text" class="form-control" id="id_vendedor" readonly>
                                    <input type="hidden" name="id_vendedor" value="<?php echo $_SESSION["id"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_encargado">Encargado *</label>
                                    <select class="form-control" id="id_encargado" name="id_encargado" required>
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_cliente">Cliente *</label>
                                    <select class="form-control" id="id_cliente" name="id_cliente" style="width: 100%;">
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="trabajo">Trabajo *</label>
                                    <textarea class="form-control" id="trabajo" name="trabajo" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_inicial">Fecha Pedido *</label>
                                    <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_final">Fecha Entrega *</label>
                                    <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="precio">Total *</label>
                                    <input type="number" min="0" step=".01" class="form-control" id="precio" name="precio" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="anticipo">Anticipo *</label>
                                    <input type="number" step=".01" class="form-control" id="anticipo" name="anticipo" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="restante">Restante *</label>
                                    <input type="number" step=".01" class="form-control" id="restante" name="restante" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="envio">Envio *</label>
                                    <select class="form-control" id="envio" name="envio" required>
                                        <option value="En Tienda">En Tienda</option>
                                        <option value="A Domicilio">A Domicilio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="metodo_pago">Metodo de pago *</label>
                                    <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Crédito">Crédito</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="estado">Estado *</label>
                                    <select class="form-control" id="estado" name="estado" required>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Cotización">Cotización</option>
                                        <option value="Diseño">Diseño</option>
                                        <option value="Taller">Taller</option>
                                        <option value="Producción">Producción</option>
                                        <option value="Terminado">Terminado</option>
                                        <option value="Archivado">Archivado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <a id="btn_pagos" class="btn btn-default" onclick="pagos()" style="margin-bottom: 15px;">+ Pagos</a>
                        <div id="pagos" style="display: none;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="anticipo2">Anticipo 2</label>
                                    <input type="number" step=".01" class="form-control" id="anticipo2" name="anticipo2">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="restante2">Restante 2</label>
                                    <input type="number" step=".01" class="form-control" id="restante2" name="restante2" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="metodo_pago2">Metodo de pago 2</label>
                                    <select class="form-control" id="metodo_pago2" name="metodo_pago2">
                                        <option value="">Seleccionar</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Crédito">Crédito</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha2">Fecha 2</label>
                                    <input type="date" class="form-control" id="fecha2" name="fecha2">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="anticipo3">Anticipo 3</label>
                                    <input type="number" step=".01" class="form-control" id="anticipo3" name="anticipo3">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="restante3">Restante 3</label>
                                    <input type="number" step=".01" class="form-control" id="restante3" name="restante3" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="metodo_pago3">Metodo de pago 3</label>
                                    <select class="form-control" id="metodo_pago3" name="metodo_pago3">
                                        <option value="">Seleccionar</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Crédito">Crédito</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha3">Fecha 3</label>
                                    <input type="date" class="form-control" id="fecha3" name="fecha3">
                                </div>
                            </div>
                        </div>
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

