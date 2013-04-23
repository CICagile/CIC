<?php
/* @var $this AsistenteController */
/* @var $model Asistente */

$this->breadcrumbs=array(
	'Asistentes'=>array('admin'),
	'Modificar datos personales',
);

$this->menu=array(
        array('label'=>'Ver Asistentes', 'url'=>array('admin')),	
);
?>

<h1>Modificar información de <?php echo $nombre . ' ' . $apellido1 . ' ' . $apellido2 . ' (' . $carnet . ')'; ?></h1>

<?php echo $this->renderPartial('_formUpdateDatosPersonalesAsistente', array('model'=>$model)); ?>