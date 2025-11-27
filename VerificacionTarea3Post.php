<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificación de Datos - POST</title>
</head>
<body>
  <h1>Verificación de Datos - Método POST</h1>
  <hr>
  <p>Los datos ingresados en el formulario se muestran a continuación:</p>

  <div id="resultado"></div>

  <p><a href="Tarea3Post.html">⬅️ Volver al formulario</a></p>

  <script>
    // Recuperar los datos del localStorage
    const datos = JSON.parse(localStorage.getItem("formularioF1"));

    if (datos) {
      let salida = "<table border='1' cellpadding='5'>";
      salida += "<tr><th>Campo</th><th>Valor</th></tr>";

      // Recorre cada dato
      for (const [clave, valor] of Object.entries(datos)) {
        if (clave === "imagen" && valor) {
          // Mostrar miniatura de imagen
          salida += `<tr><td>${clave}</td><td><img src="Imagen/${valor}" alt="Imagen subida" width="150"></td></tr>`;
        } else {
          salida += `<tr><td>${clave}</td><td>${valor}</td></tr>`;
        }
      }

      salida += "</table>";
      document.getElementById("resultado").innerHTML = salida;
    } else {
      document.getElementById("resultado").innerHTML = 
        "<p>No se han recibido datos (POST requiere servidor o simulación).</p>";
    }
  </script>
</body>
</html>