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
            
            var prueba= document.getElementById("mostarDausuariosEncontradostosCompra");
          
            prueba.innerHTML+=" <p>Cantidad: "+data+ "</p>";
            
                    $('#usuariosEncontrados').text(data);
           
                
             

        },
         error: function (xhr,responseText, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(thrownError);
            console.log(ajaxOptions);
          }
        
    });

  });
  
  