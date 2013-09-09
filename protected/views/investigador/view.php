<?php
/* @var $this InvestigadorController */
/* @var $model investigador */

$this->breadcrumbs=array(
	'Investigador'=>array('admin'),
	$model->nombre." ".$model->apellido1." ".$model->apellido2,
);

$this->menu=array(
        array('label'=>'Ver Investigadores', 'url'=>array('admin')),	
	
	
);
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
 );
?>

<h3>Información del Investigador</h3>

<?php 
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
                'cedula',
                'nombre',
                'apellido1',
                'apellido2',
                'telefono',
                'correo',
                'experiencia',
                'ingreso',
                
	),
));
?>
<br/>
<br/>
<h3>Proyectos asociados</h3>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'investigador-grid',
	'dataProvider'=>$model->buscarProyectosActuales($model->cedula),
	'columns'=>$columns,
       ));
?>


