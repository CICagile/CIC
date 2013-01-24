<?php
/* @var $this PersonaController */
/* @var $model Persona */
/* @var $periodo Periodos */

$this->breadcrumbs=array(
	'Asistentes'=>array('index'),
	'Registrar Nuevo Asistente',
);

$this->menu=array(
	array('label'=>'Ver información de asistentes', 'url'=>array('admin')),
);
?>

<h1>Registrar Nuevo Asistente</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'periodo'=>$periodo)); ?>
