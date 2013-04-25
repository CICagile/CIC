<?php
/* @var $this PersonasController */
/* @var $model Personas */

$this->breadcrumbs=array(
	'Personas'=>array('index'),
	'Búsqueda',
);

$this->menu=array(
	array('label'=>'Create Personas', 'url'=>array('create')),
	
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('personas-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Búsqueda</h1>

<p>
<!--
Opcionalmente ud puede ingresar comparadores (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
ó <b>=</b>) al principio de cada valor de búsqueda.
!-->
</p>

<?php /*echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); */?>
<div class="search-form" style="display:none">
<?php /*$this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'personas-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		/*'idtbl_Personas',*/
		'nombre',
		'apellido1',
		'apellido2',
		'cedula',
		'numerocuenta',
		'cuentacliente',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
