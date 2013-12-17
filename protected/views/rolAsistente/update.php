<?php
/* @var $this RolAsistenteController */
/* @var $model RolAsistente */

$this->breadcrumbs=array(
	'Opciones de roles de asistentes'=>array('rolasistente/admin'),
	'Modificar',
);

$this->menu=array(
	array('label'=>'Ver opciones de tipo de asistente', 'url'=>array('admin')),
);
?>

<h3>Modificar opción de tipo de asistente</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>