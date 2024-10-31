<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['mod_nombre'])) {
           $errors[] = "Nombre vacío";
        }  else if (
			!empty($_POST['mod_id']) &&
			!empty($_POST['mod_nombre'])
		)
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$nombre=mysqli_real_escape_string($con,(strip_tags($_POST["mod_nombre"],ENT_QUOTES)));
		$descripcion=mysqli_real_escape_string($con,(strip_tags($_POST["mod_descripcion"],ENT_QUOTES)));
		
		
		$id_categoria=intval($_POST['mod_id']);
		$check_query = mysqli_query($con, "SELECT * FROM categorias WHERE nombre_categoria='$nombre'");

		if (mysqli_num_rows($check_query) >= 1) {
			
			echo "<div class='alert alert-danger'>Error: La categoría '$nombre' ya existe.</div>";
		} else {
			
			$sql="UPDATE categorias SET nombre_categoria='".$nombre."', descripcion_categoria='".$descripcion."' WHERE id_categoria='".$id_categoria."'";

			if (mysqli_query($con, $sql)) {
				echo "<div class='alert alert-success'>Categoría '$nombre' insertada correctamente.</div>";
			} else {
				echo "<div class='alert alert-danger'>Error al insertar la categoría: " . mysqli_error($con) . "</div>";
			}
		}
		
		
		if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}

?>