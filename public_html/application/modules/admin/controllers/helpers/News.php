<?php

class Zend_Controller_Action_Helper_News extends Zend_Controller_Action_Helper_Abstract {

    private $news;
    private $view;

    public function __construct() {
        $this->view = Zend_Layout::getMvcInstance()->getView();
    }

    public function get() {
        $this->news=array(
                array(
                    'data' => '<div class="titulo">Mamá protegida,<br> bebé sano</div>
                        <div class="descripcion">A dos meses de su lanzamiento, el programa obtuvo buena respuesta de las mamás, que ya tienen sus libretas para controles, estudios, vacunas y medicamentos necesarios.</div>
                        <button class="btn btn-default full-width" onclick=\'location.href="'.$this->view->url(array('controller'=>'novedades','action'=>'ver','id'=>0),NULL,TRUE).'"\'>VER MÁS</button>',
                    'image' => HOST . $this->view->baseUrl() . '/files/images/novedades/card1_provi.jpg'
                ),
                array(
                    'data' => '<div class="titulo">Reunión de<br>Defensa Civil</div>
                        <div class="descripcion">Autoridades de Defensa Civil de distintos municipios se reunieron en San Isidro para intercambiar experiencias sobre la gestión de emergencias y planificar en conjunto.</div>
                        <button class="btn btn-default full-width" onclick=\'location.href="'.$this->view->url(array('controller'=>'novedades','action'=>'ver','id'=>0),NULL,TRUE).'"\'>VER MÁS</button>',
                    'image' => HOST . $this->view->baseUrl() . '/files/images/novedades/card2_provi.jpg'
                ),
                array(
                    'data' => '<div class="titulo">Seguridad vial en Boulogne</div>
                        <div class="descripcion">Para ordenar el tránsito en el barrio Santa Rita, se realizó una intervención vial en el cruce de las avenidas Avelino Rolón, Sucre y las calles Yerbal y Cura Allievi.<br><br></div>
                        <button class="btn btn-default full-width" onclick=\'location.href="'.$this->view->url(array('controller'=>'novedades','action'=>'ver','id'=>0),NULL,TRUE).'"\'>VER MÁS</button>',
                    'image' => HOST . $this->view->baseUrl() . '/files/images/novedades/card3_provi.jpg'
                )
            );
        return $this->news;
    }

}

?>
