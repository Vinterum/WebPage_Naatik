<?php

	require 'database.php';

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if ( null==$id ) {
		header("Location: Usuarios.php");
	}

	if ( !empty($_POST)) {
		// keep track validation errors
		$emailError = null;
		$nomError = null;
        $telError = null;

		// keep track post values
		$email = $_POST['email'];
		$nom = $_POST['nom'];
		$tel = $_POST['tel'];

		/// validate input
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

		// update data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE Usuario set Email = ?, Nombre = ?, Telefono =? WHERE Email = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($email,$nom,$tel,$id));
			Database::disconnect();
			header("Location: Usuarios.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM Usuario where Email = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$email = $data['Email'];
		$nom = $data['Nombre'];
		$tel = $data['Telefono'];
		Database::disconnect();
	}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta 	charset="utf-8">
	    <link   href=	"css/bootstrap.min.css" rel="stylesheet">
	    <script src=	"js/bootstrap.min.js"></script>
	</head>

	<body>
    	<div class="container">
    		<div class="span10 offset1">
    			<div class="row">
		    		<h3>Actualizar datos de un usuario</h3>
		    	</div>

	    			<form class="form-horizontal" action="update_usuario.php?id=<?php echo $id?>" method="post">

					  <div class="control-group <?php echo !empty($emailError)?'error':'';?>">

					    <label class="control-label">email</label>
					    <div class="controls">
					      	<input name="email" type="text" placeholder="email" value="<?php echo !empty($id)?$id:''; ?>">
					      	<?php if (!empty($emailError)): ?>
					      		<span class="help-inline"><?php echo $emailError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>

					  <div class="control-group <?php echo !empty($nomError)?'error':'';?>">

					    <label class="control-label">nombre</label>
					    <div class="controls">
					      	<input name="nom" type="text" placeholder="nombre" value="<?php echo !empty($nom)?$nom:'';?>">
					      	<?php if (!empty($nomError)): ?>
					      		<span class="help-inline"><?php echo $nomError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>

					  <div class="control-group <?php echo !empty($telError)?'error':'';?>">

					    <label class="control-label">telefono</label>
					    <div class="controls">
					      	<input name="tel" type="text" placeholder="telefono" value="<?php echo !empty($tel)?$tel:'';?>">
					      	<?php if (!empty($telError)): ?>
					      		<span class="help-inline"><?php echo $telError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>

					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Actualizar</button>
						  <a class="btn" href="Usuarios.php">Regresar</a>
						</div>
					</form>
				</div>

    </div> <!-- /container -->
  </body>
</html>
