<?php
/* @var $this RolAsistenteController */
/* @var $model RolAsistente */

$this->breadcrumbs=array(
	'Gestión del Sistema'=>array('parametros/index'),
	'Opciones de roles de asistentes',
);

$this->menu=array(
	array('label'=>'Crear opcion de rol de asistente', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('rol-asistente-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h3>Opciones de roles de asistentes</h3>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'rol-asistente-grid',
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
