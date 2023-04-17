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
		header("Location: Citas.php");
	}

	if (!empty($_POST)) {
		// keep track validation errors
		$f_idError = null;
		$encError = null;
		$usuarioError = null;
		$itError = null;
		$empError = null;
		$termError = null;
		$estError = null;
		$asunError = null;

		// keep track post values
		$f_id = $_POST['f_id'];
		$enc = $_POST['enc'];
		$usuario = $_POST['usuario'];
		$it = $_POST['it'];
		$emp = $_POST['emp'];
		$term = $_POST['term'];
		$est = $_POST['est'];
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
		if (empty($est)) {
			$estError = 'Porfavor selecciona el estado de la cita';
			$valid = false;
		}
		if (empty($asun)) {
			$asunError = 'Porfavor selecciona el asunto para la cita';
			$valid = false;
		}

		// update data
		if ($valid) {
			$input_emp = strtotime($emp);
			$emp = date("Y-m-d H:i:00", $input_emp);
			$input_term = strtotime($term);
			$term = date("Y-m-d H:i:00", $input_term);
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql1 = "UPDATE Itinerario set Id = ?, Empieza = ?, Termina = ?, Creada = null, Estado_id = ?, Asunto_id = ? WHERE Id = ?";
			$q1 = $pdo->prepare($sql1);
			$q1->execute(array($it, $emp, $term, $est, $asun, $it));
			$sql2 = "UPDATE Cita set Id = ?, Encargado_id = ?, Usuario_id = ?, Itinerario_id =? WHERE Id = ?";
			$q2 = $pdo->prepare($sql2);
			$q2->execute(array($f_id, $enc, $usuario, $it, $id));
			Database::disconnect();
			header("Location: Citas.php");
		}
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql2 = "SELECT * FROM Cita where Id = ?";
		$q2 = $pdo->prepare($sql2);
		$q2->execute(array($id));
		$data = $q2->fetch(PDO::FETCH_ASSOC);
		$f_id = $data['Id'];
		$enc = $data['Encargado_id'];
		$usuario = $data['Usuario_id'];
		$it = $data['Itinerario_id'];
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
		    		<h3>Actualizar datos de una cita</h3>
		    	</div>

	    			<form class="form-horizontal" action="update_cita.php?id=<?php echo $id?>" method="post">

					<div class="control-group <?php echo !empty($f_idError)?'error':'';?>">
					  	<label class="control-label">id cita</label>
					  	<div class="controls">
					  	  	<input name="f_id" type="text" readonly placeholder="id" value="<?php echo !empty($id)?$id:''; ?>">
					  	  	<?php if (!empty($f_idError)): ?>
					  	  		<span class="help-inline"><?php echo $f_idError;?></span>
					  	  	<?php endif; ?>
					  	</div>
					</div>

					<div class="control-group <?php echo !empty($itError)?'error':'';?>">
					  	<label class="control-label">id itinerario</label>
					  	<div class="controls">
					  	  	<input name="it" type="text" readonly placeholder="it" value="<?php echo !empty($it)?$it:''; ?>">
					  	  	<?php if (!empty($itError)): ?>
					  	  		<span class="help-inline"><?php echo $itError;?></span>
					  	  	<?php endif; ?>
					  	</div>
					</div>

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
					  	<?php if (!empty($encError)): ?>
					  	  	<span class="help-inline"><?php echo $encError;?></span>
						<?php endif;?>
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
							<?php if (!empty($usuarioError)): ?>
					  	  		<span class="help-inline"><?php echo $usuarioError;?></span>
							<?php endif;?>
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
		              	        		if ($row['Asunto_id']==$asun)
		              	        			echo "<option selected value='" . $row['Id'] . "'>" . $row['Asunto'] . "</option>";
		              	        		else
		              	        			echo "<option value='" . $row['Id'] . "'>" . $row['Asunto'] . "</option>";
			   						}
			   						Database::disconnect();
			  					?>
                      	    </select>
							<?php if (!empty($asunError)): ?>
					  	  		<span class="help-inline"><?php echo $asunError;?></span>
							<?php endif;?>
						</div>
					</div>

					<div class="control-group <?php echo !empty($empError) ? 'error' : ''; ?>">
						<label class="control-label">fecha y hora que empieza</label>
							<div class="controls">
							<input name="emp" type="text" placeholder="emp" value="<?php echo !empty($emp) ? $emp : ''; ?>">
							<?php if (!empty($empError)): ?>
					  	  		<span class="help-inline"><?php echo $empError;?></span>
							<?php endif;?>
						</div>
					 </div>

					<div class="control-group <?php echo !empty($termError) ? 'error' : ''; ?>">
						<label class="control-label">fecha y hora que termina</label>
						<div class="controls">
							<input name="term" type="text" placeholder="term" value="<?php echo !empty($term) ? $term : ''; ?>">
							<?php if (!empty($termError)): ?>
					  	  		<span class="help-inline"><?php echo $termError;?></span>
							<?php endif;?>
						</div>
					</div>
					


					<div class="control-group <?php echo !empty($estError)?'error':'';?>">
						<label class="control-label">estado</label>
						<div class="controls">
	                  	   	<select name ="est">
		              	        <option value="">Selecciona el estado</option>
		              	        <?php
							   		$pdo = Database::connect();
							   		$query = 'SELECT * FROM Estado';
			 				   		foreach ($pdo->query($query) as $row) {
		              	        		if ($row['Estado_id']==$est)
		              	        			echo "<option selected value='" . $row['Id'] . "'>" . $row['Estado'] . "</option>";
		              	        		else
		              	        			echo "<option value='" . $row['Id'] . "'>" . $row['Estado'] . "</option>";
			   						}
			   						Database::disconnect();
			  					?>
                      	    </select>
							<?php if (!empty($estError)): ?>
					  	  		<span class="help-inline"><?php echo $estError;?></span>
							<?php endif;?>
						</div>
					</div>

					<div class="form-actions">
						<button type="submit" class="btn btn-success">Actualizar</button>
						<a class="btn" href="Citas.php">Regresar</a>
					</div>
				</form>
			</div>

    </div> <!-- /container -->
  </body>
</html>
