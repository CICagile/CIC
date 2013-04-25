<?php
/* @var $this ConvenioController */
/* @var $model Convenio */

$this->breadcrumbs=array(
	'Convenios'=>array('index'),
	$model->nombre=>array('view','id'=>$model->idtbl_convenio),
	'Actualizar',
);

$this->menu=array(
	array('label'=>'Crear Convenio', 'url'=>array('create')),
);
?>

<h1>Actualizar Convenio <?php echo $model->nombre; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>