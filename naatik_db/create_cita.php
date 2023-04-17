<?php

require 'database.php';

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$encError = null;
$usuarioError = null;
$empError = null;
$termError = null;
$asunError = null;

if (!empty($_POST)) {

	// keep track post values
	$enc = $_POST['enc'];
	$usuario = $_POST['usuario'];
	$emp = $_POST['emp'];
	$term = $_POST['term'];
	$asun = $_POST['asun'];

	// validate input
	$valid = true;

	if (empty($enc)) {
		$encError = 'Porfavor selecciona el responsable de la cita';
		$valid = false;
	}
	if (empty($usuario)) {
		$usuarioError = 'Porfavor selecciona el usuario para la cita';
		$valid = false;
	} 
	if (empty($emp)) {
		$empError = 'Porfavor escriba fecha y hora de la cita';
		$valid = false;
	} else {
		if (!preg_match("/^[1-9]\d{3}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])\s([0-1][0-9]|2[0-3])(:([0-5][0-9]))$/", $emp, $match)) {
		  $empError = "Formato o fecha invalida";
		  $valid = false;
		}
	}
	if (empty($term)) {
		$termError = 'Porfavor escriba fecha y hora de termino';
		$valid = false;
	} else {
		if (!preg_match("/^[1-9]\d{3}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])\s([0-1][0-9]|2[0-3])(:([0-5][0-9]))$/", $term, $match)) {
		  $termError = "Formato o fecha invalida";
		  $valid = false;
		}
	}
	if (empty($asun)) {
		$asunError = 'Porfavor selecciona el asunto para la cita';
		$valid = false;
	} 

	if ($valid) {
	try{
		$input_emp = strtotime($emp);
		$emp = date("Y-m-d H:i:00", $input_emp);
		$input_term = strtotime($term);
		$term = date("Y-m-d H:i:00", $input_term);
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//We start our transaction.
		$pdo->beginTransaction();
		$sql1 ="INSERT INTO Itinerario (Id,Empieza,Termina,Creada,Estado_id,Asunto_id) values(null, ?, ?, null, ?, ?)";
		$q1 = $pdo->prepare($sql1);
		$q1->execute(array($emp, $term, 2, $asun));
		$it = $pdo->lastInsertID();
		$sql2 = "INSERT INTO Cita (Id,Encargado_id,Usuario_id,Itinerario_id) values(null, ?, ?, ?)";
		$q2 = $pdo->prepare($sql2);
		$q2->execute(array($enc, $usuario, $it));
		//We've got this far without an exception, so commit the changes.
		$pdo->commit();
		Database::disconnect();
		header("Location: Citas.php");
	}
	//Our catch block will handle any exceptions that are thrown.
	catch(Exception $e){
		//An exception has occured, which means that one of our database queries
		//failed.
		//Print out the error message.
		echo $e->getMessage();
		//Rollback the transaction.
		$pdo->rollBack();
	}
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
				<h3>Agregar nuevo cita</h3>
			</div>

			<form class="form-horizontal" action="create_cita.php" method="post">

				<div class="control-group <?php echo !empty($encError)?'error':'';?>">
				    	<label class="control-label">encargado</label>
				    	<div class="controls">
	                       	<select name ="enc">
		                        <option value="">Selecciona una encargado</option>
		                        <?php
							   		$pdo = Database::connect();
							   		$query = 'SELECT * FROM Encargado';
			 				   		foreach ($pdo->query($query) as $row) {
		                        		if ($row['Email']==$enc)
		                        			echo "<option selected value='" . $row['Email'] . "'>" . $row['Email'] . "</option>";
		                        		else
		                        			echo "<option value='" . $row['Email'] . "'>" . $row['Email'] . "</option>";
			   						}
			   						Database::disconnect();
			  					?>
                            </select>
					      	<?php if (($encError) != null) ?>
					      		<span class="help-inline"><?php echo $encError;?></span>
						</div>
				</div>

				<div class="control-group <?php echo !empty($usuarioError)?'error':'';?>">
				    	<label class="control-label">usuario</label>
				    	<div class="controls">
	                       	<select name ="usuario">
		                        <option value="">Selecciona una usuario</option>
		                        <?php
							   		$pdo = Database::connect();
							   		$query = 'SELECT * FROM Usuario';
			 				   		foreach ($pdo->query($query) as $row) {
		                        		if ($row['Email']==$usuario)
		                        			echo "<option selected value='" . $row['Email'] . "'>" . $row['Email'] . "</option>";
		                        		else
		                        			echo "<option value='" . $row['Email'] . "'>" . $row['Email'] . "</option>";
			   						}
			   						Database::disconnect();
			  					?>
                            </select>
					      	<?php if (($usuarioError) != null) ?>
					      		<span class="help-inline"><?php echo $usuarioError;?></span>
						</div>
				</div>

				<div class="control-group <?php echo !empty($asunError)?'error':'';?>">
				    	<label class="control-label">asunto</label>
				    	<div class="controls">
	                       	<select name ="asun">
		                        <option value="">Selecciona el asunto</option>
		                        <?php
							   		$pdo = Database::connect();
							   		$query = 'SELECT * FROM Asunto';
			 				   		foreach ($pdo->query($query) as $row) {
		                        		if ($row['Id']==$asun)
		                        			echo "<option selected value='" . $row['Id'] . "'>" . $row['Asunto'] . "</option>";
		                        		else
		                        			echo "<option value='" . $row['Id'] . "'>" . $row['Asunto'] . "</option>";
			   						}
			   						Database::disconnect();
			  					?>
                            </select>
					      	<?php if (($asunError) != null) ?>
					      		<span class="help-inline"><?php echo $asunError;?></span>
						</div>
				</div>

				<div class="control-group <?php echo !empty($empError) ? 'error' : ''; ?>">
					<label class="control-label">fecha y hora que empieza</label>
					<div class="controls">
						<input name="emp" type="text" placeholder="YYYY-MM-DD hh:mm" value="<?php echo !empty($emp) ? $emp : ''; ?>">
						<?php if (($empError != null)) ?>
						<span class="help-inline"><?php echo $empError; ?></span>
					</div>
				</div>

				<div class="control-group <?php echo !empty($termError) ? 'error' : ''; ?>">
					<label class="control-label">fecha y hora que termina</label>
					<div class="controls">
						<input name="term" type="text" placeholder="YYYY-MM-DD hh:mm" value="<?php echo !empty($term) ? $term : ''; ?>">
						<?php if (($termError != null)) ?>
						<span class="help-inline"><?php echo $termError; ?></span>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Agregar</button>
					<a class="btn" href="Citas.php">Regresar</a>
				</div>

			</form>
		</div>
	</div> <!-- /container -->
</body>

</html>