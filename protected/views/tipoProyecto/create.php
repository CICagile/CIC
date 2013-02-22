<?php
/* @var $this TipoProyectoController */
/* @var $model TipoProyecto */

$this->breadcrumbs=array(
	'Tipo de proyectos'=>array('admin'),
	'Crear',
);

$this->menu=array(	
	array('label'=>'Ver opciones de tipo de proyectos', 'url'=>array('admin')),
);
?>

<h3>Crear nueva opción de tipo de proyectos</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>