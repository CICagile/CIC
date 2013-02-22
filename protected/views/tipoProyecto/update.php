<?php
/* @var $this TipoProyectoController */
/* @var $model TipoProyecto */

$this->breadcrumbs=array(
	'Tipo de Proyectos'=>array('admin'),	
	'Modificar',
);

$this->menu=array(
	array('label'=>'Ver opciones de tipo de proyectos', 'url'=>array('admin')),
);
?>

<h3>Modificar opción de tipo de proyecto</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>