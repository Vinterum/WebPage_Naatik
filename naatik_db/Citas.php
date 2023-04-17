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
    			<h3>Tabla de Citas</h3>
    		</div>

			<div class="row">
				<p>
					<a href="create_cita.php" class="btn btn-success">Agregar una cita</a>
				</p>

				<table class="table table-striped table-bordered">
		            <thead>
		                <tr>
		                	<th>Encargado</th>
		                	<th>Usuario</th>
                      		<th>Empieza</th>
                      		<th>Termina</th>
							<th>Estado</th>
		                </tr>
		            </thead>
		            <tbody>
		              	<?php
					   	include 'database.php';
					   	$pdo = Database::connect();
					   	$sql = 'SELECT c.Id AS c_id, e.Nombre AS Encargado, u.Nombre AS Usuario, i.Empieza, i.Termina, s.Estado 
						   FROM ((((Cita c
						   INNER JOIN Encargado e ON Encargado_id = e.Email)
						   INNER JOIN Usuario u ON Usuario_id = u.Email)
						   INNER JOIN Itinerario i ON Itinerario_id = i.Id)
						   INNER JOIN Estado s ON i.Estado_id = s.Id)';
						if(is_iterable($pdo->query($sql))) {
	 				   		foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
    					   		echo '<td>'. $row['Encargado'] . '</td>';
    					  		echo '<td>'. $row['Usuario'] . '</td>';
								echo '<td>'. $row['Empieza'] . '</td>';
								echo '<td>'. $row['Termina'] . '</td>';
                				echo '<td>'. $row['Estado'] . '</td>';
                  				echo '<td width=250>';
								echo '<a class="btn" href="read_cita.php?id='.$row['c_id'].'">Detalles</a>';
								echo '&nbsp;';
								echo '<a class="btn btn-success" href="update_cita.php?id='.$row['c_id'].'">Actualizar</a>';
								echo '&nbsp;';
								echo '<a class="btn btn-danger" href="delete_cita.php?id='.$row['c_id'].'">Eliminar</a>';
								echo '</td>';
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

