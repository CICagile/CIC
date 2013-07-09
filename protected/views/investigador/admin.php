<?php
$model = new Investigador();
$this->breadcrumbs=array(
	'Investigadores'=>array('admin'),
	'Lista de Investigadores',
);

$this->menu=array(
	array('label'=>'Registrar nuevo investigador', 'url'=>array('create')),
	
);
//Arreglo con las columnas que se mostrarán en el CGridView
$columns = array(
    array(
        'header'=>CHtml::encode('Cedula'),
        'name'=>'Cedula',
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
        'template'=>'{view}{update}',
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Investigador/view", array("id"=>$data["Cedula"]))',
        'viewButtonLabel' => 'Ver información detallada del investigador.',
    ),
   );

Yii::app()->clientScript->registerScript('search', "$('.search-button').click(function()
    {
	$('.search-form').toggle();
	return false;
    });
    $('.search-form form').submit(function(){
	$.fn.yiiGridView.update('investigador-grid', {
		data: $(this).serialize()
	});
	return false;
});
");?>

<h1>Lista de Investigadores</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'investigador-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$filtersForm,
	'columns'=>$columns,
       )); 
?>
