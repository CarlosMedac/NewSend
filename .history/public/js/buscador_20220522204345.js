$( "#formBUSQUEDA" ).keyup(function() {
    var textoBuscador= $("#barra_busqueda_username").val();
    console.log( textoBuscador );

    $.ajax({
        type: 'POST',
        url: "/buscadorUsers",
        data: {"userSolicitado":textoBuscador},
        success: function (data) {
            console.log( data );
            console.log("BIEN"); 
            
            // $('#like'+data.mensaje).html('<div class="corazon"><i class="bi bi-heart-fill"></i></div><div class="likeTotales">'+data.total+'</div>');

            //     $('#usuariosEncontrados').text(data)
                $.ajax({
                    type: 'POST',
                    url: "/buscador",
                    data: {"userSolicitado":data},
                    success: function (data) {
                     
                        console.log("0k"); 
                    },
                     error: function (xhr,responseText, ajaxOptions, thrownError) {
                        console.log(xhr.responseText);
                        console.log(thrownError);
                        console.log(ajaxOptions);
                      }
                    
                });

        },
         error: function (xhr,responseText, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(thrownError);
            console.log(ajaxOptions);
          }
        
    });

  });