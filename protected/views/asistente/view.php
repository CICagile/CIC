<?php
/* @var $this AsistenteController */
/* @var $model Asistente */

$this->breadcrumbs=array(
	'Asistente'=>array('admin'),
	$model->nombre." ".$model->apellido1." ".$model->apellido2,
);

$this->menu=array(
        array('label'=>'Ver Asistentes', 'url'=>array('admin')),	
	array('label'=>'Actualizar información del asistente', 'url'=>array('updateDP','id'=>$model->carnet)),	
	
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

<h3>Información del asistente</h3>

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
                'correo',
                'carnet',
                'carrera',
	),
));
?>
<br/>
<br/>
<h3>Proyectos asociados</h3>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$model->verProyectos(),
	'columns'=>$columns,
       ));
?>
