<?php
	require 'database.php';
	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	if ( $id==null) {
		header("Location: index_naatik.php");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = 'SELECT c.Id AS c_id, e.Nombre AS Encargado, e.Email AS Email_encargado, e.Telefono AS Tel_encargado, 
		u.Nombre AS Usuario, u.Email AS Email_usuario, u.Telefono AS Tel_usuario, i.Empieza, i.Termina, s.Estado, a.Asunto  
			FROM Cita c
			INNER JOIN Encargado e ON Encargado_id = e.Email
			INNER JOIN Usuario u ON Usuario_id = u.Email
			INNER JOIN Itinerario i ON Itinerario_id = i.Id
			INNER JOIN Estado s ON i.Estado_id = s.Id
			INNER JOIN Asunto a ON i.Asunto_id = a.Id';
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
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
		    		<h3>Detalles de un auto</h3>
		    	</div>

	    		<div class="form-horizontal" >

					<div class="control-group">
						<label class="control-label">id</label>
					    <div class="controls">
							<label class="checkbox">
								<?php echo $data['c_id'];?>
							</label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Nombre del encargado</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Encargado'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Email del encargado</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Email_encargado'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Telefono del encargado</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Tel_encargado'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Nombre del usuario</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Usuario'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Email del usuario</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Email_usuario'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Telefono del usuario</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Tel_usuario'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Asunto de la cita</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Asunto'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Cita empieza</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Empieza'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Cita termina</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Termina'];?>
						    </label>
					    </div>
					</div>

					<div class="control-group">
					    <label class="control-label">Estado de la cita</label>
					    <div class="controls">
					      	<label class="checkbox">
						     	<?php echo $data['Estado'];?>
						    </label>
					    </div>
					</div>

				    <div class="form-actions">
						<a class="btn" href="index_naatik.php">Regresar</a>
					</div>

				</div>
			</div>
		</div> <!-- /container -->
  	</body>
</html>
