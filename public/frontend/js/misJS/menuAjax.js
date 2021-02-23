$(document).ready(function () {
    inicio();
    cargaCatalogo();
    cargaContacto();
    cargaMiCuenta();
});

function inicio() {

}

function cargaCatalogo() {
    $(".catalogo").click(function (e) {
        e.preventDefault();
        $("#content").empty();
        
        $.ajax({

            type: "method",
            url: "/catalogo",
            
            dataType: "html",
            success: function (html) {
                $("#content").append(html);
                menuVideojuegos()
                catalogoVideojuegos();

            }
        });

    });
}
function cargaContacto(){

}
function cargaMiCuenta(){

}