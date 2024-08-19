
<?php
/* --- Seccion de funciones de index ---  */
function bienvenida($conn){
    $queryWelcome = "SELECT NOMBRE_PERSONA, APELLIDO_PERSONA, CARGO FROM PERSONAS WHERE CARGO = 'Profesor'";
    $res_queryWelcome = mysqli_query($conn, $queryWelcome);
    ?>
    <p class="msg-bienvenida">
        <?php
        if ($res_queryWelcome) {
            $fila_welcome = mysqli_fetch_assoc($res_queryWelcome);
        ?>
        <?php echo "¡Bienvenido " . $fila_welcome['NOMBRE_PERSONA'] . " " . $fila_welcome['APELLIDO_PERSONA']; ?>! 
        <span style="font-weight: bold; color:darkorange;">(<?php echo $fila_welcome['CARGO']; ?>)</span>
    </p> 
        <?php
        } 
        else {
            echo "Hubo un error al hacer la consulta de Bienvenida: " . mysqli_error($conn);
        }
}
?>

<?php
function mostrarDatos($result){
    if (isset($result) && $result->num_rows > 0) {
        $id_clase_chkbx = null;
        while ($fila = mysqli_fetch_array($result)) {
            $id_clase_chkbx = $fila['ID_CLASE'];
            //$_SESSION['CLASES_SELECCIONADAS'] = $id_clase_chkbx;  ?>

            <tr>
                <td class="columna-checkbox" ><input class="input-checkbox-register" type="checkbox" name="seleccionar_registro" value="<?php echo $id_clase_chkbx; ?>"></td>
                <td class="td_hidden"><?php echo $fila['CODIGO_MATERIA']; ?></td>
                <td><?php echo $fila['NOMBRE_MATERIA']; ?></td>
                <td><?php echo $fila['COMISION']; ?></td>
                <td><?php echo $fila['AULA']; ?></td>
                <td><?php echo $fila['HORA']; ?></td>
                <td><?php echo $fila['FECHA']; ?></td>
                <td><textarea class="td_textarea" rows="1" readonly><?php echo $fila['TEMAS']; ?></textarea></td>
                <td><textarea class="td_textarea" rows="1" readonly><?php echo $fila['NOVEDADES']; ?></textarea></td>
                <td><?php echo $fila['ARCHIVOS']; ?></td>
            </tr>   <?php
        }
    } 
    else {
    echo "<tr><td colspan='9' style='font-size:20px;'>No se encontró ninguna clase registrada...</td></tr>";
    }
}
?>


<?php
function buscarClases($conn){
    $queryBuscarClases = "SELECT materias.NOMBRE_MATERIA, clases.CODIGO_MATERIA
    FROM clases, usuarios, materias, usuxrol
    WHERE clases.CODIGO_USUARIO = usuarios.CODIGO_USUARIO
    AND clases.CODIGO_MATERIA = materias.CODIGO_MATERIA
    AND usuxrol.CODIGO_USUARIO = clases.CODIGO_USUARIO
    GROUP BY materias.NOMBRE_MATERIA";

    $res_queryBuscarClases = mysqli_query($conn, $queryBuscarClases);
    if ($res_queryBuscarClases->num_rows > 0){
        while ($fila = $res_queryBuscarClases->fetch_assoc()) {
            $claseID = $fila['CODIGO_MATERIA'];

            $queryClasesDelUsuario = "SELECT clases.ID_CLASE,clases.CODIGO_MATERIA,materias.NOMBRE_MATERIA,clases.COMISION, 
            clases.AULA, clases.HORA,clases.FECHA,clases.TEMAS, clases.NOVEDADES,clases.ARCHIVOS
            FROM clases, usuarios, materias, usuxrol
            WHERE clases.CODIGO_USUARIO = usuarios.CODIGO_USUARIO
            AND clases.CODIGO_MATERIA = materias.CODIGO_MATERIA
            AND clases.CODIGO_MATERIA = $claseID
            AND usuxrol.CODIGO_USUARIO = clases.CODIGO_USUARIO";
            $res_queryClasesDelUsuario  = mysqli_query($conn, $queryClasesDelUsuario);
            if ($res_queryClasesDelUsuario->num_rows > 1){  ?>
                <label class="contenedor-materia">
                    <input  class="checkLabel" type="checkbox" >
                    <div class="titulo-materia">
                        <b><?php echo $fila['NOMBRE_MATERIA'];  ?></b>
                    </div>
                    <div class="datos-materia">
                    <table>
                        <tbody>
                        <?php mostrarDatos($res_queryClasesDelUsuario); ?>
                        </tbody>
                    </table>
                    </div>
                </label>   <?php
            }
            else if ($res_queryClasesDelUsuario->num_rows == 1) {
                ?>
                <table>
                <tbody>
                <?php mostrarDatos($res_queryClasesDelUsuario); ?>
                </tbody>
            </table> <?php
            }
        }
    }    
    else{
        ?>
        <table>
            <tr><td colspan='9' style='font-size:20px;'>No se encontró ninguna clase registrada...</td></tr>
        </table>
        <?php
    }
}
?>


<?php
/* --- Seccion de funciones de Modal Alta ---  */

function obtenerMaterias($conn){
    $queryobtenerMaterias = "SELECT MATERIAS.NOMBRE_MATERIA, MATERIAS.CODIGO_MATERIA FROM materias";
    $res_queryobtenerMaterias = mysqli_query($conn, $queryobtenerMaterias);
    if ($res_queryobtenerMaterias) {
        while ($fila_materia = mysqli_fetch_assoc($res_queryobtenerMaterias)) {   ?>
            <option value="<?php echo $fila_materia['CODIGO_MATERIA']; ?>"><?php echo $fila_materia['NOMBRE_MATERIA']; ?></option>
            <?php
        }
    } 
    else {
    echo "Error al ejecutar la consulta de Seleccionar Materias: " . mysqli_error($conn);
    }
}
?>


<?php
function altaDeClase($conn, $id_usuario, $id_materia, $comision, $aula, $fecha, $hora, $temas, $novedad, $archivos){
    $queryAltaClase = "INSERT INTO CLASES(CODIGO_USUARIO, CODIGO_MATERIA, FECHA, HORA, TEMAS, NOVEDADES, COMISION, AULA, ARCHIVOS) VALUES ('$id_usuario','$id_materia','$fecha','$hora','$temas','$novedad','$comision','$aula', '$archivos')";
    $res_queryAltaClase = mysqli_query($conn, $queryAltaClase);
    if ($res_queryAltaClase) {    
        header("Content-Type: text/html; charset=UTF-8");
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Redirigiendo...</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
            <link rel="stylesheet" href="../../css/style.css">
            <link rel="stylesheet" href="../../css/modal.css">
            <link rel="icon" href="assets/img/icono.png">

        </head>
        <body >
            <div class="notificacion-container">
                <div class="notificacion-message-box">
                    <div class="notificacion-icon-container container-icon-alta">
                        <div class="notificacion-icon icon-alta">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="notificacion-svg-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="notificacion-heading">¡Éxito!</h3>
                    <p class="notificacion-text">¡La clase se agregó correctamente!</p>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    window.location.href = "../../../index.php";
                }, 1000);
            </script>
        </body>
        </html>';
    } 
    else { 
        // http_response_code(500); // Error interno del servidor
        // echo json_encode(array('message' => 'Error al dar alta de clase: ' . $conn->error));  
        //<script>  console.error("hay un error con el boton Alta: no se envian los datos mediante el servidor a la bd")  </script>
        header("Content-Type: text/html; charset=UTF-8"); 
        echo '<h3 class="bad">¡Ups ha ocurrido un error!</h3>'   ;
    }
}
?>


<?php
function modificacionDeClase($conn, $id_usuario, $id_materia, $comision, $aula, $fecha, $hora, $temas, $novedad, $archivos){
    if (isset($_SESSION['CLASES_SELECCIONADAS'])) {
        $clase_seleccionada = (array)$_SESSION['CLASES_SELECCIONADAS'];
        $clase_seleccionada = implode("", $clase_seleccionada);

    $queryModificacionClase = "UPDATE CLASES SET CODIGO_MATERIA = '$id_materia', FECHA ='$fecha', HORA = '$hora', TEMAS = '$temas', NOVEDADES = '$novedad', COMISION = '$comision', 
    AULA = '$aula', ARCHIVOS = '$archivos' 
    WHERE CODIGO_USUARIO = '$id_usuario' AND ID_CLASE= '$clase_seleccionada'";
    $res_queryModificacionClase = mysqli_query($conn, $queryModificacionClase);
    
    if ($res_queryModificacionClase) {    
        header("Content-Type: text/html; charset=UTF-8");
        echo ' 
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Redirigiendo...</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
            <link rel="stylesheet" href="../../css/style.css">
            <link rel="stylesheet" href="../../css/modal.css">
            <link rel="icon" href="assets/img/icono.png">

        </head>
        <body >
           <div class="notificacion-container">
                <div class="notificacion-message-box">
               <div class="notificacion-icon-container container-icon-alta">
                        <div class="notificacion-icon icon-alta">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="notificacion-svg-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="notificacion-heading">¡Éxito!</h3>
                    <p class="notificacion-text">¡La clase se modifico correctamente!</p>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    window.location.href = "../../../index.php";
                }, 1000);
            </script>
        </body>
        </html>';
    } 
    else { 
        // http_response_code(500); // Error interno del servidor
        // echo json_encode(array('message' => 'Error al dar alta de clase: ' . $conn->error));  
        //<script>  console.error("hay un error con el boton Alta: no se envian los datos mediante el servidor a la bd")  </script>
        header("Content-Type: text/html; charset=UTF-8"); 
        echo '<h3 class="bad">¡Ups ha ocurrido un error!</h3>';
    }
}
}
?>


