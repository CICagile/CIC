<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo,
);

$this->menu=array(
	array('label'=>'Ver Proyectos', 'url'=>array('admin')),
	array('label'=>'Nuevo Proyecto', 'url'=>array('create')),        
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
	/*array('label'=>'Delete Proyectos', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idtbl_Proyectos),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Proyectos', 'url'=>array('admin')),*/
);
?>

<h3>Detalle del proyecto.</h3>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'idtbl_Proyectos',
                'codigo',
		'nombre',		
		array(
                        'label' => $model->periodos->getAttributeLabel('inicio'),
                        'value' => $this->FechaMysqltoPhp($model->periodos->inicio),
                ),
                array(
                        'label' => $model->periodos->getAttributeLabel('fin'),
                        'value' => $this->FechaMysqltoPhp($model->periodos->fin),
                ),
                '_tipoproyecto.nombre',
                '_estadoproyecto.nombre',
                '_objetivoproyecto.nombre',
                '_adscrito.nombre'             
	),
)); 
      
?>



