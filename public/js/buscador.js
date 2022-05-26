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
  function IrRegistrarse () {
    window.location.href = "/registrarse";
}
  
  function visitarPerfil(idPerfil) {
    window.location.href = "/perfil/"+idPerfil;
}
<<<<<<< HEAD
// function DejarSeguir(userLogued,idseguir) {
//     $.ajax({
//         type: 'POST',
//         url: "/unfollow",
//         data: ({userLogued: userLogued,idseguir: idseguir}),
//         async: true,
//         dataType: "json",    
//         beforeSend: function ( xhr ) {
=======
function DejarSeguirBuscador(userLogued,idseguir) {
    $.ajax({
        type: 'POST',
        url: "/unfollow",
        data: ({userLogued: userLogued,idseguir: idseguir}),
        async: true,
        dataType: "json",    
        beforeSend: function ( xhr ) {
>>>>>>> 5c8aeebd7f73989d2084993cf0e051fcc9561df4
            
//             $('[name='+idseguir+']').html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');
            
//          },
//         success: function (data) {
//             var textoBuscador= $("#barra_busqueda_username").val();
            
//         $.ajax({
//             type: 'POST',
//             url: "/buscador",
//             data: {"userSolicitado":textoBuscador},
//             success: function (data) {
//                 console.log( data );
//                 console.log("Ajax Success :)"); 
//                 if(data !=""){
//                     $("#usuariosEncontrados").html("");
                
//                 }         
//                 $('#usuariosEncontrados').append(data);


//             },
//              error: function (xhr,responseText, ajaxOptions, thrownError) {
//                 console.log(xhr.responseText);
//                 console.log(thrownError);
//                 console.log(ajaxOptions);
//               }
          
//         });
//         }
//     });
// }

<<<<<<< HEAD
// function Seguir(userLogued,idseguir) {
//     $.ajax({
//         type: 'POST',
//         url: "/follow",
//         data: ({userLogued: userLogued,idseguir: idseguir}),
//         async: true,
//         dataType: "json",
//         beforeSend: function ( xhr ) {
//             $('[name='+idseguir+']').html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');
//          },
//         success: function (data) {
//             var textoBuscador= $("#barra_busqueda_username").val();
=======
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
>>>>>>> 5c8aeebd7f73989d2084993cf0e051fcc9561df4

//         $.ajax({
//             type: 'POST',
//             url: "/buscador",
//             data: {"userSolicitado":textoBuscador},
//             success: function (data) {
//                 console.log( data );
//                 console.log("Ajax Success :)"); 
//                 if(data !=""){
//                     $("#usuariosEncontrados").html("");
                
//                 }         
//                 $('#usuariosEncontrados').append(data);


//             },
//              error: function (xhr,responseText, ajaxOptions, thrownError) {
//                 console.log(xhr.responseText);
//                 console.log(thrownError);
//                 console.log(ajaxOptions);
//               }
          
//         });
    
//         }
//     });
// }