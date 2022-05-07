var mypage = 1;
mycontent(mypage);

$(window).scroll(function (){
    if($(window).scrollTop() + $(window).height() == $(document).height()){
        mypage++;
        mycontent(mypage);
    }
})
function mycontent(mypage) {
    $('#ani_img').show();
    $.ajax({
        type: 'POST',
        url: "/home",
        data: {"page":mypage},
        success: function (data) {
            if(data.length<100){
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

function Eliminar(id) {
    $.ajax({
        type: 'POST',
        url: "/Eliminar",
        data: ({id: id}),
        async: true,
        dataType: "json",
        success: function (data) {
            console.log(data)
            window.location.reload();
        }
    });
}
$('#mensaje_imagen').on("change",function(){
    var url="";
    url = $(this).val();
    var indice =url.lastIndexOf("\\");
    url = url.substring(indice+1);
    var img = $('<img />',{ id: 'imagen_form', src: 'uploads/img/'+url+''}).appendTo($('.imagenformulario'));
});
String.prototype.isEmpty = function() {
    return (this.length === 0 || !this.trim());
};
