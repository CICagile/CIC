<?php
/* @var $this ObjetivoProyectoController */
/* @var $model ObjetivoProyecto */

$this->breadcrumbs=array(
	'Objetivos para proyectos'=>array('admin'),
	'Opciones',
);

$this->menu=array(	
	array('label'=>'Crear opción de objetivo para proyecto', 'url'=>array('create')),
        array('label'=>'Ver Proyectos', 'url'=>array('proyectos/admin'))
);

?>

<h3>Opciones de objetivos para proyectos.</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'objetivo-proyecto-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(		
		'nombre',
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{update}', 
                        'updateButtonLabel' => 'Modificar el nombre de la opción.',
		),
	),
)); ?>
