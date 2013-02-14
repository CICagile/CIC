<?php
$model = new Asistente();
$this->breadcrumbs=array(
	'Asistente'=>array('index'),
	'Búsqueda',
);

$this->menu=array(
	array('label'=>'Registrar nuevo asistente', 'url'=>array('create')),
	
);
//Arreglo con las columnas que se mostrarán en el CGridView
$columns = array(
    array(
        'header'=>CHtml::encode('Carnet'),
        'name'=>'carnet',
    ),
    array(
        'header'=>CHtml::encode('Nombre'),
        'name'=>'nombre',
    ),
    array(
        'header'=>CHtml::encode('Primer Apellido'),
        'name'=>'apellido1',
    ),
    array(
        'header'=>CHtml::encode('Segundo Apellido'),
        'name'=>'apellido2',
    ),
    array(
        'header'=>CHtml::encode('Teléfono'),
        'name'=>'telefono',
    ),
    array(
        'header'=>CHtml::encode('Correo Electrónico'),
        'name'=>'correo',
    ),
    array(
        'class'=>'CButtonColumn',
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/updateDP", array("id"=>$data["carnet"]))',
        'deleteButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',),
   );

Yii::app()->clientScript->registerScript('search', "$('.search-button').click(function()
    {
	$('.search-form').toggle();
	return false;
    });
    $('.search-form form').submit(function(){
	$.fn.yiiGridView.update('asistente-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Lista de Asistentes</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$filtersForm,
	'columns'=>$columns,
       )); 
?>
