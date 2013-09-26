<?php
/* @var $this AdscritoController */
/* @var $model Adscrito */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Adscritos'=>array('admin'),
        'Crear'
);

$this->menu=array(	
	array('label'=>'Ver opciones de adscripción', 'url'=>array('admin')),
);
?>

<h3>Crear nueva opción de adscripción.</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>