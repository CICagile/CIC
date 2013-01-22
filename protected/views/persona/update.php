<?php
/* @var $this PersonaController */
/* @var $model Persona */

$this->breadcrumbs=array(
	'Personas'=>array('index'),
	$model->idtbl_Personas=>array('view','id'=>$model->idtbl_Personas),
	'Update',
);

$this->menu=array(
	array('label'=>'List Persona', 'url'=>array('index')),
	array('label'=>'Create Persona', 'url'=>array('create')),
	array('label'=>'View Persona', 'url'=>array('view', 'id'=>$model->idtbl_Personas)),
	array('label'=>'Manage Persona', 'url'=>array('admin')),
);
?>

<h1>Update Persona <?php echo $model->idtbl_Personas; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>