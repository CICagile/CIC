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

<h1>Modificar información de <?php echo $nombre . ' ' . $apellido1 . ' ' . $apellido2 . ' (' . $carnet . ')'; ?></h1>

<?php echo $this->renderPartial('_formUpdateDatosPersonalesAsistente', array('model'=>$model)); ?>