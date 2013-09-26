<?php
/* @var $this AdscritoController */
/* @var $model Adscrito */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Adscritos'=>array('admin'),
        'Modificar'
);

$this->menu=array(	
	array('label'=>'Ver opciones de adscripción', 'url'=>array('admin')),
);
?>

<h3>Modificar opción de adscripción</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>