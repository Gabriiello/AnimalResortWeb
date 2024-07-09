const btnReservar = document.getElementById('btnReservar');

btnReservar.addEventListener('click', async function() {

    console.log("Fechas elegidas:", fechasElegidas);
    var direccionId;

    try {
        var direccionData = await $.ajax({
            url: "direccion_reserva.php",
            dataType: "json"
        });
        direccionId = direccionData.id;
    } catch (error) {
        console.log("Error al obtener los datos de dirección:", error);
        return;
    }

    var fechasElegidas=['2024-07-10', '2024-07-17', '2024-07-09'] ;
    var numMascotas = $("input[type=checkbox]:checked").length;

    if (fechasElegidas.length === 0 || numMascotas === 0) {
        alert('Seleccione al menos una fecha y una mascota para realizar la reserva.');
        return;
    }

    var mascotasSeleccionadas = [];
    $("input[type=checkbox]:checked").each(function () {
        mascotasSeleccionadas.push(this.id);
    });

    var descuento = 0;
    var precio_total = parseInt($("#precioTotal").text().trim().replace('$', '').replace(',', '')); 
    var id_usuario_reserva = getCookie('usuario_id');
    var estado_reserva = "Reserva exitosa"; 

    fechasElegidas.forEach(function(fecha) {
        var dataToSend = {
            fecha_inicio: fecha,
            fecha_fin: fecha,
            direccion: direccionId,
            descuento: descuento,
            precio_total: precio_total,
            dias_reserva: fechasElegidas.length,
            id_usuario_reserva: id_usuario_reserva,
            estado_reserva: estado_reserva,
            listaIdsMascotas: mascotasSeleccionadas
        };

        $.ajax({
            type: "POST",
            url: "reservar_servicio.php",
            data: dataToSend,
            success: function(response) {
                console.log('Reserva realizada con éxito para la fecha:', fecha);
            },
            error: function(error) {
                console.error('Error al realizar la reserva para la fecha:', fecha, error);
                alert('Hubo un error al realizar la reserva para la fecha: ' + fecha + '. Por favor, inténtelo de nuevo.');
            }
        }); 
    });
});

function getCookie(cookieName) {
    var name = cookieName + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieArray = decodedCookie.split(';');
    for (var i = 0; i < cookieArray.length; i++) {
        var cookie = cookieArray[i].trim();
        if (cookie.indexOf(name) == 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return "";
}