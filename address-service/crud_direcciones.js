
$(document).ready(function () {
    
   


    $.ajax({
        url: "../address-service/direcciones_usuario.php",
        dataType: "json",
        success: function (data) {
            var cardsContainer = $(".cards-container");
            data.forEach(function (item) {
                var card = $('<div class="card">');
                var cardContent = $('<div class="card-content">');
                var editButton = $('<button class="btn btn-primary btn-edit" data-id="' + item.id + '" data-bs-toggle="modal" data-bs-target="#editDireccionModal">Editar</button>');
                var deleteButton = $('<button class="btn btn-danger btn-delete" data-id="' + item.id + '">Eliminar</button>');
                var ciudad = $(
                    '<p>Ciudad<br> <span style="color: #3c8735;">' +
                      item.ciudad +
                      "</span></p>"
                  );
                  var direccion = $(
                    '<p>Direccion<br>  <span style="color: #3c8735;">' +
                      item.direccion +
                      "</span></p>"
                  );
                  var descripcion = $(
                    '<p>Descripcion casa<br> <span style="color: #3c8735;">' +
                      item.descripcion +
                      "</span></p>"
                  );
    
                  var imagen = $('<img src="../statics/logo_casa.png" alt="Imagen">');

                cardContent.append(imagen,ciudad, direccion, descripcion, editButton, deleteButton);
                card.append(cardContent);
                cardsContainer.append(card);
            });

            // Actualizar datos en el modal
            $(".btn-edit").on("click", function () {
                var id = $(this).data("id");
                
                var direccion = data.find(d => d.id == id);
                
                
                
                if (direccion) {
                    console.log("Entro");
                    $("#direccionId").val(direccion.id);
                    $("#ciudad").val(direccion.ciudad);
                    $("#direccion").val(direccion.direccion);
                    $("#descripcion").val(direccion.descripcion);
                } 
            });

            // Eliminar
            $(".btn-delete").on("click", function () {
                var id = $(this).data("id");
                if (confirm("¿Seguro de que quieres eliminar esta dirección?")) {
                    $.ajax({
                        url: "delete_direccion.php",
                        type: "POST",
                        data: { id: id },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                alert("Dirección eliminada con éxito.");
                                location.reload();
                            } else {
                                alert("Error al eliminar la dirección: " + (response.error || "Desconocido."));
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("Error al realizar la solicitud:", textStatus, errorThrown);
                        }
                    });
                }
            });

            

        },
        error: function () {
            console.log("Error al obtener los datos.");
        },
    });
});
