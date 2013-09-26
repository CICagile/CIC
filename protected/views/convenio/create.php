<?php
/* @var $this ConvenioController */
/* @var $model Convenio */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Convenios'=>array('admin'),
        'Crear'
);
?>

<h1>Crear Convenio</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>