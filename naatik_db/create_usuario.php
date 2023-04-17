<?php

require 'database.php';

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$emailError = null;
$nomError = null;
$telError = null;

if (!empty($_POST)) {

	// keep track post values
	$email = $_POST['email'];
	$nom = $_POST['nom'];
	$tel = $_POST['tel'];

	// validate input
	$valid = true;

	if (empty($email)) {
		$emailError = 'Porfavor escribe tu email';
		$valid = false;
	} else {
		$emailTest = test_input($email);
		// check if e-mail address is well-formed
		if (!filter_var($emailTest, FILTER_VALIDATE_EMAIL)) {
		  $emailError = "Formato de email invalido";
		  $valid = false;
		}
	}
	if (empty($nom)) {
		$nomError = 'Porfavor escribe tu nombre completo';
		$valid = false;
	} else {
		$nomTest = test_input($nom);
		// check if name only contains letters and whitespace
		if (!preg_match("/^[a-zA-Z-' ]*$/", $nomTest, $match)) {
		  $nomError = "Solo letras y espacios en blanco permitidos";
		  $valid = false;
		}
	}
	if (empty($tel)) {
		$telError = 'Porfavor escribe tu telefono';
		$valid = false;
	} else {
		if (!preg_match('/^[0-9]{10}+$/', $tel, $match)) {
			$telError = "Escriba un numero de telefono con 10 digitos";
			$valid = false;
		}
	}

	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO Usuario (Email,Nombre,Telefono) values(?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($email, $nom, $tel));
		Database::disconnect();
		header("Location: Usuarios.php");
	}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container">
		<div class="span10 offset1">
			<div class="row">
				<h3>Agregar nuevo usuario</h3>
			</div>

			<form class="form-horizontal" action="create_usuario.php" method="post">

				<div class="control-group <?php echo !empty($emailError) ? 'error' : ''; ?>">
					<label class="control-label">email</label>
					<div class="controls">
						<input name="email" type="text" placeholder="email" value="<?php echo !empty($email) ? $email : ''; ?>">
						<?php if (($emailError != null)) ?>
						<span class="help-inline"><?php echo $emailError; ?></span>
					</div>
				</div>

				<div class="control-group <?php echo !empty($nomError) ? 'error' : ''; ?>">
					<label class="control-label">nombre completo</label>
					<div class="controls">
						<input name="nom" type="text" placeholder="nombre" value="<?php echo !empty($nom) ? $nom : ''; ?>">
						<?php if (($nomError != null)) ?>
						<span class="help-inline"><?php echo $nomError; ?></span>
					</div>
				</div>

				<div class="control-group <?php echo !empty($semError) ? 'error' : ''; ?>">
					<label class="control-label">telefono</label>
					<div class="controls">
						<input name="tel" type="text" placeholder="telefono" value="<?php echo !empty($tel) ? $tel : ''; ?>">
						<?php if (($telError != null)) ?>
						<span class="help-inline"><?php echo $telError; ?></span>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Agregar</button>
					<a class="btn" href="Usuarios.php">Regresar</a>
				</div>

			</form>
		</div>
	</div> <!-- /container -->
</body>

</html>