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

            $('#usuariosEncontrados'+data.username).html('<div class="corazon"><i class="bi bi-heart-fill"></i></div><div class="likeTotales">'+data.username+'</div>');

                //$('#usuariosEncontrados').text(data)
             

        },
         error: function (xhr,responseText, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(thrownError);
            console.log(ajaxOptions);
          }
        
    });

  });