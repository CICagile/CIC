<?php
/* @var $this ObjetivoProyectoController */
/* @var $model ObjetivoProyecto */

$this->breadcrumbs=array(
	'Objetivo para proyectos'=>array('admin'),
	'Crear',
);

$this->menu=array(	
	array('label'=>'Ver opciones de objetivos para proyectos', 'url'=>array('admin')),
);
?>

<h3>Crear nueva opción de objetivo para proyectos</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>