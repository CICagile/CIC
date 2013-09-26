<?php
/* @var $this ConvenioController */
/* @var $model Convenio */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Convenios'=>array('admin'),
        'Modificar'
);

$this->menu=array(
	array('label'=>'Crear Convenio', 'url'=>array('create')),
);
?>

<h1>Actualizar Convenio <?php echo $model->nombre; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>