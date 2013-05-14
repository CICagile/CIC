<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $dataProvider CArrayDataProvider */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo,
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Ver Proyectos', 'url'=>array('admin')),
        
);


?>

<h3>Detalle del proyecto.</h3>

<?php
$sectores_beneficiados = Proyectos::listFormatBenefitedSectors($model->idtbl_sectorbeneficiado);
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(            
                'codigo',
		'nombre',
                'estado',
		array(
                        'label' => 'Fecha Inicio',
                        'value' => $this->FechaMysqltoPhp($model->inicio),
                ),
                array(
                        'label' => 'Fecha finalización',
                        'value' => $this->FechaMysqltoPhp($model->fin),
                ),
                '_tipoproyecto.nombre',                
                '_objetivoproyecto.nombre',
                '_adscrito.nombre',
            array(
                'label' => 'Sector(es) beneficiado(s)',
                'value' => $sectores_beneficiados,
                'type' => 'html',
                ),
	),
)); 
      
?>

<br/>
<br/>

<?php $this->renderPartial('_editarasistentes', array('model'=>$model,'dataProvider'=>$dataProvider)); ?>