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
            async: true,
            beforeSend: function ( xhr ) {
                $('#bloqueImgBusqueda').html('<img src="../../uploads/img/search-fondo.gif" id="iconoBuscarGif"/>'); 
                $('#usuariosEncontrados').html('<div class="buscandoUser"><div class="containerBuscandoUser"><div class="gifBuscandoDIV"><img src="../../uploads/img/ajax-loader.gif" id="gifBuscando"/></div><div class="textoBuscando">Buscando "'+textoBuscador+'"...</div></div</div>'); 

             },
            success: function (data) {
                if(data !=""){
                    $("#usuariosEncontrados").html("");
                
                }    
                if(data.length<1000){
                    $('#usuariosEncontrados').html('<div class="buscandoUser"><div class="containerBuscandoUser"><div class="textoBuscando">No se han encontrado usuarios para "'+textoBuscador+'"</div></div</div>'); 

                }     
                $('#usuariosEncontrados').append(data);
                $('#bloqueImgBusqueda').html('<i class="bi bi-search" id="iconoBuscar"></i>'); 

            },
             error: function (xhr,responseText, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
                console.log(thrownError);
                console.log(ajaxOptions);
              }
          
        });
    }

  });
  function IrRegistrarse () {
    window.location.href = "/registrarse";
}IrLogIn
function  IrLogIn() {
    window.location.href = "/login";
}
  function visitarPerfil(idPerfil) {
    window.location.href = "/perfil/"+idPerfil;
}

function DejarSeguirBuscador(userLogued,idseguir) {
    $.ajax({
        type: 'POST',
        url: "/unfollow",
        data: ({userLogued: userLogued,idseguir: idseguir}),
        async: true,
        dataType: "json",    
        beforeSend: function ( xhr ) {
            $('[name='+idseguir+']').html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>'); 
         },
        success: function (data) {
            var textoBuscador= $("#barra_busqueda_username").val();      
        $.ajax({
            type: 'POST',
            url: "/buscador",
            data: {"userSolicitado":textoBuscador},
            success: function (data) {
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
}


function noticiasElegida(mypage) {
    var noticiaSeleccionada= $(mypage).attr("id");
    window.location.href = "/noticias/"+noticiaSeleccionada;
}

function SeguirBuscador(userLogued,idseguir) {
    $.ajax({
        type: 'POST',
        url: "/follow",
        data: ({userLogued: userLogued,idseguir: idseguir}),
        async: true,
        dataType: "json",
        beforeSend: function ( xhr ) {
            $('[name='+idseguir+']').html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');
            $('#seguirBuscador').css('background-color','white');
         },
        success: function (data) {
            var textoBuscador= $("#barra_busqueda_username").val();
        $.ajax({
            type: 'POST',
            url: "/buscador",
            data: {"userSolicitado":textoBuscador},
            success: function (data) {
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
}
