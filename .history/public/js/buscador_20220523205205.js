$( "#formBUSQUEDA" ).keyup(function() {
    var textoBuscador= $("#barra_busqueda_username").val();
    console.log( textoBuscador );

    $.ajax({
        type: 'POST',
        url: "/buscador",
        data: {"userSolicitado":textoBuscador},
        success: function (data) {
            console.log( data.username );
            console.log("Ajax Success :)"); 
            
            $('.usuariosEncontrados').append(data);
             

        },
         error: function (xhr,responseText, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(thrownError);
            console.log(ajaxOptions);
          }
        
    });

  });
  
  