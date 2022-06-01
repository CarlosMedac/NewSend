var mypage = 1;
var mypageperfil = 1;
mycontent(mypage);
mycontentPerfil(mypageperfil);

$(window).scroll(function (){
    if(document.body.scrollHeight - window.innerHeight === window.scrollY){
        mypage++;
        mypageperfil++;
        mycontent(mypage);
        mycontentPerfil(mypage);
    }
})
function mycontent(mypage) {
    $('#ani_img').show();
    $.ajax({
        type: 'POST',
        url: "/",
        data: {"page":mypage},
        success: function (data) {
            if(data.length<2000){
                $('#loading').text('No hay mas mensajes')
            }else{
                $('.mensaje').animate({scrollTop: $('#loading').offsetTop}, 5000, 'easeOutBounce');
            $('#ani_img').hide();
            $(".put_mess").append(data);
            }
        },
         error: function (xhr,responseText, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            console.log(thrownError);
            console.log(ajaxOptions);
          }
        
    });
}
function mycontentPerfil(mypage) {
    $('#ani_img_perfil').show();
    $.ajax({
        type: 'POST',
        url: "/perfil/"+$('#cod_user').val(),
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
function checkPass(idUsuario) {
    toastr.options = {
        'icon': false,
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    var pass1 = $('#editar_pass1').val();
    var pass2 = $('#editar_pass2').val();
    var pass3 = $('#editar_pass3').val();
    if (pass2 == pass3) {
        $.ajax({
            type: 'POST',
            url: "/checkPass",
            data: ({pass: pass1}),
            async: true,
            dataType: "json",
            success: function (data) {
                if (data.confirmado) {
                    $.ajax({
                        type: 'POST',
                        url: "/changePass",
                        data: ({pass : pass3 , id : idUsuario}),
                        async: true,
                        dataType: "json",
                        success: function (data) {
                            if (data.confirmado) {
                                toastr["success"]("Contraseña guardada");
                                $('#editar_pass1').val('');
                                $('#editar_pass2').val('');
                                $('#editar_pass3').val('');
                            } else {
                                toastr["error"]("No se ha podido guardar la contraseña");
                            }
                        }
                    });
                } else {
                    toastr["warning"]("La contraseña introducida es incorrecta");
                }
            }
        });
    } else {
        toastr["warning"]("Las contraseñas no coinciden");
    }
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
            $('#seguirVersionMoviles').css('background-color','white');
            $('#seguirVersionMoviles').html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');
         },
        success: function (data) {
            $('#seguir').html('<i class="bi bi-person-check-fill"></i>');
            $('#seguirVersionMoviles').html('<i class="bi bi-person-check-fill"></i>');
            var numSeguidores = $('#seguidores').text();
            $('#seguidores').html(parseInt(numSeguidores)+1);
            $('#seguir').attr('onclick','DejarSeguir('+userLogued+','+idseguir+')');
            $('#seguirVersionMoviles').attr('onclick','DejarSeguir('+userLogued+','+idseguir+')');

        }
    });
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
                $('#seguirVersionMoviles').html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');

            }
         },
        success: function (data) {
            if (home) {
                window.location.reload();
            } else {
            $('#seguir').html('Seguir');
            $('#seguirVersionMoviles').html('Seguir');
            var numSeguidores = $('#seguidores').text();
            $('#seguidores').html(parseInt(numSeguidores)-1);
            $('.follow').css('background-color','#8d6aad');
            $('.follow').css('color','white');
            $('#seguir').attr('onclick','Seguir('+userLogued+','+idseguir+')');
            $('#seguirVersionMoviles').attr('onclick','Seguir('+userLogued+','+idseguir+')');

            }  
        }
    });
}
function Like(idMensaje,idUsuario) {
    $.ajax({
        type: 'POST',
        url: "/Like",
        data: ({idMensaje: idMensaje,idUsuario: idUsuario}),
        async: true,
        dataType: "json",
        beforeSend: function () {
            $('#corazon'+idMensaje).html('<i class="bi bi-heart-fill"></i>');
         },
        success: function (data) {
            $('#like'+data.mensaje).html('<div class="corazon"><i class="bi bi-heart-fill"></i></div><div class="likeTotales">'+data.total+'</div>');
            $('#like'+data.mensaje).attr("onclick","QuitarLike("+data.mensaje+","+data.usuario+")");
        }
    });
}
function QuitarLike(idMensaje,idUsuario) {
    $.ajax({
        type: 'POST',
        url: "/QuitarLike",
        data: ({idMensaje: idMensaje,idUsuario: idUsuario}),
        async: true,
        dataType: "json",
        beforeSend: function () {
            $('#corazon'+idMensaje).html('<i id="nolike" class="bi bi-heart"></i>');
         },
        success: function (data) {
            $('#like'+data.mensaje).html('<div class="corazon" id="corazon'+idMensaje+'"><i id="nolike" class="bi bi-heart"></i></div><div class="likeTotales">'+data.total+'</div>');
            $('#like'+data.mensaje).attr("onclick","Like("+data.mensaje+","+data.usuario+")");
        }
    });
}
function LikeRespuesta(idMensaje,idUsuario) {
    $.ajax({
        type: 'POST',
        url: "/LikeRespuesta",
        data: ({idMensaje: idMensaje,idUsuario: idUsuario}),
        async: true,
        dataType: "json",
        beforeSend: function () {
            $('#corazon'+idMensaje).html('<i class="bi bi-heart-fill"></i>');
         },
        success: function (data) {
            $('#like'+data.mensaje).html('<div class="corazon"><i class="bi bi-heart-fill"></i></div><div class="likeTotales">'+data.total+'</div>');
            $('#like'+data.mensaje).attr("onclick","QuitarLikeRespuesta("+data.mensaje+","+data.usuario+")");
        }
    });
}
function QuitarLikeRespuesta(idMensaje,idUsuario) {
    $.ajax({
        type: 'POST',
        url: "/QuitarLikeRespuesta",
        data: ({idMensaje: idMensaje,idUsuario: idUsuario}),
        async: true,
        dataType: "json",
        beforeSend: function () {
            $('#corazon'+idMensaje).html('<i id="nolike" class="bi bi-heart"></i>');
         },
        success: function (data) {
            $('#like'+data.mensaje).html('<div class="corazon" id="corazon'+idMensaje+'"><i id="nolike" class="bi bi-heart"></i></div><div class="likeTotales">'+data.total+'</div>');
            $('#like'+data.mensaje).attr("onclick","LikeRespuesta("+data.mensaje+","+data.usuario+")");
        }
    });
}
function Eliminar(id) {
    $.ajax({
        type: 'POST',
        url: "/Eliminar",
        data: ({id: id}),
        async: true,
        dataType: "json",
        success: function (data) {
            window.location.reload();
        }
    });
}
$('#mensaje_imagen').on("change",function(e){
    let reader = new FileReader();

    reader.readAsDataURL(e.target.files[0]);
    reader.onload = function(){
        let preview = document.getElementById('preview'),
                image = document.createElement('img');
                image.setAttribute('class','preview-img');

        image.src = reader.result;

        preview.innerHTML = '';
        preview.innerHTML += '<button type="reset" id="cancel" class="btn_elimg" onclick="Quitarfoto()"><i class="bi bi-x-lg"></i></button>'
        preview.append(image);
        
    };
});
$('#comentarios_imagen').on("change",function(e){
    let reader = new FileReader();

    reader.readAsDataURL(e.target.files[0]);
    reader.onload = function(){
        let preview = document.getElementById('preview'),
                image = document.createElement('img');
                image.setAttribute('class','preview-img');

        image.src = reader.result;

        preview.innerHTML = '';
        preview.innerHTML += '<button type="reset" id="cancel" class="btn_elimg" onclick="Quitarfoto()"><i class="bi bi-x-lg"></i></button>'
        preview.append(image);
        
    };
});
$('#editar_img').on("change",function(e){
    let reader = new FileReader();

    reader.readAsDataURL(e.target.files[0]);
    reader.onload = function(){
        let preview = document.getElementById('pre_img'),
                image = document.createElement('img');
                image.setAttribute('class','preview-img');

        image.src = reader.result;
        preview.style.backgroundImage="url("+image.src+")";      
    };
});
$('#submitHome').on("click",function(){
    if($('#mensaje_mensaje').val() != "" || $('#mensaje_imagen').val() != "") {
        $('#formhome').submit();
    } 
});
function Quitarfoto () {
    let preview = document.getElementById('preview');
    preview.innerHTML = '';
    $('#mensaje_imagen').val("");
}
$(".mensaje_texto").each(function () {
    if (this.scrollHeight == 0) {
        this.setAttribute("style", "height:" + (48) + "px;overflow-y:hidden;");
    } else {
        this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
    }
  }).on("input", function () {
    this.style.height = "auto";
    this.style.height = (this.scrollHeight) + "px";
  });

String.prototype.isEmpty = function() {
    return (this.length === 0 || !this.trim());
};

function seguidores (idPerfil) {
    window.location.href = "/perfil/seguidores/"+idPerfil;
}
function seguidos (idPerfil) {
    window.location.href = "/perfil/seguidos/"+idPerfil;
}
function irPerfil (idPerfil) {
    window.location.href = "/perfil/"+idPerfil;
}
function irMensaje (idMensaje) {
    window.location.href = "/mensaje/"+idMensaje;
}
function IrComentario (idComentario) {
    window.location.href = "/comentario/"+idComentario;
}
function IrHome () {
    window.location.href = "/";
}
$('.imagen_mensaje').on("click",function(e){
    e.preventDefault();
});

function mostrarMas(id) {
    $('#subrespuestas'+id).slideToggle('fast');
    if ($('#mostrarMasTexto'+id).text() == 'Mostrar menos') {
        $('#mostrarMasTexto'+id).html('Mostrar respuestas');
    } else {
        $('#mostrarMasTexto'+id).html('Mostrar menos');
    }  
}

function SeguirSeguidos(userLogued,idseguir) {
    $.ajax({
        type: 'POST',
        url: "/follow",
        data: ({userLogued: userLogued,idseguir: idseguir}),
        async: true,
        dataType: "json",
        beforeSend: function ( xhr ) {
            $('#seguir'+idseguir).html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');
         },
        success: function (data) {
            $('#seguir'+idseguir).html('<i class="bi bi-person-check-fill"></i>');
            var numSeguidores = $('#seguidores').text();
            $('#seguidores').html(parseInt(numSeguidores)+1);
            $('#seguir'+idseguir).attr('onclick','DejarSeguirSeguidos('+userLogued+','+idseguir+')');
        }
    });
}

function DejarSeguirSeguidos(userLogued,idseguir,home) {
    $.ajax({
        type: 'POST',
        url: "/unfollow",
        data: ({userLogued: userLogued,idseguir: idseguir}),
        async: true,
        dataType: "json",    
        beforeSend: function ( xhr ) {
            if (home != true) {
                $('#seguir'+idseguir).html('<img src="../../uploads/img/ajax-loader.gif" id="ani_img_seguir"/>');
            }
         },
        success: function (data) {
            if (home) {
                window.location.reload();
            } else {
            $('#seguir'+idseguir).html('Seguir');
            var numSeguidores = $('#seguidores').text();
            $('#seguidores').html(parseInt(numSeguidores)-1);
            $('#seguir'+idseguir).attr('onclick','SeguirSeguidos('+userLogued+','+idseguir+')');
            }  
        }
    });
}

//Menu Responsive
$('#menu-nav').on('click', function () {
    $('.nav').addClass('show-menu');
});
$('#cerrar-nav').on('click', function () {
    $('.nav').removeClass('show-menu');
});