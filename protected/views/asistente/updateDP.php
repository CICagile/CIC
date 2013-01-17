<?php
/* @var $this AsistenteController */
/* @var $model Asistente */

$this->breadcrumbs=array(
	'Asistentes'=>array('index'),
	'Modificar datos personales',
);

$this->menu=array(
	array('label'=>'Registrar nuevo asistente', 'url'=>array('create')),
);
?>

<h1>Modificar información de <?php echo $model->nombre . ' ' . $model->apellido1 . ' ' . $model->apellido2 . ' (' . $model->carnet . ')'; ?></h1>

<?php echo $this->renderPartial('_formUpdateDatosPersonalesAsistente', array('model'=>$model)); ?>