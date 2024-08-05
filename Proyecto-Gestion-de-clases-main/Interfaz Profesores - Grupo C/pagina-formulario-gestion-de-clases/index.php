<?php
include("assets/php/conexion.php");
// Verificar si la sesión ya está activa
if (session_status() === PHP_SESSION_NONE) {
  session_start(); // Iniciar la sesión si no está activa
}

$_SESSION["CODIGO_USUARIO"] = 1;
date_default_timezone_set('America/Buenos_Aires');
// $_SESSION['NUMBER_CHECKBOX'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Clases</title>
  <!-- Librería WaterCSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
  <!-- Styles CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/modal.css">

  <script type="module" src="assets/js/app.js" defer></script>
  <script type="module" src="assets/js/main.js" defer></script>

  <link rel="icon" href="assets/img/icono.png">
</head>

<body>
  <header>
    <p class="name-page">Gestión de Clases</p>
    <nav id="nav-bar">
      <ul>
        <a href="#" target="_blank" class="nav-links">
          <li>Pagina principal</li>
        </a>
        <a href="#" target="_blank" class="nav-links">
          <li>Ayuda</li>
        </a>
        <a href="cerrar_sesion.php" onclick="return confirm('¿Desea Cerrar Sesión?')" target="_blank" class="nav-links">
          <li>Cerrar Sesión</li>
        </a>
      </ul>
    </nav>
  </header>

  <?php include("assets/templates/modal_alta.php"); ?>
  <?php include("assets/templates/modal_modificacion.php"); ?>
  <?php include("assets/templates/modal_baja.php"); ?>

  

  <main>
    
    <div class="contenedor-bienvenida-msg">
      <p>¡Bienvenido
        <?php
        $query_welcome = "SELECT NOMBRE_PERSONA, APELLIDO_PERSONA, CARGO FROM PERSONAS WHERE CARGO = 'Profesor'";
        $res_welcome = mysqli_query($conn, $query_welcome);
        if ($res_welcome) {
          $fila_welcome = mysqli_fetch_assoc($res_welcome);
        ?>
          <?php echo $fila_welcome['NOMBRE_PERSONA'] . " " . $fila_welcome['APELLIDO_PERSONA']; ?>! <span style="font-weight: bold; color:darkorange;">(<?php echo $fila_welcome['CARGO']; ?>)</span></p>
    <?php
        } else {
          echo "Hubo un error al hacer la consulta de Bienvenida: " . mysqli_error($conn);
        }
    ?>

    </div>


    <div class="container">

      <section id="menu">
        <div class="descripcion-menu">
          <h3 class="heading3-descripcion">¿Qué quieres hacer?</h3>
        </div>
        <div class="btn-menu">
          <button class="btn-alta btns-menu" id="id-btn-alta">Alta de clase</button>
          <button class="btn-modificar btns-menu" id="id-btn-modificar" disabled>Modificar una clase</button>
          <button class="btn-baja btns-menu" id="id-btn-baja" disabled>Baja de clase</button>
          <div class="imagen-menu">
            <img class="img-logo-menu" src="assets/img/logo.png" width="150" height="150" alt="Logo Sistema de Administración Universal S.A.U" title="Logo Sistema de Administración Universal S.A.U">
          </div>
        </div>
      </section>

      <section id="tabla-clases">
        <div class="contenedor-buscador-filtros">
          <div class="search">
            <input class="input-search" type="text" placeholder="Buscar">
            <input class="input-submit-search" type="submit" value="🔎">
          </div>
          <div class="filter">
            <select>
              <option value="">Filtrar</option>
              <option value="Por fecha">Por fecha</option>
              <option value="Por hora">Por hora</option>
              <option value="Por Materia">Por Materia</option>
            </select>
          </div>
        </div>
    

        <div class="contenedor-tabla">
        <table class="table-heads">
            <thead>
              <tr>
                <th class="columna-checkbox"></th>
                <th >Materia</th>
                <th >Comisión</th>
                <th >Aula</th>
                <th >Hora</th>
                <th >Fecha</th>
                <th >Temas</th>
                <th >Novedades</th>
                <th >Archivos</th>
              </tr>
            </thead>
        </table>
        <?php  buscarClases($conn);  ?>
        
        </div>

        </div>
      </section>
      
    </div>
  </main>

  <footer></footer>
</body>

</html>


<?php
function mostrarDatos($result)
{
  if (isset($result) && $result->num_rows > 0) {
    $id_clase_chkbx = null;
    while ($fila = mysqli_fetch_array($result)) {
      $id_clase_chkbx = $fila['ID_CLASE'];
      $_SESSION['NUMBER_CHECKBOX'] = $id_clase_chkbx;
?>
      <tr>
        <td class="columna-checkbox" ><input class="input-checkbox-register" id="chkbx-<?php echo $id_clase_chkbx; ?>" type="checkbox" name="seleccionar_registro" value="<?php echo $id_clase_chkbx; ?>"></td>
        <!-- <td><php echo $fila['TITULO_ABREVIADO']; ?></td> -->
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
      ?>
      <!-- Script para manejar el evento del checkbox. Para visualizar problemas futuros. -->
      <!-- <script>
        let checkbox<?php echo $id_clase_chkbx; ?> = document.getElementById("chkbx-<?php echo $id_clase_chkbx; ?>")
        if (checkbox<?php echo $id_clase_chkbx; ?>) {
          checkbox<?php echo $id_clase_chkbx; ?>.addEventListener("click", () => console.log(checkbox<?php echo $id_clase_chkbx; ?>));
        } else {
          console.warn("Checkbox no encontrado para ID <?php echo $id_clase_chkbx; ?>")
        }
      </script>  -->
<?php
    }
  } else {
    echo "<tr><td colspan='9' style='font-size:20px;'>No se encontró ninguna clase registrada...</td></tr>";
  }
}

?>

<?php
  function buscarClases($conn){
    $consulta = "SELECT materias.NOMBRE_MATERIA, clases.CODIGO_MATERIA
    FROM clases, usuarios, materias, usuxrol
    WHERE clases.CODIGO_USUARIO = usuarios.CODIGO_USUARIO
    AND clases.CODIGO_MATERIA = materias.CODIGO_MATERIA
    AND usuxrol.CODIGO_USUARIO = clases.CODIGO_USUARIO
    GROUP BY materias.NOMBRE_MATERIA";

    $res_materia = mysqli_query($conn, $consulta);
    if ($res_materia->num_rows > 0){
      while ($fila = $res_materia->fetch_assoc()) {
    $claseID = $fila['CODIGO_MATERIA'];
    ?>
    <label class="contenedor-materia">
            <input  class="checkLabel" type="checkbox" >
            <div class="titulo-materia">
                <b><?php echo $fila['NOMBRE_MATERIA'];  ?></b>
            </div>
            <div class="datos-materia">
              <table>
                <tbody>
                <?php
              $consulta = "SELECT clases.ID_CLASE, clases.CODIGO_USUARIO, usuxrol.CODIGO_ROL,
              materias.CODIGO_MATERIA, materias.NOMBRE_MATERIA,
              clases.COMISION, clases.AULA, clases.FECHA, clases.HORA, clases.TEMAS, clases.NOVEDADES,
              clases.ARCHIVOS
              FROM clases, usuarios, materias, usuxrol
              WHERE clases.CODIGO_USUARIO = usuarios.CODIGO_USUARIO
              AND clases.CODIGO_MATERIA = materias.CODIGO_MATERIA
              AND clases.CODIGO_MATERIA = $claseID
              AND usuxrol.CODIGO_USUARIO = clases.CODIGO_USUARIO";
              $resultado = mysqli_query($conn, $consulta);
              mostrarDatos($resultado);
              ?>
                </tbody>
              </table>
            </div>
        </label>
    <?php
      }
    }
    else {
      ?>
      <table>
      <tr><td colspan='9' style='font-size:20px;'>No se encontró ninguna clase registrada...</td></tr>
      </table>
      <?php
    }
  }
?>
