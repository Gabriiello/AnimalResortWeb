if (!document.cookie.includes('usuario_id')) {
  alert('Sesión expirada, inicie nuevamente');
  window.location.href = '../index.php';
}
var fechasElegidas = [];
var direccionId;
document.addEventListener("DOMContentLoaded", function () {
  var fechaSeleccionada = document.getElementById("fechaSeleccionada");
  var diasSeleccionados = document.getElementById("diasSeleccionados");
  var direccionInfo = document.getElementById("direccionInfo");

  var calendar = flatpickr(fechaSeleccionada, {
    mode: "multiple",
    minDate: "today",
    maxDate: new Date().fp_incr(90),
    dateFormat: "Y-m-d",
    onChange: function (selectedDates, dateStr, instance) {
      fechasElegidas = selectedDates.map(function (date) {
        return date.toISOString().split("T")[0];
      });
      diasSeleccionados.textContent = "Días: " + fechasElegidas.length;
      actualizarResumen();
    },
  });

  // direcciones
  $.ajax({
    url: "../daycare-service/direccion_reserva.php",
    dataType: "json",
    success: function (data) {
      var direccionHtml = "Dirección de domicilio: <br>" + data[0].ciudad + ", " + data[0].direccion;
      direccionId = data[0].id;
      direccionInfo.innerHTML = direccionHtml;
    },
    error: function () {
      console.log("Error al obtener los datos direccion.");
    },
  });

  // mascotas
  $.ajax({
    url: "../daycare-service/mascota_reserva.php",
    dataType: "json",
    success: function (data) {
      var mascotasContainer = $(".info-mascotas");

      data.forEach(function (item) {
        var nombre_mascota = item.nombre_mascota;
        var id_mascota = item.id;
        var checkbox = $('<input type="checkbox">').attr({
          value: nombre_mascota,
          id: id_mascota,
        });

        var label = $('<label>').attr('for', 'checkbox_' + nombre_mascota).text(nombre_mascota);
        var checkboxContainer = $('<div>').addClass('checkbox-container');

        checkboxContainer.append(label);
        checkboxContainer.append(checkbox);
        mascotasContainer.append(checkboxContainer);
      });
    },
    error: function () {
      console.log("Error al obtener los datos de las mascotas.");
    },
  });

  function actualizarResumen() {
    var numMascotas = $("input[type=checkbox]:checked").length;
    var cantDias = fechasElegidas.length;
    var precioDia = 20000; // Precio por día

    var precioTotal = cantDias * numMascotas * precioDia;
    // Si no hay fechas seleccionadas o mascotas, el precio total es cero
    if (cantDias === 0 || numMascotas === 0) {
      precioTotal = 0;
    }

    // Actualizar elementos en el resumen
    $("#numMascotas").text(numMascotas);
    $("#cantDias").text(cantDias);
    $("#precioDia").text(precioDia);
    $("#precioTotal").text(precioTotal);
  }

  $(document).on("change", 'input[type="checkbox"]', function () {
    actualizarMascotasSeleccionadas();
    actualizarResumen();
  });

  function actualizarMascotasSeleccionadas() {
    var mascotasSeleccionadas = [];
    $("input[type=checkbox]:checked").each(function () {
      mascotasSeleccionadas.push(this.id);
    });
  }
});

const btnReservar = document.getElementById('btnReservar');

btnReservar.addEventListener('click', async function() {

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
         listaIdsMascotas: JSON.stringify(mascotasSeleccionadas)
      };

      $.ajax({
          type: "POST",
          url: "reservar_servicio.php",
          data: dataToSend,
          success: function(response) {
              console.log('Reserva realizada con éxito para la fecha:', fecha);
              window.location.href = '../history-service/reservas.html';
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