var mypage = 1;
var mypageperfil = 1;
mycontent(mypage);
mycontentPerfil(mypageperfil);

$(window).scroll(function (){
    if($(window).scrollTop() + $(window).height() >= $(document).height()){
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
        url: "/home",
        data: {"page":mypage},
        success: function (data) {
            if(data.length<600){
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
            if(data.length<600){
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

function Like(idMensaje,idUsuario) {
    $.ajax({
        type: 'POST',
        url: "/Like",
        data: ({idMensaje: idMensaje,idUsuario: idUsuario}),
        async: true,
        dataType: "json",
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
        success: function (data) {
            $('#like'+data.mensaje).html('<div class="corazon"><i id="nolike" class="bi bi-heart"></i></div><div class="likeTotales">'+data.total+'</div>');
            $('#like'+data.mensaje).attr("onclick","Like("+data.mensaje+","+data.usuario+")");
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


function irPerfil (idPerfil) {
    window.location.href = "/perfil/"+idPerfil;
}
function irMensaje (idMensaje) {
    window.location.href = "/mensaje/"+idMensaje;
}
$('.imagen_mensaje').on("click",function(e){
    e.preventDefault();
});