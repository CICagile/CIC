<?php
/* @var $this SectorBeneficiadoController */
/* @var $model SectorBeneficiado */

$this->breadcrumbs=array(
	'Sector Beneficiado'=>array('index'),
	'Ver',
);

$this->menu=array(
	array('label'=>'Create SectorBeneficiado', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sector-beneficiado-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Sector Beneficiado</h1>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sector-beneficiado-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'nombre',
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{update}',
                        'updateButtonUrl'=>'Yii::app()->controller->createUrl("sectorBeneficiado/update", array("id"=>$data["idtbl_sectorbeneficiado"]))',
                        'updateButtonLabel' => 'Actualizar información del Sector Beneficiado.',
		),
	),
)); ?>
