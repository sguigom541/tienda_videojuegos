/*************************************************************************
 *
 * PETICIONES AJAX CATALOGO VIDEOJUEGOS
 *
 ************************************************************************/

function listadoVideojuegos() {
    /*Incorporo la plantilla*/
    var plantilla = $('<div class="col-md-6">');
    plantilla.load('plantillas/plantilla_listado_productos.html');
    /**
     * hago la peticion al servidor
     */

    $.ajax({
        type: "get",
        url: "api/videojuegos",
        data: "data",
        dataType: "json",
        success: function (data) {
            var videojuegos = data;
            const url = $('.listadoVideojuegos').attr('data-url');
            for (let videojuego of videojuegos) {
                let item = $(plantilla).clone();
                //ponemos el nombre del videojuego
                $(item).find(".producto-titulo").text(videojuego.nombre);
                //ponemos la imagen del videojuego
                $(item).find(".producto-imagen").click(function (ev) {
                    ev.preventDefault();
                    verProducto(videojuego.id);
                })
                $(item).find(".img-videojuego").attr({
                    'alt': videojuego.plataforma,
                    'src': url + videojuego.nombre + '/imagenPrincipal/' + videojuego.imgPrincipal
                })
                if ((videojuego.descuento) > 0) {
                    let precioVideojuego = videojuego.precio;
                    let descuentoVideojuego = videojuego.descuento;
                    var descuentoAplicado = (precioVideojuego * descuentoVideojuego) / 100;
                    var total = precioVideojuego - descuentoAplicado;

                    $(item).find(".producto-precio").before("<span class='mr-2 price-dc'>" + precioVideojuego + "€</span>");
                    $(item).find(".producto-precio").text(total.toFixed(2) + "€");
                } else {
                    $(item).find(".producto-precio").text(videojuego.precio + "€");
                }
                $('.listadoVideojuegos').append(item);
            }
        }
    })
}


function catalogoVideojuegos() {

    /*Incorporo la plantilla*/
    var plantilla = $('<div class="col-lg-4 col-md-6">').load('plantillas/plantilla_catalogo_videojuegos.html');


    /*Hago la petición al servidor */
    $.ajax({
        type: "get",
        url: "api/videojuegos",
        data: "data",
        dataType: "json",
        success: function (data) {
            console.log(data)
            var videojuegos = data;
            const url = $('#listadoVideojuegos').attr('data-url');
            for (let videojuego of videojuegos) {
                let item = $(plantilla).clone();
                //ponemos el nombre del videojuego
                $(item).find(".titulo-videojuego").text(videojuego.nombre);
                //ponemos la imagen del videojuego
                //console.log(url+videojuego.nombre+'/imagenPrincipal/'+videojuego.imgPrincipal);
                $(item).find(".img-fluid").attr({
                    'class': 'img-fluid',
                    'alt': videojuego.plataforma,
                    'src': url + videojuego.nombre + '/imagenPrincipal/' + videojuego.imgPrincipal
                })
                if ((videojuego.descuento) > 0) {
                    let precioVideojuego = videojuego.precio;
                    let descuentoVideojuego = videojuego.descuento;
                    var descuentoAplicado = (precioVideojuego * descuentoVideojuego) / 100;
                    var total = precioVideojuego - descuentoAplicado;

                    $(item).find(".price").before("<span class='mr-2 price-dc'>" + precioVideojuego + "€</span>");
                    $(item).find(".price").text(total.toFixed(2) + "€");
                } else {
                    $(item).find(".price").text(videojuego.precio + "€");
                }
                if (videojuego.stock > 0) {
                    $(item).find(".texto-videojuego").append($('<div>').attr({'class': 'ribbon new'}).append($('<div>').attr({'class': 'theribbon'}).text('Disponible')).append($('<div>').attr({'class': 'ribbon-background'})));
                } else {
                    $(item).find(".texto-videojuego").append($('<div>').attr({'class': 'ribbon agotado'}).append($('<div>').attr({'class': 'theribbon'}).text('Agotado')).append($('<div>').attr({'class': 'ribbon-background'})));
                }
                //Se limpia el listado de productos y se muestra el producto
                $(item).find(".detallesvideojuego").click(function (ev) {
                    ev.preventDefault();
                    verProducto(videojuego.id);
                })
                $('#listadoVideojuegos').append(item);
            }


        }
    });


}

function verProducto(idProducto) {
    $("#content").empty();
    var id = "id=" + idProducto;

    //mando un ajax
    $.ajax({
        method: "get",
        url: "api/verVideojuego",
        data: id,
        success: function (response) {
            $("#content").append(response);
            productoElegido();
        }
    })
}

function productoElegido() {
    $('cuerpoVideojuegoElegido').empty();
    var objVideojuego = $(".typeVideojuego").data('videojuego')
    //var array=objVideojuego.imagenes;
    //console.log(array);
    var arrayFotos = $(".typeVideojuego").data('fotosVideojuego');
    //console.log(arrayFotos);
    var urlFotos = $(".typeVideojuego").data('url');
    //console.log(objVideojuego);
    var plantillaVideojuego = $('<div id="productMain" class="row">')
    $('.divVideojuego').load('/plantillas/plantilla_videojuego_elegido.html', function (data) 
    {

        plantillaVideojuego.append(data);

        let itemPlantilla = plantillaVideojuego.clone();

        $("#contenedorProducto").append(itemPlantilla);
        //url+videojuego.nombre+'/imagenPrincipal/'+videojuego.imgPrincipal
        var plantillaImagen = $('<div>').load('/plantillas/divImagen.html');
        plantillaImagen = plantillaImagen.attr('class', 'item');


        /*for (let i = 2; i < arrayFotos.length; i++) {
            let clon=plantillaImagen.clone();
            clon.find('img-fluid').attr('src',arrayFotos[i])
            $('.clon').append(clon);
            
        }*/

        /**
         * parte de las fotos
         */
        for (let i = 2; i < arrayFotos.length; i++) {

            let divClone = $('#clonar').clone().appendTo('.clon');
            let btnClone = $('#clonarThumbs').clone().appendTo('.imgPequenia');
            $(divClone).attr("hidden", false);
            $(btnClone).attr("hidden", false);
            let imgClone = $('<img class="img-fluid">')
            $(imgClone).attr({
                "src": arrayFotos[i],
                'alt': objVideojuego.id,
            })
            let imgClone2 = $('<img class="img-fluid">')
            $(imgClone2).attr({
                "src": arrayFotos[i],
                'alt': objVideojuego.id,
            })
            $(divClone).append(imgClone2);
            $(btnClone).append(imgClone);
        }

        $('#clonar')[0].remove();
        /**
         * parte de los textos
         */

        $('.detallesProducto').text(objVideojuego.descripcion);
        if (objVideojuego.descuento > 0) {
            var totalSub = (objVideojuego.precio * objVideojuego.descuento) / 100;
            var total = objVideojuego.precio - totalSub;

            var pDescuento = $('<p>').text("-" + objVideojuego.descuento + " %");
            var pDescuentoPrecio = $('<p>').text(total.toFixed(2) + " €");
            $(".price").before(pDescuentoPrecio);
            $(".price").after(pDescuento);
            var tachado = $("<s>").text(objVideojuego.precio + " €");
            $(".price").append(tachado);

            $(".price").addClass("mx-4 ");
        } else {
            $(".price").text(objVideojuego.precio + " €");
        }

        if (objVideojuego.stock > 20) {
            $(".estadoDisponibilidad").css("color", "green");
        } else if (objVideojuego.stock > 0 && objVideojuego.stock < 21) {
            $(".estadoDisponibilidad").css("color", "orange");
        } else if (objVideojuego.stock == 0) {
            $(".estadoDisponibilidad").css("color", "red");
            $(".addCesta").attr({"disabled": true});
            $('.divProducto').hide();
            $(".price").hide();

        }

        $('.shop-detail-carousel').owlCarousel({
            items: 1,
            thumbs: true,
            nav: false,
            dots: false,
            loop: true,
            autoplay: true,
            thumbsPrerendered: true
        });

        //Cambia la cantidad de producto que quiero comprar
        var proQty = $('.divProducto');
        proQty.prepend('<span class="dec qtybtn">-</span>');
        proQty.append('<span class="inc qtybtn">+</span>');
        proQty.on('click', '.qtybtn', function () {
            var $button = $(this);
            var oldValue = $button.parent().find('input').val();
            if ($button.hasClass('inc')) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                // Don't allow decrementing below zero
                if (oldValue > 0) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 0;
                }
            }

            if (newVal <= objVideojuego.cantidad) {
                $button.parent().find('input').val(newVal);
            }

        });
       //Para cuando pierdo el foco del input
       $(itemPlantilla).find('.cantidadCesta').blur(function () {
        if ($(this).val() > objVideojuego.cantidad) {
            $(this).val(objVideojuego.cantidad);


        } else if ($(this).val() < 0) {
            $(this).val(0);


        }

        //Añadir a la cesta
        $(".addCesta").click(function (e) {
            e.preventDefault();

            let cantidadProducto = $(".cantidadCesta").val();

            var autenticado = $('.userInSesion').data('user');
            if(autenticado)
            {
                addCarritoBD(cantidadProducto, objVideojuego.id);
            }
            cCarrito();
        });
    });
    });

}

function menuVideojuegos() {
    var plantilla = $('<ul class="default-widget-categorias">');
    $.ajax({
        //Metodo que se utiliza
        type: "get",
        //url de destino
        url: "api/plataformas",
        //tipo de dato que nos devuelve el servidor
        dataType: "json",
        success: function (data) {
            var plataformas = data;
            for (let plataforma of plataformas) {
                var item = $("<li>");
                var enlace=$("<a>").clone();
                $(enlace).attr({"href":"#","id":plataforma.id}).text(plataforma.nombre);
                $(enlace).click(function(ev){
                    ev.preventDefault();
                   filtroVideojuegos($(this).attr('id'));
                })
                item.append(enlace);
                $(".default-widget-categorias").append(item);
            }
        }

    })
}

function filtroVideojuegos(id)
{
    /*Incorporo la plantilla*/
    var plantilla = $('<div class="col-md-6">');
    plantilla.load('plantillas/plantilla_listado_productos.html');
    $.ajax({
        //Metodo que se utiliza
        type: "get",
        //url de destino
        url: "api/videojuegos/plat/"+id,

        //tipo de dato que nos devuelve el servidor
        dataType: "json",
        success: function (data) {
            console.log(this.url);
            $('.listadoVideojuegos').empty();
            var videojuegos = data;
            const url = $('.listadoVideojuegos').attr('data-url');
            for (let videojuego of videojuegos) {
                let item = $(plantilla).clone();
                //ponemos el nombre del videojuego
                $(item).find(".producto-titulo").text(videojuego.nombre);
                //ponemos la imagen del videojuego
                $(item).find(".producto-imagen").click(function (ev) {
                    ev.preventDefault();
                    verProducto(videojuego.id);
                })
                $(item).find(".img-videojuego").attr({
                    'alt': videojuego.plataforma,
                    'src': url + videojuego.nombre + '/imagenPrincipal/' + videojuego.imgPrincipal
                })
                if ((videojuego.descuento) > 0) {
                    let precioVideojuego = videojuego.precio;
                    let descuentoVideojuego = videojuego.descuento;
                    var descuentoAplicado = (precioVideojuego * descuentoVideojuego) / 100;
                    var total = precioVideojuego - descuentoAplicado;

                    $(item).find(".producto-precio").before("<span class='mr-2 price-dc'>" + precioVideojuego + "€</span>");
                    $(item).find(".producto-precio").text(total.toFixed(2) + "€");
                } else {
                    $(item).find(".producto-precio").text(videojuego.precio + "€");
                }
                $('.listadoVideojuegos').append(item);
            }
        }
    })
}

/*function menuVideojuegos2() {
    var plantilla = $('<div class="checkbox">')
    $.ajax({
        //Metodo que se utiliza
        type: "get",
        //url de destino
        url: "api/plataformas",
        //tipo de dato que nos devuelve el servidor
        dataType: "json",
        success: function (data) {
            var plataformas = data;
            for (let plataforma of plataformas) {
                let item = plantilla.clone();
                let label = $('<label>');
                let checkbox = $('<input type="checkbox">');
                $(label).attr("for", plataforma.nombre).text(plataforma.nombre);
                $(checkbox).attr({"id": plataforma.id, "name": plataforma.nombre});
                item.append(label).append(checkbox);
                $("#filtrosBusqueda").append(item);
            }
        }

    });
}*/

function aplicarFiltros() {
    $("#filtroPlataforma").on('click', function (ev) {
        ev.preventDefault();

    })
}