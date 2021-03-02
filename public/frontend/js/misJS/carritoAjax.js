/*************************************************************************
 *
 * PETICIONES AJAX PARTE DEL CARRITO DEL FRONTEND
 *
 ************************************************************************/
$(document).ready(function () {
    cCarrito();
});


function carritoPagina() {
    var autenticado = $('.userInSesion').data('user');
    if(autenticado){
        showCarritoBD();
    }
    
}


function cCarrito() {
    var autenticado = $('.userInSesion').data('user');
    
    if(autenticado){
        $.ajax({
            method: "get",
            url: "api/cuentaCarrito",
            success: function (response) {
                if (response > 0) {
                    $(".cCarrito").text(response);
                } else {
                    $(".cCarrito").text(0);
                }
            }
        });
    }

}
/*************************************************************************
 *
 * PETICIONES AJAX PARTE DEL CARRITO DEL FRONTEND USUARIO LOGUEADO
 *
 ************************************************************************/







//Añado un nuevo producto al carrito
function addCarritoBD(cantidadProducto, idVideojuego) {

    var dataSerialize = "cantidadElegida=" + cantidadProducto + "&idVideojuego=" + idVideojuego;

    $.ajax({
        type: "post",
        url: "api/addCarrito",
        data: dataSerialize
    });
}

//Actualizo el carrito desde el carrito
function updateCarritoBD(cantidadProducto, idVideojuego) {

    var dataSerialize = "cantidadElegida=" + cantidadProducto + "&idVideojuego=" + idVideojuego;

    $.ajax({
        type: "post",
        url: "api/updateCarrito",
        data: dataSerialize
    });
}

//Elimino un producto del carrito
function deleteCarritoBD(idVideojuego, elemento) {

    var dataSerialize = "idVideojuego=" + idVideojuego;

    $.ajax({
        type: "post",
        url: "api/deleteCarrito",
        data: dataSerialize,
        success: function (respuesta) {

            if (respuesta) {
                elemento.parent().parent().remove();
                cuentaFactura();
                cCarrito();

                if ($(".cuerpoTablaCarrito").children().length == 0) {
                    $(".btnPagar").attr({ "disabled": true });
                }

            } else {
                alert("fallo");

            }
        }
    });
}


function showCarritoBD()
{
    var plantilla = $('<tr>').load("plantillas/plantilla_td_carrito.html");

    $.ajax({
        type: "post",
        url: "api/showCarrito",

        success: function (respuesta) 
        {
            if(respuesta.length>0)
            {
                for (let i = 0; i < respuesta.length; i++) 
                {
                    let item = $(plantilla).clone();
                    $(item).find(".carritoNombreVideojuego").text(respuesta[i].nombre);
                    $(item).find(".carritoUnidadVideojuego").val(respuesta[i].cantidadElegida);
                    $(item).find(".carritoPrecioVideojuego").text(respuesta[i].precio + " €");
                    $(item).find(".carritoDescuentoProducto").text(respuesta[i].descuento + " %");

                    var img = $("<img>").attr({
                        "src": respuesta[i].foto,
                        "alt": "Videojuego" + respuesta[i].id
                    });
                    $(img).css({ "width": "70px", "height": "70px", "class": "d-block" });
                    $(item).find(".carritoNombreVideojuego").after(img);

                    let pProducto = cuentaProducto(respuesta[i].descuento, respuesta[i].precio, respuesta[i].cantidadElegida);
                    $(item).find(".carritoTotalVideojuego").text(pProducto + " €");

                    //Cambia la cantidad de producto que quiero comprar y me aumenta o disminuye su precio

                    var proQty = $(item).find('.divVideojuego');
                    proQty.prepend('<span class="dec qtybtn">-</span>');
                    proQty.append('<span class="inc qtybtn">+</span>');
                    proQty.on('click', '.qtybtn', function () 
                    {
                        var $button = $(this);
                        var oldValue = $button.parent().find('input').val();
                        if ($button.hasClass('inc')) 
                        {
                            var newVal = parseFloat(oldValue) + 1;

                        } else {

                            if (oldValue > 0) {
                                var newVal = parseFloat(oldValue) - 1;
                            } else {
                                newVal = 0;
                            }
                        }

                        if (newVal <= respuesta[i].cantidad) {
                            $button.parent().find('input').val(newVal);
                            let totalCalculado = cuentaProducto(respuesta[i].descuento, respuesta[i].precio, newVal)
                            $(item).find(".carritoTotalVideojuego").text(totalCalculado + " €");
                            updateCarritoBD(newVal, respuesta[i].id)
                            cuentaFactura()
                        }
                    });
                    //Para cuando pierdo el foco del input
                    $(item).find('.carritoUnidadVideojuego').blur(function () {
                        let totalCalculado = 0;
                        if ($(this).val() > respuesta[i].cantidad) {
                            $(this).val(respuesta[i].cantidad);
                            totalCalculado = cuentaProducto(respuesta[i].descuento, respuesta[i].precio, $(this).val())
                            updateCarritoBD($(this).val(), respuesta[i].id)
                            cuentaFactura()

                        } else if ($(this).val() < 0) {
                            $(this).val(0);
                            totalCalculado = cuentaProducto(respuesta[i].descuento, respuesta[i].precio, $(this).val())
                            updateCarritoBD($(this).val(), respuesta[i].id)
                            cuentaFactura()

                        } else {
                            totalCalculado = cuentaProducto(respuesta[i].descuento, respuesta[i].precio, $(this).val())
                            updateCarritoBD($(this).val(), respuesta[i].id)
                            cuentaFactura()
                        }

                        $(item).find(".carritoTotalVideojuego").text(totalCalculado + " €");

                        // eliminar tabla del carrito



                    })
                    $(item).find(".eliminaFilaVideojuego").click(function (ev) {
                        ev.preventDefault();
                        //console.log(ev);
                        deleteCarritoBD(respuesta[i].id, $(this));

                    });
                    $(".cuerpoTablaCarrito").append(item);
                }
                cuentaFactura()
                $(".btnPagar").attr({ "disabled": false });

                $(".btnPagar").click(function () {
                    pagar();

                });
            }
        }
    });
}






function cuentaProducto(descuentoProducto, precio, cantidad) {
    let total = 0;
    if (descuentoProducto > 0) {
        let descuento = (precio * descuentoProducto) / 100;
        let totalDescuneto = precio - descuento;
        total = totalDescuneto * cantidad;
    } else {
        total = precio * cantidad;

    }
    return total.toFixed(2);
}




function cuentaFactura() {
    let facturaTotal = 0;
    for (let item of $(".carritoTotalProducto")) {
        let cantidad = $(item).text().split(" ");
        facturaTotal += (cantidad[0] - 0);

    }

    $(".facturaTotal").text(facturaTotal.toFixed(2) + " €");
}