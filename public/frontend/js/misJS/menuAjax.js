$(document).ready(function () {
    inicio();
    cargaCatalogo();
    cargaContacto();
    cargaMiCuenta();
    cargaRegistroLogin();
});

function inicio() {
    $(".inicio").click(function () {
        $("#content").empty();

        $.ajax({
            method: "get",
            url: "api/inicio",
            success: function (response) {
                $("#content").append(response);
            }
        });
    });
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
                listadoVideojuegos();

            }
        });

    });
}
function cargaContacto(){
    $(".contacto").click(function () {
        $("#content").empty();

        $.ajax({
            method: "get",
            url: "api/contacto",
            success: function (response) {
                $("#content").append(response);
            }
        });
    });
}
function cargaMiCuenta(){
    $(".miCuenta").click(function () {
        $("#content").empty();
        $.ajax({
            method:"get",
            url: "api/miCuenta",
            success:function(response){
                $("#content").append(response);
                accionesCuenta();
                verMisDatos();
            }
        })

    });
}

function cargaRegistroLogin(){
    $(".registro").click(function(){
        $("#content").empty();

        $.ajax({
            method:"get",
            url: "api/registrologin",
            success:function(response){
                $("#content").append(response);
            }
        })
    })
}