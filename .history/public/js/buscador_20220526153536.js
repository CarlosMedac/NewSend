$( "#formBUSQUEDA" ).keyup(function() {
    var textoBuscador= $("#barra_busqueda_username").val();
    console.log( textoBuscador );
     
    if( textoBuscador.replace(/ /g, "")==""){
        $("#usuariosEncontrados").html("");
    }
    else{

    
        $.ajax({
            type: 'POST',
            url: "/buscador",
            data: {"userSolicitado":textoBuscador},
            success: function (data) {
                console.log( data );
                console.log("Ajax Success :)"); 
                if(data !=""){
                    $("#usuariosEncontrados").html("");
                
                }         
                $('#usuariosEncontrados').append(data);


            },
             error: function (xhr,responseText, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
                console.log(thrownError);
                console.log(ajaxOptions);
              }
          
        });
    }

  });
  
  function visitarPerfil (idPerfil) {
    window.location.href = "/perfil/"+idPerfil;
}