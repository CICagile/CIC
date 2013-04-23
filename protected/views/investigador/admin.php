<?php
//$model = new Asistente();
$this->breadcrumbs=array(
	'Investigadores'=>array('admin'),
	'Lista de Investigadores',
);

$this->menu=array(
	array('label'=>'Registrar nuevo asistente', 'url'=>array('create')),
	
);
/*/Arreglo con las columnas que se mostrarán en el CGridView
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
        'template'=>'{view}{update}',
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',
        'viewButtonLabel' => 'Ver información detallada del asistente.',
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/updateDP", array("id"=>$data["carnet"]))',
        'updateButtonLabel' => 'Actualizar información del asistente.',
    ),
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
*/?>

<!--h1>Lista de Asistentes</h1--!>

<?php /*$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$filtersForm,
	'columns'=>$columns,
       )); */
?>
