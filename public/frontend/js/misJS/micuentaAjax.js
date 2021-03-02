

/*******************************************************************
 *
 * Ver mi cuenta cuando la sesión esté iniciada
 *
 *******************************************************************/

 function accionesCuenta(){
     $(".miCuenta").click(function(ev){
         ev.preventDefault();
         console.log(ev);
         $(".opcionesCuenta").empty();

         verMisDatos();
     });

     $(".misPedidos").click(function (ev) {
         ev.preventDefault();
        $(".opcionesCuenta").empty();
        verMisPedidos();
    });
 }

function verMisDatos() 
{
    var plantilla=$('<div class="box">').load("plantillas/plantilla_micuenta.html");
    //nos traemos los datos del usuario en sesión
    $.ajax({
        type: "post",
        url:"api/datosPersonales",
        success:function(misDatos)
        {
            //let itemUsuario=plantilla.clone();
            $(plantilla).find("input[name=email]").val(misDatos.email);

            $(plantilla).find("input[name=password]").val(misDatos.password);
            $(plantilla).find("input[name=nombre]").val(misDatos.nombre);
            $(plantilla).find("input[name=ape1]").val(misDatos.ape1);
            $(plantilla).find("input[name=ape2]").val(misDatos.ape2);
            $(plantilla).find("input[name=direccion]").val(misDatos.direccion);

            $(plantilla).find("#actualizaCuenta").click(function(ev){

                ev.preventDefault();
                console.log(ev);
               var formuSerializado=$("#formdatospersonales").serialize();
                $.ajax({
                    method: "POST",
                    url: "api/updateUsuario",
                    data: formuSerializado,
                    success:function(respuesta){
                        if (respuesta) {
                            alert("Sus datos han sido actualizados correctamente");
                        } else {
                            alert("Sus datos no han podido ser actualizados correctamente");
                        }
                    }

                })
            })

            $(".opcionesCuenta").append(plantilla);
        }
    })

}

function verMisPedidos(){
    var plantillaTablaMisPedidos=$('<div class="table-responsive divPedidosUsuarioEnSesion">')
    .load("plantillas/plantilla_tabla_pedidos.html");

    var pTrPedidos = $('<tr>')
        .load("plantillas/plantilla_tabla_td_misPedidos.html");

    $.ajax({
        type: "post",
        url: "api/datosPersonales",
        success: function (respuesta) {
            $(".apartadoUsuario").empty();
            var pedidos = respuesta.pedidos;
            let itemPedido = $(plantillaTablaMisPedidos).clone();

            //creamos un título
            var h3 = $("<h3 class='mb-2'>").text("INFORMACION DE LOS PEDIDOS");
            $(".apartadoUsuario").append(h3);

            //Creamos la tabla y sus filas
            $(".apartadoUsuario").append(itemPedido);
            for (let pedido of pedidos) {
                let item = $(pTrPedidos).clone();

                let fecha = separarFecha(pedido.fecha.date);
                $(item).find(".idPedido").text(pedido.id);
                $(item).find(".fechaPedido").text(fecha[2] + "/" + fecha[1] + "/" + fecha[0]);
                $(item).find(".pedidoCantidad").text(pedido.cantidad + " €");

                $(item).find(".btnFactura").click(function (e) {
                    alert("Saco la factura de ese pedido");
                });

                $('.pedidosCuerpoTabla').append(item);
            }
        }
    });
}