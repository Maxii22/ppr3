<?php
// Verificar si la sesión ya está activa
if (session_status() === PHP_SESSION_NONE) {
  session_start(); // Iniciar la sesión si no está activa
}
?>

<section class="modal2">
  <div class="modal2__container">
    <div class="btn-cerrar-modal2"><a href="#" class="modal2__close" title="Cerrar">X</a></div>

    <h2 class="modal2__title">Baja de Clases</h2>
    <p>¿Esta seguro de querer eliminar estas clases?</p>

    <form method="POST">
      <table id="table_modal_baja">

        <thead>
        <tr>
            <th class="th-class-materia">Materia</th>
            <th class="th-class-comision">Comisión</th>
            <th class="th-class-aula">Aula</th>
            <th class="th-class-hora">Hora</th>
            <th class="th-class-fecha">Fecha</th>
            <th class="th-class-temas">Temas</th>
            <th class="th-class-novedades">Novedades</th>
            <th class="th-class-archivos">Archivos</th>
          </tr>
        </thead>

        <tbody>
         
        </tbody>
      </table>

      <input type="submit" name="btn_Baja" value="Eliminar" class="btn_añadir">
    </form>
  </div>
</section>


<?php
if (isset($_POST['btn_Baja'])) {
  if (isset($_SESSION['CLASES_SELECCIONADAS'])) {
    $clases_seleccionadas = $_SESSION['CLASES_SELECCIONADAS'];
    
    $id_usuario = $_SESSION['CODIGO_USUARIO'];

    if (is_array($clases_seleccionadas)) {
      $query = "DELETE FROM CLASES WHERE ID_CLASE = ? AND CODIGO_USUARIO = ?";
      $stmt = $conn->prepare($query);
      if ($stmt) {
        foreach ($clases_seleccionadas as $id_clase) {
          $stmt->bind_param("is", $id_clase, $id_usuario);
          $stmt->execute();
        }

        if ($stmt->affected_rows > 0) {
          ?>
            <div class="notificacion-container">
                <div class="notificacion-message-box">
                <div class="notificacion-icon-container container-icon-editar">
                <div class="notificacion-icon icon-editar ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="notificacion-svg-icon">
                        <path fill="#ffffff" d="M32 0C14.3 0 0 14.3 0 32S14.3 64 32 64V75c0 42.4 16.9 83.1 46.9 113.1L146.7 256 78.9 323.9C48.9 353.9 32 394.6 32 437v11c-17.7 0-32 14.3-32 32s14.3 32 32 32H64 320h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V437c0-42.4-16.9-83.1-46.9-113.1L237.3 256l67.9-67.9c30-30 46.9-70.7 46.9-113.1V64c17.7 0 32-14.3 32-32s-14.3-32-32-32H320 64 32zM288 437v11H96V437c0-25.5 10.1-49.9 28.1-67.9L192 301.3l67.9 67.9c18 18 28.1 42.4 28.1 67.9z"/>
                    </svg>
                </div>
            </div>
                    <h3 class="notificacion-heading">Eliminado...</h3>
                    <p class="notificacion-text">¡Las clases se estan eliminando!</p>
                </div>
            </div>
          <?php       
            unset($_SESSION['CLASES_SELECCIONADAS']);
          ?>
          <script>  document.body.classList.add('no-scroll');   setTimeout('  window.location="index.php";', 2000);   </script>

      <?php
        } 
        else {
          echo "<tr><td colspan='6' style='font-size:20px';>No se han podido eliminar las clases.</td></tr>";
        }
        $stmt->close();
      } 
      else {
        echo "<tr><td colspan='6' style='font-size:20px';>Error en la preparación de la consulta de eliminación.</td></tr>";
      }
    }
  } 
  else {
    echo "<tr><td colspan='6' style='font-size:20px';>No se han seleccionado clases para eliminar.</td></tr>";
  }
} 
else {
  //echo "<tr><td colspan='6' style='font-size:20px';>No se pudo eliminar las clases. Error con el Botón.</td></tr>";
}
?>
