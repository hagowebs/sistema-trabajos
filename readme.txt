Estructura

app
|------- config                                     // Conexión y parámetros globales.
|          |------- config.php
|          |------- database.php
|
|------- api                                        // Scripts que devuelven JSON (API interna).
|          |------- usuarios
|          |           |------- listar.php
|          |           |------- crear.php
|          |           |------- editar.php
|          |           |------- eliminar.php
|          |
|          |------- trabajos
|          |           |------- listar.php
|          |           |------- crear.php
|          |           |------- editar.php
|          |           |------- eliminar.php
|          |           
|          |------- clientes
|                      |------- listar.php
|                      |------- crear.php
|                      |------- editar.php
|                      |------- eliminar.php
|
|------- views                                      // Pantallas principales (frontend con HTML + JS).
|          |------- usuarios.php
|          |------- trabajos.php
|          |------- clientes.php
|          |
|          |------- scripts
|                      |------- usuarios.js
|                      |------- trabajos.js
|                      |------- clientes.js
|
public                                              // Entrada pública (index, login, assets).
|------- assets
|          |------- css
|          |------- js
|          |------- images
|
|------- index.php
|------- login.php
|------- logout.php
|
vendor                                              // Librerías externas.
|------- tcpdf-php8
