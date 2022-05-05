$(document).ready(function(){
console.log("prueba");
});

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