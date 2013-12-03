<?php
/* @var $this UsuarioController */
/* @var $model Usuario */

$this->breadcrumbs=array(
	'Usuarios'=>array('index'),
	$model->idtbl_usuario=>array('view','id'=>$model->idtbl_usuario),
	'Update',
);

$this->menu=array(
	array('label'=>'List Usuario', 'url'=>array('index')),
	array('label'=>'Create Usuario', 'url'=>array('create')),
	array('label'=>'View Usuario', 'url'=>array('view', 'id'=>$model->idtbl_usuario)),
	array('label'=>'Manage Usuario', 'url'=>array('admin')),
);
?>

<h1>Update Usuario <?php echo $model->idtbl_usuario; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>