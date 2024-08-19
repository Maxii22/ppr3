<?php
include("assets/php/conexion.php");
include("assets/php/funciones.php");
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
  <header id="header">
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

  <?php 
  if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 

  if ( $message['type'] == 'error') {
  ?>
  <script>
  const $modal = document.querySelector(".modal"); 
  const bad = document.querySelector(".bad");
  $modal.classList.add("modal--show");
  bad.style.display = "block";
  
  </script>
  <?php
  }
 
}
?>

  <main>

    <div class="contenedor-bienvenida-msg">
      <?php  bienvenida($conn) ?>
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







