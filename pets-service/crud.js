$(document).ready(function () {
    $.ajax({
        url: "../pets-service/mascotas_usuario.php",
        dataType: "json",
        success: function (data) {
            var cardsContainer = $(".cards-container");
            data.forEach(function (item) {
                var card = $('<div class="card">');
                var cardContent = $('<div class="card-content">');

                var nombre_mascota = $('<p>Nombre: <span style="color: #3c8735;">' + item.nombre_mascota + "</span></p>");
                var edad = $('<p>Edad:  <span style="color: #3c8735;">' + item.anios + " años " + item.meses + " meses</span></p>");
                var raza = $('<p>Raza: <span style="color: #3c8735;">' + item.raza + "</span></p>");
                var genero = $('<p>Genero: <span style="color: #3c8735;">' + item.genero + "</span></p>");
                var tamaño = $('<p>Tamaño: <span style="color: #3c8735;">' + item.peso_mascota + "</span></p>");

                var editButton = $('<button class="btn btn-primary btn-edit" data-id="' + item.id + '" data-bs-toggle="modal" data-bs-target="#editMascotaModal">Editar</button>');
                var deleteButton = $('<button class="btn btn-danger btn-delete" data-id="' + item.id + '">Eliminar</button>');
                
                cardContent.append(nombre_mascota, edad, raza, genero, tamaño, editButton, deleteButton);
                card.append(cardContent);
                cardsContainer.append(card);
            });
            //actualizar
            $(".btn-edit").on("click", function () {
                var id = $(this).data("id");
                var mascota = data.find(m => m.id == id);
            
                if (mascota) {
                    $("#mascotaId").val(mascota.id);
                    $("#nombre_mascota").val(mascota.nombre_mascota);
                    $("#anios").val(mascota.anios);
                    $("#meses").val(mascota.meses);
                    $("#genero").val(mascota.genero);
                }
            });            
            //eliminar
            $(".btn-delete").on("click", function () {
                var id = $(this).data("id");
                if (confirm("¿Seguro de que quieres eliminar esta mascota?")) {
                    $.ajax({
                        url: "delete_mascota.php",
                        type: "POST",
                        data: { id: id },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                alert("Mascota eliminada con éxito.");
                                location.reload();
                            } else {
                                alert("Error al eliminar la mascota: " + (response.error || "Desconocido."));
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