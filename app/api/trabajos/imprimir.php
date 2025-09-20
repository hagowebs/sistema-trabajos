<?php // app/api/trabajos/imprimir.php

require_once '../../config/database.php';

// Librería TCPDF
require_once '../../../vendor/tcpdf-php8/tcpdf.php';

$id = $_GET['id'] ?? 0;

try {

    // === 1. OBTENER DATOS DEL TRABAJO ===
    $query = "SELECT 
                t.id, 
                t.trabajo, 
                t.fecha_inicial, 
                t.fecha_final, 
                t.precio,
                t.anticipo,
                t.restante,
                t.anticipo2,
                t.anticipo3,
                c.nombre AS cliente,
                c.direccion,
                c.documento,
                c.telefono,
                c.email
            FROM trabajos t
            LEFT JOIN clientes c ON t.id_cliente = c.id
            WHERE t.id = :id
            LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $trabajo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trabajo) {
        throw new Exception("Trabajo no encontrado.");
    }

    // === 2. CREAR PDF ===
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->startPageGroup();
    $pdf->AddPage();

    // Bloque 1 - Encabezado
    $bloque1 = <<<EOF
    <table>
        <tr>
            <td style="width:150px">
                <img src="../../../public/images/logo-negro-bloque.jpg">
            </td>
            <td style="width:20px;"></td>
            <td style="width:200px; font-size:8.5px;">
                Calle Río Papaloapan #300<br>
                Col. Valle del Sur, 34120 Durango.<br>
                pipdirecto@hotmail.com
            </td>
            <td style="width:20px;"></td>
            <td style="width:100px; font-size:8.5px;">
                Teléfono: (618)811-1757<br>
                Whatsapp: (618)102-5467
            </td>
            <td style="width:50px; color:red">
                <br>Folio<br>{$trabajo['id']}
            </td>
        </tr>
    </table>
EOF;
    $pdf->writeHTML($bloque1, false, false, false, false, '');

    // Bloque 2 - Cliente
    $bloque2 = <<<EOF
    <table style="font-size:10px; padding:5px 10px;">
        <tr>
            <td style="border:1px solid #666; width:390px;">
                Cliente: {$trabajo['cliente']}<br>
                Teléfono: {$trabajo['telefono']}<br>
                Email: {$trabajo['email']}
            </td>
            <td style="border:1px solid #666; width:150px;">
                Recibido:<br>
                {$trabajo['fecha_inicial']}<br>
                Entrega:<br>
                {$trabajo['fecha_final']}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border:1px solid #666; height:200px;">
                {$trabajo['trabajo']}
            </td>
        </tr>
    </table>
EOF;
    $pdf->writeHTML($bloque2, false, false, false, false, '');

    // Bloque 3 - Dirección y precios
    $bloque3 = <<<EOF
    <table style="font-size:10px; padding:5px 10px;">
        <tr>
            <td style="width:390px;">
                {$trabajo['direccion']}<br>
                {$trabajo['documento']}
            </td>
            <td style="border:1px solid #666; width:150px;">
                Total: {$trabajo['precio']}<br>
                Anticipo: {$trabajo['anticipo']}<br>
                Restante: {$trabajo['restante']}
            </td>
        </tr>
    </table>
EOF;
    $pdf->writeHTML($bloque3, false, false, false, false, '');

    // Bloque 4 - Otros pagos
    if (!empty($trabajo['anticipo2']) || !empty($trabajo['anticipo3'])) {
        $bloque4 = <<<EOF
        <table style="font-size:10px; padding:5px 10px;">
            <tr>
                <td style="width:390px;"></td>
                <td style="border:1px solid #666; width:150px;">
                    Otros pagos<br>
                    Pago 2: {$trabajo['anticipo2']}<br>
                    Pago 3: {$trabajo['anticipo3']}
                </td>
            </tr>
        </table>
EOF;
        $pdf->writeHTML($bloque4, false, false, false, false, '');
    }

    // === 3. SALIDA DEL PDF ===
    $pdf->Output("trabajo_{$trabajo['id']}.pdf", "I");

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
