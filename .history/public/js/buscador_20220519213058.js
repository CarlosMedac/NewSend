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
            (textoBuscador.username).appendTo('usuariosEncontrados');
        $('<div class="content"/>')
            .html(json.html).appendTo('body');

        },
         error: function (xhr,responseText, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(thrownError);
            console.log(ajaxOptions);
          }
        
    });

  });