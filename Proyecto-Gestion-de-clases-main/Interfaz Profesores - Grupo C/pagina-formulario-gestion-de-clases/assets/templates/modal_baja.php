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
      <table>

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
          <?php
          $id_materia = $materia = $comision = $hora = $fecha = $aula = $temas = $novedades = $archivos = "";

          if (isset($_SESSION['NUMBER_CHECKBOX'])) {
            $valores_checkbox = (array)$_SESSION['NUMBER_CHECKBOX'];
            $valor_checkbox = implode("", $valores_checkbox);
            $id_usuario = $_SESSION['CODIGO_USUARIO'];

            $consulta = "SELECT CLASES.ID_CLASE, CLASES.CODIGO_USUARIO, USUXROL.CODIGO_ROL, MATERIAS.CODIGO_MATERIA, MATERIAS.NOMBRE_MATERIA, CLASES.COMISION, CLASES.AULA, CLASES.FECHA, CLASES.HORA, CLASES.TEMAS, CLASES.NOVEDADES, CLASES.ARCHIVOS FROM CLASES
              JOIN USUARIOS ON CLASES.CODIGO_USUARIO = USUARIOS.CODIGO_USUARIO
              JOIN MATERIAS ON CLASES.CODIGO_MATERIA = MATERIAS.CODIGO_MATERIA
              JOIN USUXROL ON USUXROL.CODIGO_USUARIO = CLASES.CODIGO_USUARIO
              WHERE CLASES.CODIGO_USUARIO = '$id_usuario'
              AND CLASES.ID_CLASE IN (";
              $consulta .= str_repeat("?,", count($valores_checkbox) - 1) . "?"; //El "?," se repite la cantidad de posiciones que tiene valores_checkbox -1 y luego lo concatena con un ultimo "?" .
                
              $consulta .= ")"; //Cierro la consulta.
              $stmt = $conn->prepare($consulta);   //$stmt : Sentecia Preparada.
                
              $stmt->bind_param(str_repeat("i", count($valores_checkbox)), ...$valores_checkbox);
              // el "i" se refiere a que solo permite numeros enteros.
              // se repite la cantidad de posiciones que tiene $valores_checkbox.
              // ...$valores_checkbox: Pone en los ? los valores de cada posicion del array .
              
              $stmt->execute(); //Ejecuto la consulta.

              $resultado = $stmt->get_result();  // Devuelve el resultado de la consulta y luego lo guardo en $resultado.

            if(isset($resultado)&& $resultado->num_rows>0){
              while($fila=$resultado->fetch_assoc()){ 
                ?>
                
                  <tr>
                    <td><?php echo $fila['NOMBRE_MATERIA']; ?></td>
                    <td><?php echo $fila['COMISION']; ?></td>
                    <td><?php echo $fila['AULA']; ?></td>
                    <td><?php echo $fila['HORA']; ?></td>
                    <td><?php echo $fila['FECHA']; ?></td>
                    <td><textarea class="td_textarea" rows="1" readonly><?php echo $fila['TEMAS']; ?></textarea></td>
                    <td><textarea class="td_textarea" rows="1" readonly><?php echo $fila['NOVEDADES']; ?></textarea></td>
                    <td><?php echo $fila['ARCHIVOS']; ?></td>
                  </tr>
                <?php
              }
            }
            else {
              echo "<tr><td colspan='8' style='font-size:20px;'>No se encontró ninguna clase registrada...</td></tr>";
            }
          }
          ?>
        </tbody>
      </table>

      <input type="submit" name="btn_Baja" value="Eliminar" class="btn_añadir">
    </form>
  </div>
</section>


<?php
if (isset($_POST['btn_Baja'])) {
  if (isset($_SESSION['NUMBER_CHECKBOX'])) {
    $valores_checkbox = $_SESSION['NUMBER_CHECKBOX'];
    
    $id_usuario = $_SESSION['CODIGO_USUARIO'];

    if (is_array($valores_checkbox)) {
      $query = "DELETE FROM CLASES WHERE ID_CLASE = ? AND CODIGO_USUARIO = ?";
      $stmt = $conn->prepare($query);
      if ($stmt) {
        foreach ($valores_checkbox as $id_clase) {
          $stmt->bind_param("is", $id_clase, $id_usuario);
          $stmt->execute();
        }

        if ($stmt->affected_rows > 0) {
          ?>
          <script src="https://cdn.tailwindcss.com"></script>

          <div class="flex min-h-screen items-center justify-center bg-transparent ">
            <div class="rounded-lg bg-gray-50 px-16 py-14">
              <div class="flex justify-center">
                <div class="rounded-full bg-blue-200 p-6 animate-spin">
                  <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-500 p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path fill="#ffffff" d="M32 0C14.3 0 0 14.3 0 32S14.3 64 32 64V75c0 42.4 16.9 83.1 46.9 113.1L146.7 256 78.9 323.9C48.9 353.9 32 394.6 32 437v11c-17.7 0-32 14.3-32 32s14.3 32 32 32H64 320h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V437c0-42.4-16.9-83.1-46.9-113.1L237.3 256l67.9-67.9c30-30 46.9-70.7 46.9-113.1V64c17.7 0 32-14.3 32-32s-14.3-32-32-32H320 64 32zM288 437v11H96V437c0-25.5 10.1-49.9 28.1-67.9L192 301.3l67.9 67.9c18 18 28.1 42.4 28.1 67.9z"/>
                    </svg>
                  </div>
                </div>
              </div>
              <h3 class="my-4 text-center text-3xl font-semibold text-gray-700">Eliminando..</h3>
              <p class="w-[230px] text-center font-normal text-gray-600">¡Se estan eliminando las clases!</p>
            </div>
          </div>
          <?php       
            unset($_SESSION['NUMBER_CHECKBOX']);
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
