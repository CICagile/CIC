<?php
/* @var $this ConvenioController */
/* @var $model Convenio */

$this->breadcrumbs=array(
	'Convenios'=>array('index'),
	'Ver',
);

$this->menu=array(
	array('label'=>'Crear Convenio', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('convenio-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Convenios</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'convenio-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'nombre',
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{update}',
                        'updateButtonUrl'=>'Yii::app()->controller->createUrl("convenio/update", array("id"=>$data["idtbl_convenio"]))',
                        'updateButtonLabel' => 'Actualizar información del Convenio.',
		),
	),
)); ?>
