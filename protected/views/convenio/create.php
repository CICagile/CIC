<?php
/* @var $this ConvenioController */
/* @var $model Convenio */

$this->breadcrumbs=array(
	'Convenios'=>array('index'),
	'Crear',
);
?>

<h1>Crear Convenio</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>