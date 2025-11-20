<?php
// Verificación de datos enviados por POST
$datos = $_POST;
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
    <h1 class="mb-3">Verificación de Datos - Método POST</h1>
    <hr>
    <p class="lead">Los datos ingresados en el formulario se muestran a continuación:</p>

    <?php if (!empty($datos)) : ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Campo</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($datos as $clave => $valor): ?>
              <tr>
                <td><?= htmlspecialchars($clave) ?></td>
                <td>
                  <?php if ($clave === "imagen" && !empty($_FILES["imagen"]["name"])): ?>
                    <img src="Imagen/<?= htmlspecialchars($_FILES["imagen"]["name"]) ?>" alt="Imagen subida" width="150" class="img-thumbnail">
                  <?php else: ?>
                    <?= htmlspecialchars($valor) ?>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">No se han recibido datos (POST requiere servidor).</div>
    <?php endif; ?>

    <a href="Tarea3Post.html" class="btn btn-secondary mt-3">⬅️ Volver al formulario</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
