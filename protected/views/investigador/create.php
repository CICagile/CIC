<?php
/* @var $this InvestigadorController */
/* @var $model Investigador */
/* @var $periodo Periodos */

$this->breadcrumbs=array(
	'Investigador'=>array('index'),
	'Registrar Nuevo Investigador',
);

$this->menu=array(
	array('label'=>'Ver investigadores', 'url'=>array('admin')),
);
?>

<h1>Registrar Nuevo Investigador</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'periodo'=>$periodo)); ?>