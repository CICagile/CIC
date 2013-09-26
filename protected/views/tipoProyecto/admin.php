<?php
/* @var $this TipoProyectoController */
/* @var $model TipoProyecto */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Tipos de Proyecto',
);

$this->menu=array(
	array('label'=>'Crear opción de tipo de proyecto', 'url'=>array('create')),
	array('label'=>'Ver Proyectos', 'url'=>array('proyectos/admin')),
);

?>

<h3>Opciones de tipo de proyectos.</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tipo-proyecto-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(		
		'nombre',
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{update}', 
                        'updateButtonLabel' => 'Modificar el nombre de la opción.',
		)
	),
)); ?>
