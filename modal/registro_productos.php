	<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="nuevoProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo producto</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="guardar_producto" name="guardar_producto">
			<div id="resultados_ajax_productos"></div>
			  <div class="form-group">
				<label for="codigo" class="col-sm-3 control-label"  >Código</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Código del producto" minlength="3" maxlength="3"   pattern="^[A-Za-z][0-9]+$" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Nombre</label>
				<div class="col-sm-8">
					<textarea class="form-control" id="nombre" name="nombre" placeholder="Nombre del producto" required maxlength="255" ></textarea>
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="categoria" class="col-sm-3 control-label">Categoría</label>
				<div class="col-sm-8">
					<select class='form-control' name='categoria' id='categoria' required>
						<option value="">Selecciona una categoría</option>
							<?php 
							$query_categoria=mysqli_query($con,"select * from categorias order by nombre_categoria");
							while($rw=mysqli_fetch_array($query_categoria))	{
								?>
							<option value="<?php echo $rw['id_categoria'];?>"><?php echo $rw['nombre_categoria'];?></option>			
								<?php
							}
							?>
					</select>			  
				</div>
			  </div>
			  
			<div class="form-group">
				<label for="precio" class="col-sm-3 control-label">Precio</label>
				<div class="col-sm-8">
				  <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio de venta del producto" required  maxlength="8">
				</div>
			</div>
			
			<div class="form-group">
				<label for="stock" class="col-sm-3 control-label">Stock</label>
				<div class="col-sm-8">
				  <input type="number" min="0" class="form-control" id="stock" name="stock" placeholder="Inventario inicial" required  maxlength="8">
				</div>
			</div>
			 
			 
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
		  </div>
		  </form>
		</div>
	  </div>
	</div>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Obtener el formulario
		const form = document.getElementById('guardar_producto');
		
		if(form) {
			form.addEventListener('submit', function(e) {
				
				e.preventDefault();
				
				
				const nombre = document.getElementById('nombre').value.trim();
				const codigo = document.getElementById('codigo').value.trim();
				
				
				const inicial = nombre[0].toUpperCase();
				
				
				if(!codigo.startsWith(inicial) || !/^[A-Z][0-9]+$/.test(codigo)) {
					
					document.getElementById('resultados_ajax_productos').innerHTML = `
						<div class="alert alert-danger" role="alert">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>Error!</strong> El código debe comenzar con la inicial del nombre (${inicial}) y contener solo números después.
						</div>`;
					return false;
				}
				
				
				let datos = new FormData(form);
				
				
				fetch('ajax/nuevo_producto.php', {
					method: 'POST',
					body: datos
				})
				.then(response => response.text())
				.then(data => {
					
					document.getElementById('resultados_ajax_productos').innerHTML = data;
					
					
					if(data.includes('alert-success')) {
						
						form.reset();
						
						setTimeout(function() {
							$('#nuevoProducto').modal('hide');
						}, 2000);
					}
				})
				.catch(error => {
					console.error('Error:', error);
					document.getElementById('resultados_ajax_productos').innerHTML = `
						<div class="alert alert-danger" role="alert">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>Error!</strong> Ha ocurrido un error al procesar la solicitud.
						</div>`;
				});
			});
		}
	});
	</script>


	<?php
		}
	?>