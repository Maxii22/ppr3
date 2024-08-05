<?php
include("assets/php/conexion.php");
// session_start();
// $_SESSION["CODIGO_USUARIO"] = 1;


// Verificar si la sesión ya está activa
if (session_status() === PHP_SESSION_NONE) {
  session_start(); // Iniciar la sesión si no está activa
}

?>

<section class="modal">
  <div class="modal__container">
    <div class="btn-cerrar-modal"> <a href="#" class="modal__close" title="Cerrar">X</a> </div>

    <h2 class="modal__title">Alta de Clase</h2>

    <form method="post">
      <div class="form-labels">

        <label for="materia-id">
          Materia
          <select  class="inputs-modal" name="materia" id="materia-id">
            <option hidden value="">Seleccione la materia</option>
            <?php
            obtenerMaterias($conn);
            ?>
          </select>
        </label>

        <label for="comision-id">
          Comision
          <select class="inputs-modal" name="comision" id="comision-id">
            <option hidden value="">Seleccione la comision</option>
            <option value="1ro1ra">1ro1ra</option>
            <option value="1ro2da">1ro2da</option>
            <option value="1ro3ra">1ro3ra</option>
            <option value="2do1ra">2do1ra</option>
            <option value="2do2da">2do2da</option>
            <option value="3ro1ra">3ro1ra</option>
            <option value="3ro2da">3ro2da</option>
          </select>
        </label>

        <label for="aula-id">
          Aula:
          <input type="number" min="1" max=100 placeholder="Ingrese número de aula" class="inputs-modal" name="aula" id="aula-id">
        </label>

        <label for="hora-id">
          Hora
          <select class="inputs-modal" name="hora" id="hora-id">
            <option hidden value="">Seleccione hora</option>
            <option value="8:00-10:00">8:00-10:00</option>
            <option value="10:10-12:00">10:10-12:00</option>
            <option value="8:00-12:00">8:00-12:00</option>
            <option value="18:00-22:00">18:00-22:00</option>
            <option value="20:10-22:00">20:10-22:00</option>
            <option value="18:00-20:00">18:00-20:00</option>
          </select>
        </label>

        <label for="fecha-id">
          Fecha
          <input class="inputs-modal" type="date" name="fecha" id="fecha-id"  value="<?php echo date('Y-m-d'); ?>">
        </label>

        <textarea name="temas" maxlength="100" rows="2" placeholder="Ingresá una tarea" class="textarea-form-class"></textarea>

        <textarea name="novedad" maxlength="100" rows="2" placeholder="¿Alguna Novedad?" class="textarea-form-class"></textarea>

        <label for="file-id" class="form-label">Subir algún archivo</label>
        <input type="file" class="form-control" name="archivo" id="file-id" placeholder="" aria-describedby="fileHelpId" />


      </div>

      <input type="submit" name="btn_Añadir" value="Añadir" class="btn_añadir">

    </form>

    <h3 class="bad">¡Por favor complete los campos indicados de manera correcta!</h3>

  </div>
</section>

<?php
$id_usuario = 1;

function obtenerMaterias($conn)
{
  $query_materia = "SELECT MATERIAS.NOMBRE_MATERIA, MATERIAS.CODIGO_MATERIA FROM materias";
  $res_materia = mysqli_query($conn, $query_materia);
  if ($res_materia) {
    while ($fila_materia = mysqli_fetch_assoc($res_materia)) {
?>
      <option value="<?php echo $fila_materia['CODIGO_MATERIA']; ?>"><?php echo $fila_materia['NOMBRE_MATERIA']; ?></option>
    <?php
    }
  } else {
    echo "Error al ejecutar la consulta de Seleccionar Materias: " . mysqli_error($conn);
  }
}

function agregarClases($conn, $id_usuario, $id_materia, $comision, $aula, $fecha, $hora, $temas, $novedad, $archivos)

{
  $consulta = "INSERT INTO CLASES(CODIGO_USUARIO, CODIGO_MATERIA, FECHA, HORA, TEMAS, NOVEDADES, COMISION, AULA, ARCHIVOS) VALUES ('$id_usuario','$id_materia','$fecha','$hora','$temas','$novedad','$comision','$aula', '$archivos')";
  $resultado = mysqli_query($conn, $consulta);
  if ($resultado) {
    return true;
  } else {
    return false;
  }
}

if (isset($_POST['btn_Añadir'])) {
  $id_materia = trim($_POST['materia']);
  $comision = trim($_POST['comision']);
  $aula = trim($_POST['aula']);
  $fecha = trim($_POST['fecha']);
  $hora = trim($_POST['hora']);
  $temas = trim($_POST['temas']);
  $novedad = trim($_POST['novedad']);
  $archivos = trim($_POST['archivo']);
  if (
    strlen($id_materia) > 0 &&
    strlen($comision) > 0 &&
    strlen($aula) > 0 &&
    strlen($fecha) > 0 &&
    strlen($hora) > 0  &&
    strlen($temas) >= 0  &&
    strlen($novedad) >= 0 &&
    strlen($archivos) >= 0
  ) {
    if (agregarClases($conn, $id_usuario, $id_materia, $comision, $aula, $fecha, $hora, $temas, $novedad, $archivos)) {
      
      ?>  
        <script src="https://cdn.tailwindcss.com"></script>



<!--
        
<div class="flex min-h-screen items-center justify-center carga">
      <div class="rounded-lg bg-gray-50 px-16 py-14">
      <div class="flex justify-center">
        <div class="rounded-full bg-green-200 p-6">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-500 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
          </div>
        </div>
      </div>
        <h3 class="my-4 text-center text-3xl font-semibold text-gray-700">Exito!!!</h3>
        <p class="w-[230px] text-center font-normal text-gray-600">La clase se agregó correctamente!</p>
      </div>
    </div> -->

    <div class="flex min-h-screen items-center justify-center bg-transparent ">
      <div class="rounded-lg bg-gray-50 px-16 py-14">
        <div class="flex justify-center">
          <div class="rounded-full bg-green-200 p-6 ">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-500 p-4">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
            </div>
          </div>
        </div>
        <h3 class="my-4 text-center text-3xl font-semibold text-gray-700">¡Éxito!</h3>
        <p class="w-[230px] text-center font-normal text-gray-600">¡La clase se agregó correctamente!</p>
      </div>
    </div>
      <script>  document.body.classList.add('no-scroll');   setTimeout('window.location="index.php";', 1000);   </script>
      <?php

    } else {
    ?>
      <script>  console.error("hay un error con el boton Alta: no se envian los datos mediante el servidor a la bd")  </script>
      <h3 class="bad">¡Ups ha ocurrido un error!</h3>
    <?php
    }
  } else {
    ?>
    <script>
      const $modal = document.querySelector(".modal");
      const bad = document.querySelector(".bad");
      const fondo_modal = document.querySelector(".modal__container");
      const inputs_modal = document.querySelectorAll(".inputs-modal");

      $modal.classList.add("modal--show");
      fondo_modal.style.borderColor = "red";
      for (let i = 0; i < inputs_modal.length; i++) {
        inputs_modal[i].style.borderColor = "red";
      }
      bad.style.display = "block";
      </script>
<?php
  }
}
?>
