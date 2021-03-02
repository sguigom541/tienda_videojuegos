<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/misFunciones")
 */
class FuncionesController extends AbstractController
{
    /**
     * @Route("/separar", name="separa_cadena")
     */
    public function separarCadena($array): string
    {
        $string = "01";

        if ($array != null) {
            //Dime cual es el ultimo valor del array
            $ultimoElemento = end($array);

            //Separame el ultimo elemento por donde este el barra baja "_"
            $array1 = explode("_", $ultimoElemento);

            //Separame el array de arriba por un punto "."
            $array2 = explode(".", $array1[1]);

            if (intval($array2[0]) < 9) {
                $numero = $array2[0] + 1;
                $string = strval("0" . $numero);
            } else {
                $string = $array2[0] + 1;
            }
        }

        return $string;
    }

    /**
     * @Route("/transformarFechas", name="transformarFechas_cadena")
     */
    public function transformarFechas($fecha): \DateTimeInterface
    {
        $arraySeparada = explode("-", $fecha);
        array_push($arraySeparada, "01");

        $fechaString = $arraySeparada[0] . "-" . $arraySeparada[1] . "-" . $arraySeparada[2] . " 00:00:00";

        $dt = new DateTime($fechaString);


        return $dt;
    }

    /**
     * @Route("/calculoPrecio", name="calculoPrecio_cadena")
     */
    public function calculoPrecio($descuentoProducto, $precio, $cantidad): float
    {
        $total = 0;
        if ($descuentoProducto > 0) {
            $descuento = ($precio * $descuentoProducto) / 100;
            $totalDescuneto = $precio - $descuento;
            $total = $totalDescuneto * $cantidad;
        } else {
            $total = $precio * $cantidad;
        }

        return $total;
    }

    /**
     * @Route("/calculoDescuento", name="calculoDescuento_cadena")
     */
    public function calculoDescuento($descuentoProducto, $precio): float
    {
        $totalDescuneto = 0;
        $descuento = ($precio * $descuentoProducto) / 100;
        $totalDescuneto = $precio - $descuento;


        return $totalDescuneto;
    }

    /**
     * @Route("/resizeImage", name="resizeImage")
     */
    function resizeImage($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    
        return $dst;
    }
}
