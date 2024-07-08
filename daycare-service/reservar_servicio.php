<?php 

$conexion = mysqli_connect ("localhost", "animalre", "y367}A]y){K4Cg4", "animalre_database");

if (mysqli_connect_errno ()) {
    printf ("Conexión fallida:% s \ n", mysqli_connect_error ());
    
}

	$fecha_inicio = $_POST["fecha_inicio"];
	$fecha_fin = $_POST["fecha_fin"];
	$direccion = $_POST["direccion"];
	$descuento = $_POST["descuento"];
	$precio_total = $_POST["precio_total"];
	$dias_reserva = $_POST["dias_reserva" ];
	$id_usuario_reserva=$_POST["id_usuario_reserva"];
	$estado_reserva=$_POST["estado_reserva"];
	$id_pro_servi=$_POST["id_pro_servi"];
	$servicio_especifico=$_POST["servicio_especifico"];
	$content = $_POST["listaIdsMascotas"];
	$grupo=$_POST["grupo"];
	
    $json = json_decode($content, true);
    $conteo=0;
    $fila;
        foreach ($json as $mydata) {
            $idMascota = $mydata;
          
			$sqlcont=mysqli_query($conexion,"SELECT count(*) FROM Reservas, MascotasReservadas
	    	WHERE Reservas.id_usuario_reserva= '$id_usuario_reserva' AND Reservas.id_pro_servicio<=2  AND Reservas.fecha_inicio='$fecha_inicio' AND MascotasReservadas.id_mascota='$idMascota' AND MascotasReservadas.id_reservacion=Reservas.id");
           
            $fila = mysqli_fetch_row($sqlcont);
           
            if ($fila[0]!=0){ 
                $conteo++;
            }
            
        }
		
if($conteo==0){
    
    $sql = "INSERT INTO Reservas (fecha_inicio, fecha_fin,direccion, descuento,precio_total,dias_reserva, id_usuario_reserva, id_pro_servicio,servicio_especifico, estado_reserva, grupo_reserva) VALUES 
	('$fecha_inicio' ,'$fecha_fin' ,'$direccion' ,'$descuento' ,'$precio_total' ,'$dias_reserva', '$id_usuario_reserva', '$id_pro_servi', '$servicio_especifico', '$estado_reserva', '$grupo')";
	
        $result = mysqli_query($conexion,$sql);

	if($result){
		
		$sql2="SELECT MAX(id) FROM Reservas WHERE id_usuario_reserva= $id_usuario_reserva ";
        $id_reserva_server=mysqli_query($conexion,$sql2);
          
         $fila = mysqli_fetch_row($id_reserva_server);
     
        foreach ($json as $mydata) {
          
           $idMascota = $mydata;
         
         	$sqlMascotas = "INSERT INTO MascotasReservadas (id_reservacion, id_mascota,usuario_mascota_res) VALUES 
	('$fila[0]' ,'$idMascota' ,'$id_usuario_reserva')"; 
               $resultMascota = mysqli_query($conexion,$sqlMascotas);
        }
        
		
	 if (!$id_reserva_server) {
            echo 'No se pudo ejecutar la consulta: ';
            exit;
        }
      
        echo $fila[0];
	}
	
	else{
		echo "No pudo insertar Reserva $sql";
	}
}
else{
    echo "error:1";
}
		
 ?>