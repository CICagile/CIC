<?php
//@var $this AsistenteController
  //@var $model Asistente;
$model = new Asistente();
$this->breadcrumbs=array(
	'Asistente'=>array('index'),
	'Búsqueda',
);

$this->menu=array(
	array('label'=>'Registrar nuevo asistente', 'url'=>array('create')),
	
);

$columns = array(
    array(
        'header'=>CHtml::encode('carnet'),
        'name'=>'carnet',
    ),
    array(
        'header'=>CHtml::encode('nombre'),
        'name'=>'nombre',
    ),
    array(
        'header'=>CHtml::encode('apellido1'),
        'name'=>'apellido1',
    ),
    array(
        'header'=>CHtml::encode('apellido2'),
        'name'=>'apellido2',
    ),
    array(
        'header'=>CHtml::encode('cedula'),
        'name'=>'cedula',
     ),     
    array(
        'header'=>CHtml::encode('numerocuenta'),
        'name'=>'numerocuenta',
    ),
    array(
        'header'=>CHtml::encode('cuentacliente'),
        'name'=>'cuentacliente',
    ),
    array(
        'header'=>CHtml::encode('telefono'),
        'name'=>'telefono',
    ),
    array(
        'header'=>CHtml::encode('correo'),
        'name'=>'correo',
    ),
    array(
        'class'=>'CButtonColumn',
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("view"=>$data["carnet"]))',
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/updateDP", array("id"=>$data["carnet"]))',
        'deleteButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("view"=>$data["carnet"]))',),
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

<h1>Busqueda</h1>

<p>
<!--
Opcionalmente ud puede ingresar comparadores (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
ó <b>=</b>) al principio de cada valor de búsqueda.
!-->
</p>

<?php /*echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); */?>
<div class="search-form" style="display:none">
<?php ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>$columns,
           )); ?>
