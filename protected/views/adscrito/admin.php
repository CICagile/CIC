<?php
/* @var $this AdscritoController */
/* @var $model Adscrito */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Adscritos',
);

$this->menu=array(
        array('label'=>'Crear opción de adscripción', 'url'=>array('create')),
	array('label'=>'Ver Proyectos', 'url'=>array('proyectos/admin'))	
);

?>
<h3>Opciones de Adscripción para proyectos.</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'adscrito-grid',
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
