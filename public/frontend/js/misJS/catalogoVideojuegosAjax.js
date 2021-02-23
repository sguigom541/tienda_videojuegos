/*************************************************************************
 * 
 * PETICIONES AJAX CATALOGO VIDEOJUEGOS
 * 
 ************************************************************************/

function catalogoVideojuegos()
{

    /*Incorporo la plantilla*/
    var plantilla = $('<div class="col-lg-4 col-md-6">').load('plantillas/plantilla_catalogo_videojuegos.html');


    /*Hago la petición al servidor */
    $.ajax({
        type: "get",
        url: "api/videojuegos",
        data: "data",
        dataType: "json",
        success: function (data) {
            var videojuegos=data;

            for (let videojuego of videojuegos) 
            {
                let item=$(plantilla).clone();
                //ponemos el nombre del videojuego
                $(item).find(".titulo-videojuego").text(videojuego.nombre);
                //ponemos la imagen del videojuego
                $(item).find(".img-fluid").attr({'class':'img-fluid','alt':videojuego.plataforma,'src':videojuego.imgPrincipal})

                if((videojuego.descuento)>0)
                {
                    let precioVideojuego=videojuego.precio;
                    let descuentoVideojuego=videojuego.descuento;
                    var descuentoAplicado=(precioVideojuego * descuentoVideojuego) / 100;
                    var total=precioVideojuego - descuentoAplicado;
                    
                    $(item).find(".price").before("<span class='mr-2 price-dc'>" + precioVideojuego + "€</span>");
                    $(item).find(".price").text(total.toFixed(2) + "€");
                }
                else{
                    $(item).find(".price").text(videojuego.precio+ "€");
                }
                //Se limpia el listado de productos y se muestra el producto
                $(item).find(".detallesVideojuego").on("click",function(ev){
                    ev.preventDefault();
                    
                })
                $('#listadoVideojuegos').append(item);
            }


        }
    });

    



}

function menuVideojuegos(){
    var plantilla=$('<div class="checkbox">')
    $.ajax({
        //Metodo que se utiliza
        type: "get",
        //url de destino
        url: "api/plataformas",
        //tipo de dato que nos devuelve el servidor
        dataType: "json",
        success: function (data) {
            console.log(data);
            var plataformas=data;
            for (let plataforma of plataformas) {
                let item=plantilla.clone();
                let label=$('<label>');
                let checkbox=$('<input type="checkbox">');
                $(label).attr("for", plataforma.nombre).text(plataforma.nombre);
                $(checkbox).attr({"id":plataforma.id,"name":plataforma.nombre});
                item.append(label).append(checkbox);
                $("#filtrosBusqueda").append(item);
            }
        }
       
    });
}

function aplicarFiltros()
{
    $("#filtroPlataforma").on('click',function(ev){
        ev.preventDefault();
        
    })
}