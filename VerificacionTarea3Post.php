<?php
// Procesar POST y subida de archivo
$datos = $_POST;
$uploadPath = null;
$uploadError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Manejar archivo subido
  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['imagen'];
    if ($file['error'] === UPLOAD_ERR_OK) {
      // Validar tipo MIME de imagen
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $file['tmp_name']);
      finfo_close($finfo);
      $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
      if (array_key_exists($mime, $allowed)) {
        $ext = $allowed[$mime];
        $nameSafe = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
        $unique = time() . '_' . $nameSafe . '.' . $ext;
        $targetDir = __DIR__ . DIRECTORY_SEPARATOR . 'Imagen' . DIRECTORY_SEPARATOR;
        if (!is_dir($targetDir)) {
          mkdir($targetDir, 0755, true);
        }
        $target = $targetDir . $unique;
        if (move_uploaded_file($file['tmp_name'], $target)) {
          // Guardar ruta relativa para mostrar en HTML
          $uploadPath = 'Imagen/' . $unique;
        } else {
          $uploadError = 'Error al mover el archivo al directorio de destino.';
        }
      } else {
        $uploadError = 'Tipo de archivo no permitido. Solo imágenes JPG, PNG, GIF o WEBP.';
      }
    } else {
      $uploadError = 'Error de subida (código: ' . $file['error'] . ').';
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verificación de Datos - POST</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container my-5">
    <h1 class="mb-3">Verificación de Datos</h1>
    <hr>
    <p class="lead">Los datos ingresados en el formulario se muestran a continuación:</p>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>

      <?php if ($uploadError) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($uploadError) ?></div>
      <?php endif; ?>

      <div class="row g-4">
        <div class="col-12 col-md-5">
          <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
              <h5 class="card-title mb-3">Imagen subida</h5>
              <?php if ($uploadPath): ?>
                <img src="<?= htmlspecialchars($uploadPath) ?>" alt="Imagen subida" class="img-fluid rounded shadow-sm mb-3">
                <p class="small text-muted">Archivo guardado en <code><?= htmlspecialchars($uploadPath) ?></code></p>
              <?php else: ?>
                <div class="alert alert-secondary">No se subió ninguna imagen o hubo un problema.</div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-7">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title mb-3">Datos del formulario</h5>
              <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle">
                  <tbody>
                    <?php foreach ($datos as $clave => $valor): ?>
                      <tr>
                        <th class="text-end"><?= htmlspecialchars($clave) ?>:</th>
                        <td><?= nl2br(htmlspecialchars((string)$valor)) ?></td>
                      </tr>
                    <?php endforeach; ?>
                    <?php if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK): ?>
                      <tr>
                        <th class="text-end">Nombre original:</th>
                        <td><?= htmlspecialchars($_FILES['imagen']['name']) ?> (<?= round($_FILES['imagen']['size'] / 1024, 1) ?> KB)</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    <?php else: ?>
      <div class="alert alert-warning">No se han recibido datos (envía el formulario para ver la verificación).</div>
    <?php endif; ?>

    <a href="Tarea3Post.html" class="btn btn-secondary mt-3">⬅️ Volver al formulario</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
