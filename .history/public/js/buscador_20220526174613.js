$( "#formBUSQUEDA" ).keyup(function() {
    var textoBuscador= $("#barra_busqueda_username").val();
     
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
  
  function visitarPerfil(idPerfil) {
    window.location.href = "/perfil/"+idPerfil;
}
function DejarSeguir(userLogued,idseguir,home) {
    $.ajax({
        type: 'POST',
        url: "/unfollow",
        data: ({userLogued: userLogued,idseguir: idseguir}),
        async: true,
        dataType: "json",    
        beforeSend: function ( xhr ) {
            if (home != true) {
                $('#seguir').html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');
            }
         },
        success: function (data) {
            if (home) {
                window.location.reload();
            } else {
            $('#seguir').html('Seguir');
            var numSeguidores = $('#seguidores').text();
            $('#seguidores').html(parseInt(numSeguidores)-1);
            $('#seguir').attr('onclick','Seguir('+userLogued+','+idseguir+')');
            }  
        }
    });
}

function Seguir(userLogued,idseguir) {
    $.ajax({
        type: 'POST',
        url: "/follow",
        data: ({userLogued: userLogued,idseguir: idseguir}),
        async: true,
        dataType: "json",
        beforeSend: function ( xhr ) {
            $('#seguir').html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');
         },
        success: function (data) {
            var textoBuscador= $("#barra_busqueda_username").val();
     
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
        }
    });
}