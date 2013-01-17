<?php
/* @var $this PersonaController */
/* @var $model Persona */

$this->breadcrumbs=array(
	'Asistentes'=>array('index'),
	'Registrar Nuevo Asistente',
);

$this->menu=array(
	array('label'=>'Modificar datos de un asistente', 'url'=>array('update')),
);
?>

<h1>Registrar Nuevo Asistente</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>