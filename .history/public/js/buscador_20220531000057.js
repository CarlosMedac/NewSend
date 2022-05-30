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

$( "#actualidad" ).click(function() {
    var noticiaSeleccionada= $("#actualidad").val();
    
    
        $.ajax({
            type: 'POST',
            url: "/",
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
  });


function noticiasElegida(mypage) {
    
    $.ajax({
        type: 'POST',
        url: "/noticias/"+$('#cod_user').val(),
        data: {"page":mypage},
        success: function (data) {
            if(data.length<2000){
                $('#loading').text('No hay mas mensajes')
            }else{
                $('.mensaje').animate({scrollTop: $('#loading').offsetTop}, 5000, 'easeOutBounce');
            $('#ani_img_perfil').hide();
            $(".put_messPerfil").append(data);
            }
        },
         error: function (xhr,responseText, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(thrownError);
            console.log(ajaxOptions);
          }
        
    });
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