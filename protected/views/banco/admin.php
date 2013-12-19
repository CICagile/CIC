<?php
/* @var $this BancoController */
/* @var $model Banco */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Bancos',
);

$this->menu=array(
	array('label'=>'Crear Banco', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('banco-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h3>Opciones de bancos</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'banco-grid',
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
