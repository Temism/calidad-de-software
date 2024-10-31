<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['mod_codigo'])) {
           $errors[] = "Código vacío";
        } else if (empty($_POST['mod_nombre'])){
			$errors[] = "Nombre del producto vacío";
		} else if ($_POST['mod_categoria']==""){
			$errors[] = "Selecciona la categoría del producto";
		} else if (empty($_POST['mod_precio'])){
			$errors[] = "Precio de venta vacío";
		} else if (
			!empty($_POST['mod_id']) &&
			!empty($_POST['mod_codigo']) &&
			!empty($_POST['mod_nombre']) &&
			$_POST['mod_categoria']!="" &&
			!empty($_POST['mod_precio'])
		){
			$codigo = $_POST['mod_codigo'];
			$nombre = $_POST['mod_nombre'];
			$inicial = substr($nombre, 0, 1);

			// Validar que el código empiece con la inicial del nombre
			if (!preg_match("/^" . $inicial . "[0-9]+$/", $codigo)) {
				$errors[] = "El código debe comenzar con la inicial del nombre ($inicial) y contener solo números después.";
			} else {
				/* Connect To Database*/
				require_once ("../config/db.php"); // Contiene las variables de configuracion para conectar a la base de datos
				require_once ("../config/conexion.php"); // Contiene funcion que conecta a la base de datos

				// Escapar y limpiar los datos de entrada
				$codigo = mysqli_real_escape_string($con, (strip_tags($_POST["mod_codigo"], ENT_QUOTES)));
				$nombre = mysqli_real_escape_string($con, (strip_tags($_POST["mod_nombre"], ENT_QUOTES)));
				$categoria = intval($_POST['mod_categoria']);
				$stock = intval($_POST['mod_stock']);
				$precio_venta = floatval($_POST['mod_precio']);
				$id_producto = intval($_POST['mod_id']);

				
				$check_query = mysqli_query($con, "SELECT * FROM products WHERE nombre_producto='$nombre' AND id_producto != '$id_producto'");
				if (mysqli_num_rows($check_query) > 0) {
					$errors[] = "Error: El producto '$nombre' ya existe.";
				} else {
					
					$sql = "UPDATE products SET codigo_producto='$codigo', nombre_producto='$nombre', id_categoria='$categoria', precio_producto='$precio_venta', stock='$stock' WHERE id_producto='$id_producto'";
					$query_update = mysqli_query($con, $sql);

					if ($query_update) {
						$messages[] = "Producto ha sido actualizado satisfactoriamente.";
					} else {
						$errors[] = "Lo siento, algo ha salido mal intenta nuevamente. " . mysqli_error($con);
					}
				}
			}
		} else {
			$errors []= "Error desconocido.";
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