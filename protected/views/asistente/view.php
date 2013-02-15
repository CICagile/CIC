<?php
/* @var $this AsistenteController */
/* @var $model Asistente */

$this->breadcrumbs=array(
	'Asistente'=>array('index'),
	$model->nombre." ".$model->apellido1." ".$model->apellido2,
);

$this->menu=array(
	array('label'=>'Create Asistente', 'url'=>array('create')),
	array('label'=>'Update Asistente', 'url'=>array('update','id'=>$model->carnet)),
	array('label'=>'Delete Asistente', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->carnet),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Buscar Asistente', 'url'=>array('admin')),
);
//Columnas para mostrar todos los proyectos relacionados con un asistente
$columns = array(
    array(
        'header'=>CHtml::encode('Código'),
        'name'=>'idtbl_proyectos',
        'type'=>'raw',
        'value'=>'CHtml::link($data["codigo"], CHtml::normalizeUrl(array("/proyectos/","ver" => $data["idtbl_proyectos"])))',
        ),
    array(
        'header'=>CHtml::encode('Nombre'),
        'name'=>'nombre',
    ),
   array(
        'header'=>CHtml::encode('Horas'),
        'name'=>'horas',
    ),
   );
?>

<h1>Ver Asistentes</h1>

<?php 
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'nombre',
                'apellido1',
                'apellido2',
                'cedula',
                'numerocuenta',
                'banco',
                'cuentacliente',
                'telefono',
                'correo'
	),
));
?>
</br><center><h2> Proyectos en los que trabaja: </h2></center>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$model->proyectosasistente($model->carnet),
	'columns'=>$columns,
       ));
?>

