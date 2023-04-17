<?php

require 'database.php';

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

#$remitente = null ;
#$destinatario = null;
#$asunto =  null;
#$mensaje =  null;
#$telefono = null ;
#$nombre = null;
$body = null;
$telefonoError = null;
$nombreError = null;
$remitenteError = null;
$destinatarioError = null;
$asuntoError = null;
$mensajeError  = null;


#$dbMsgError = null;


if ( !empty($_POST)) {

// keep track post values

  // post values to send email
  $remitente = $_POST['correo'];
  $destinatario = "pruebas01273@gmail.com";
  $asunto =  $_POST['asunto'];
  $mensaje =  $_POST['msj'];
  $headers = "From: $remitente\r\nReply-to: $remitente";
  $nombre = $_POST['nombre'];
  $telefono = $_POST['telefono'];

  $email_content = "Nombre: $nombre\n";
  $email_content .= "Email: $remitente\n";
  $email_content .= "Telefono:$telefono\n";
  $email_content .= "Mensaje:$mensaje  \n";

  #$dbMsg = $_POST['dbmsg'];

// validate input
  $valid = true;
  // validate for email and database
  if (empty($telefono)) {
		$telefonoError = 'Porfavor escribe tu telefono';
		$valid = false;
	} else {
		if (!preg_match('/^[0-9]{10}+$/', $telefono, $match)) {
			$telefonoError = "Escriba un numero de telefono con 10 digitos";
			$valid = false;
		}
	}
  if (empty($nombre)) {
		$nombreError = 'Porfavor escribe tu nombre completo';
		$valid = false;
	} else {
		$nombreTest = test_input($nombre);
		// check if name only contains letters and whitespace
		if (!preg_match("/^[a-zA-Z-' ]*$/", $nombreTest, $match)) {
		  $nombreError = "Solo letras sin acento y espacios en blanco permitidos";
		  $valid = false;
		}
	}
  if (empty($remitente)) {
		$remitenteError = 'Porfavor escribe tu email';
		$valid = false;
	} else {
		$remitenteTest = test_input($remitente);
		// check if e-mail address is well-formed
		if (!filter_var($remitenteTest, FILTER_VALIDATE_EMAIL)) {
		  $remitenteError = "Formato de email invalido";
		  $valid = false;
		}
	}
  if (empty($destinatario)) {
  $destinatarioError = 'Porfavor ingrese un correo valido';
  $valid = false;
    }
  if (empty($asunto)) {
  $asuntoError = 'Porfavor seleccione el asunto';
  $valid = false;
    } 
  if (empty($mensaje)) {
  $mensajeError = 'Porfavor ingrese un mensaje';
  $valid = false;
    }
  #if (empty($dbMsg)) {
	#	$dbMsgError = 'Porfavor escribe tu email';
	#	$valid = false;
	#} else {
	#	$emailTest = test_input($dbEmail);
	#	// check if e-mail address is well-formed
	#	if (!preg_match("/^[.]*$/", $nameTest, $match)){
	#	  $dbMsgError = "Formato de mensaje inválido";
	#	  $valid = false;
	#	}
	#}

  // insert data
  if ($valid) { 
      // send email
      mail($destinatario, $asunto, $email_content, $headers);
      header("Location: Contacto.php");

      // send input to database
      $pdo = Database::connect();
		  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $sql = "REPLACE INTO Usuario (Email,Nombre,Telefono) values(?, ?, ?)";
		  $q = $pdo->prepare($sql);
		  $q->execute(array($remitente, $nombre, $telefono));
		  Database::disconnect();
		  header("Location: Contacto.php");
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Contacto</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../fontawesome/css/all.css">
  <link rel="stylesheet" href="../CSS/Contacto.css" type = "text/css">
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="../JS/Contacto.js" defer></script>
  
</head>

<body>

<!-- Navigation Bar -->
<div class="navbar" id="navbar">
  <a class="navbar-logo" href="index.html"><img src="../Images/logo-naatik-head.png" width="52"></a>
  <a href="Nosotros.html">Nosotros</a>
  <a href="Servicio.html">Servicio</a>
  <a href="Contacto.php">Contacto</a>
  <a class="push right" href="#">En|Es</a>
  <a class="right" href="https://www.youtube.com"><i class="fa-brands fa-youtube"></i></a>
  <a class="right" href="https://twitter.com"><i class="fa-brands fa-twitter"></i></a>
  <a class="right corner" href="https://www.facebook.com"><i class="fa-brands fa-facebook-f"></i></a>
</div>

<!-- Header -->
<div class="header">
  <div class="titulo-header">Contacto</div>
</div>

<!-- Sub-Header -->
<div class="sub-header scroll-sub">
  <div class="sub-header-row">
    <p>Ya sea si requiere mayores informes, busca contratar nuestros servicios
    <br>o está interesado en nuestras estancias para tésis o servicio social.</br>
    <br><strong>¡Contáctenos, nos ponemos a sus ordenes!</strong></br></p>
  </div>
</div>

<!-- Mensaje y datos de contacto -->
<div class="row scroll-contacto">
  <div class ="mensaje">
  <form action = "Contacto.php" method = "post">
    <div>
     <label for="Asunto">Elija un asunto:</label>
     <select  name="asunto" id="asunto">
      <option value="estancias">Estancias de tésis o servicio social</option>
      <option value="contratación">Contratación de servicios</option>
      <option value="proyectos">Proyectos de investigación/colaboración</option>
     </select>
    </div>

  
      <div class="telefono" <?php echo !empty($telefonoError) ? 'error' : ''; ?>">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" maxlength="10" size="15">
        <?php if (($telefonoError != null)) ?>
				<span class="error"><?php echo $telefonoError; ?></span>
      </div>
      <br class="salto"/>

      <div class="name">
        <label for="nombre"><br>Nombre completo*:</label>
        <input type="text" id="nombre" name="nombre" maxlength="50" size="39" required>
        <?php if (($nombreError != null)) ?>
				<span class="error"><?php echo $nombreError; ?></span><br>
      </div>
      <br class="salto"/>

      <div class="email">
        <label for="correo">Email*:</label>
        <input type="text" id="correo" name="correo" maxlength="100" size="39" required>
        <?php if (($remitenteError != null)) ?>
				<span class="error"><?php echo $remitenteError; ?></span><br>
      </div>
      <br class="salto"/>

      <p class="msjfield">
        <label for="msj">Mensaje*:</label>
        <textarea id="msj" name="msj" rows="4" cols="60" maxlength="300" required></textarea><br>
      </p>
      <br class="salto"/>
      <input type="submit" value="Submit">
    </form>
  </div>

  <div class="mensaje-extra">
    <h3>¡Una vez recibamos el mensaje<br>le devolveremos un correo<br>para agendar una cita!</h3>
    <img class="customer-service" src="../Images/customer-service1.png">
  </div>

  <div class="datos">
    <!--Sección datos de contacto-->
    <h3>Datos de Contacto</h3>
      <p><i class="fa-solid fa-phone"></i><strong>Teléfono:</strong> (55) 19-522-602 <br></p>
      <p><i class="fa-solid fa-at"></i><strong>Email:</strong> contacto@naatik.ai</p>
    <h3>Horario</h3>
      <p><i class="fa-solid fa-business-time"></i><strong>LUN - VIER:</strong> 10:00 - 18:00 HRS <br></p>
      <p><i class="fa-solid fa-calendar-xmark"></i><strong>SAB - DOM:</strong> CERRADO</p>
  </div>
</div>

<!-- Titulo estancias -->
<div class="estancias scroll-estancias">
  <h1>Aprende sobre nuestras<br>estancias para tesis o servicio social</h1>
</div>

<!-- Info de estancias -->
<div class="row scroll-estancias">
  <div class="contenedor-estancias">
    <div class="info-estancias">
      Se solicitan estudiantes pasantes de la carrera de Ingeniería en Computación,<br>
      Ciencia de la computación , sistemas informáticos o afín o de cualquier otra<br>
      carrera, que cumplan con los requisitos solicitados y deseen desarrollar su<br>
      tesis de licenciatura en la empresa Naatik A.I. Solutions,<br>
      (www.naatik.ai, RFC: NAS200723T10, Registro RENIECyT-Conacyt 2000846),<br>
      con desarrollo y seguimiento de actividades de manera remota.
    </div>
    <div class="img-estancias">
      <img class="img-ai" src="../Images/hand-ai.jpg">
    </div>
  </div>
  <div>
  <hr class="gradient-hr">
  </div>
  <div class="accordion">
    <div class="accordion-item">
      <div class="accordion-item-header">
        Requisitos
      </div>
      <div class="accordion-item-body">
        <div class="accordion-item-body-content">
          <ul>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Ubicarse en el 20% de los mejores promedios de su generación.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Interés en aplicaciones de Inteligencia Artificial, particularmente aprendizaje automático y ciencia de datos.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Familiaridad con aplicaciones generales de minería y ciencia de datos.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Experiencia en programación con librerías y paquetes de Python, R, Java y Matlab, C/C++.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Conocimientos en Base de datos (SQL ,MySQL, Postgres, etc).
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Conocimientos en desarrollo de aplicaciones con tecnologías WEB (Java Script, CSS y HTML. NodeJS, Json / API RESTfull, Rest Web Services, Apache)
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Ingles
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Pasión por el análisis y descubrimiento de patrones, creativo, proactivo, innovación en desarrollo y espíritu de servicio.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Disponibilidad de medio tiempo o tiempo completo.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Sexo indistinto, edad máxima 25 años.
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <div class="accordion-item-header">
        Beneficios
      </div>
      <div class="accordion-item-body">
        <div class="accordion-item-body-content">
          <ul>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Asesoría por parte de personal experto a nivel nacional e internacional en el área de sistemas inteligentes.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Acceso a programas e información técnica especializada.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Desarrollo y seguimiento de actividades de manera totalmente remota (8 horas diarias).
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Participación en proyectos relacionados con inteligencia artificial.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Excelente ambiente de trabajo.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Crecimiento profesional.
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <div class="accordion-item-header">
        Actividades generales a realizar
      </div>
      <div class="accordion-item-body">
        <div class="accordion-item-body-content">
          <ul>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Análisis, diseño e implementación de soluciones de software.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Visualización y análisis de datos.
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <div class="accordion-item-header">
        Documentación
      </div>
      <div class="accordion-item-body">
        <div class="accordion-item-body-content">
          <ul>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Copia de historial académico.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Currículum vitae actualizado.
            </li>
            <li>
              <i class="fa-fw fa-solid fa-circle-check"></i>Copia de CURP e IFE.
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<div class="scroll-footer">
<footer class="footer">
  <div class="footer-container">
    <div class="block-logo">
      <a class="footer-logo" href="index.html"><img class="naatik" src="../Images/NAATIK_LOGO.svg" alt="Naatik"></a>
    </div>
    <div class="mapa-sitio">
      <ul class="mapa">
        <li class="menu-item">
          <a href="Nosotros.html">Nosotros</a>
        </li>
        <li class="menu-item">
          <a href="Servicio.html">Servicio</a>
        </li>
        <li class="menu-item">
          <a href="Contacto.php">Contacto</a>
        </li>
      </ul>
    </div>
    <nav class="redes-sociales">
      <h2>Redes Sociales</h2>
      <div class="menu-social">
        <ul class="inline-menu">
        <li class="footer-link">
            <a class="footer-icon" href="https://www.youtube.com/channel/UCgL1wxyGeuTBaRwBMB3lT6A"><i class="fa-brands fa-youtube"></i></a>
          </li>
          <li class="footer-link">
            <a class="footer-icon" href="https://twitter.com/NaatikAI"><i class="fa-brands fa-twitter"></i></a>
          </li>
          <li class="footer-link">
            <a class="footer-icon corner" href="https://www.facebook.com/NaatikAI"><i class="fa-brands fa-facebook-f"></i></a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="block-chat">
      <a><img class="chat-bot-logo" src="../Images/Chat-bot.png" width="200"></a>
    </div>
  </div>
</footer>

<!-- Bottom-Footer -->
<div class="bottom-footer">
  <div class="copyright-block">
    <div class="copyright-text">
      <p>Naatik A.I. Solutions© Copyright 2021. Todos los Derechos Reservados.</p>
    </div>
  </div>
  <nav class="menu-politicas">
    <ul class="inline-menu">
      <li class="inline-menu-item">
        <a href="Privacidad.html">Aviso de Privacidad</a>
      </li>
      <li class="inline-menu-item">
        <a href="Preguntas.html">Preguntas Frecuentes</a>
      </li>
    </ul>
  </nav>
</div>
</div>
</body>
</html>
