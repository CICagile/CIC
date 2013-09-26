<?php
/* @var $this ObjetivoProyectoController */
/* @var $model ObjetivoProyecto */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Objetivos de Proyectos'=>array('admin'),
        'Modificar'
);

$this->menu=array(
	array('label'=>'Ver opciones de objetivos para proyectos', 'url'=>array('admin')),
);
?>

<h3>Modificar opción de objetivo para proyecto</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>