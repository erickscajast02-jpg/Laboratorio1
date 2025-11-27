<?php
// Procesar POST y subida de archivo
function safe($v){ return htmlspecialchars($v); }

$received = !empty($_POST);
$uploadResult = null;
if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['imagen'];
    // validaciones básicas
    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['error'] === UPLOAD_ERR_OK) {
        if ($file['size'] <= $maxSize && in_array(mime_content_type($file['tmp_name']), $allowed)) {
            $uploadsDir = __DIR__ . DIRECTORY_SEPARATOR . 'Imagen';
            if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
            // Generar nombre único para evitar colisiones
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $name = pathinfo($file['name'], PATHINFO_FILENAME);
            $name = preg_replace('/[^A-Za-z0-9_-]/', '', $name);
            $targetName = $name . '_' . time() . '.' . $ext;
            $targetPath = $uploadsDir . DIRECTORY_SEPARATOR . $targetName;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $uploadResult = ['ok' => true, 'filename' => $targetName];
            } else {
                $uploadResult = ['ok' => false, 'error' => 'No fue posible mover el archivo al directorio de destino.'];
            }
        } else {
            $uploadResult = ['ok' => false, 'error' => 'Tipo de archivo no permitido o tamaño excede 5MB.'];
        }
    } else {
        $uploadResult = ['ok' => false, 'error' => 'Error en la subida: ' . $file['error']];
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verificación de Datos - POST</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow p-6">
      <h1 class="text-2xl font-bold mb-2">Verificación de Datos</h1>
      <p class="text-sm text-gray-600 mb-4">Los datos enviados desde el formulario aparecen a continuación. La imagen subida (si se proporcionó) se guarda en <code>Imagen/</code>.</p>

      <?php if (!$received && !$uploadResult): ?>
        <div class="p-4 rounded bg-yellow-50 border border-yellow-200 text-yellow-800">No se han recibido datos. Asegúrate de enviar el formulario desde el servidor (POST + enctype="multipart/form-data").</div>
      <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <h2 class="text-lg font-semibold mb-2">Campos enviados</h2>
            <dl class="divide-y divide-gray-200 bg-gray-50 rounded-lg p-3">
              <?php foreach ($_POST as $k => $v): ?>
                <div class="py-2 flex justify-between items-center">
                  <dt class="font-medium text-sm text-gray-700"><?= safe($k) ?></dt>
                  <dd class="text-sm text-gray-900"><?= nl2br(safe($v)) ?></dd>
                </div>
              <?php endforeach; ?>
            </dl>
          </div>

          <div>
            <h2 class="text-lg font-semibold mb-2">Imagen subida</h2>
            <?php if ($uploadResult === null): ?>
              <div class="rounded p-4 bg-gray-50 text-gray-700">No se envió imagen.</div>
            <?php elseif ($uploadResult['ok']): ?>
              <div class="rounded-lg overflow-hidden shadow">
                <img src="Imagen/<?= safe($uploadResult['filename']) ?>" alt="Imagen subida" class="w-full h-64 object-cover">
              </div>
              <p class="mt-2 text-sm text-gray-600">Archivo guardado: <strong><?= safe($uploadResult['filename']) ?></strong></p>
            <?php else: ?>
              <div class="rounded p-4 bg-red-50 text-red-800">Error: <?= safe($uploadResult['error']) ?></div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

      <div class="mt-6 flex justify-start">
        <a href="Tarea3Post.html" class="inline-block bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">⬅️ Volver al formulario</a>
      </div>
    </div>
  </div>

</body>
</html>
