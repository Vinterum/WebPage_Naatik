<?php
	require 'database.php';
	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if ( !empty($_POST)) {
		// keep track post values
		$id = $_POST['id'];
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql1 = "DELETE FROM Itinerario WHERE Id = ?";
		$q1 = $pdo->prepare($sql1);
		$q1->execute(array($id));
		$sql2 = "DELETE FROM Cita WHERE Id = ?";
		$q2 = $pdo->prepare($sql2);
		$q2->execute(array($id));
		Database::disconnect();
		header("Location: Citas.php");
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
			    	<h3>Eliminar una cita</h3>
			    </div>

			    <form class="form-horizontal" action="delete_cita.php" method="post">
		    		<input type="hidden" name="id" value="<?php echo $id;?>"/>
					<p class="alert alert-error">Estas seguro que quieres eliminar este cita?</p>
					<div class="form-actions">
						<button type="submit" class="btn btn-danger">Si</button>
						<a class="btn" href="Citas.php">No</a>
					</div>
				</form>
			</div>
	    </div> <!-- /container -->
	</body>
</html>
