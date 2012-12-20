<?php
/* @var $this PersonasController */
/* @var $model Personas */

$this->breadcrumbs=array(
	'Personas'=>array('index'),
	$model->idtbl_Personas=>array('view','id'=>$model->idtbl_Personas),
	'Update',
);

$this->menu=array(
	array('label'=>'Create Personas', 'url'=>array('create')),
	array('label'=>'Ver Personas', 'url'=>array('view', 'id'=>$model->idtbl_Personas)),
	array('label'=>'Búsqueda Personas', 'url'=>array('admin')),
);
?>

<h1>Update Personas <?php echo $model->idtbl_Personas; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>