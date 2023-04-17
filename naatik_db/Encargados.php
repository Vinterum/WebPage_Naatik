<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta 	charset="utf-8">
	    <link   href="css/bootstrap.min.css" rel="stylesheet">
	    <script src="js/bootstrap.min.js"></script>
	</head>

	<body>
	    <div class="container">

    		<div class="row">
    			<h3>Tabla de Encargados</h3>
    		</div>

			<div class="row">
				<p>
					<a href="create_encargado.php" class="btn btn-success">Agregar un Encargado</a>
				</p>

				<table class="table table-striped table-bordered">
		            <thead>
		                <tr>
		                	<th>Nombre</th>
		                	<th>Email </th>
                      		<th>Telefono</th>
		                </tr>
		            </thead>
		            <tbody>
		              	<?php
					   	include 'database.php';
					   	$pdo = Database::connect();
					   	$sql = 'SELECT * FROM Encargado ORDER BY Nombre';
						if(is_iterable($pdo->query($sql))) {
	 				   		foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
    					   		echo '<td>'. $row['Nombre'] . '</td>';
    					  		echo '<td>'. $row['Email'] . '</td>';
                				echo '<td>'. $row['Telefono'] . '</td>';
                  				echo '<td width=250>';
								echo '<a class="btn btn-success" href="update_encargado.php?id='.$row['Email'].'">Actualizar</a>';
								echo '&nbsp;';
								echo '<a class="btn btn-danger" href="delete_encargado.php?id='.$row['Email'].'">Eliminar</a>';
						  		echo '</tr>';
					    	}
						} else {
							echo 'La tabla está vacía';
						}

					   	Database::disconnect();
					  	?>
				    </tbody>
	            </table>

	    	</div>
			<div class="form-actions">
			<a class="btn" href="index_naatik.php">Regresar</a>
			</div>

	    </div> <!-- /container -->
	</body>
</html>
