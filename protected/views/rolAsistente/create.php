<?php
/* @var $this RolAsistenteController */
/* @var $model RolAsistente */

$this->breadcrumbs=array(
	'Opciones de roles de asistentes'=>array('rolasistente/admin'),
	'Crear un nuevo rol de asistentes',
);

$this->menu=array(
	array('label'=>'Ver opciones de roles de asistentes', 'url'=>array('admin')),
);
?>

<h3>Crear un nuevo rol de asistente</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>