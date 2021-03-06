<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $dataProvider CArrayDataProvider */
/* @var $investigadores CArrayDataProvider */
/* @var $errores array */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo,
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Agregar investigador', 'url'=>array('agregarinvestigador', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Ver Proyectos', 'url'=>array('admin')),
        array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
        
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
                        'value' => $model->inicio,
                ),
                array(
                        'label' => 'Fecha finalización',
                        'value' => $model->fin,
                ),
                '_tipoproyecto.nombre',                
                '_objetivoproyecto.nombre',
                '_adscrito.nombre',
                array(
                    'label' => 'Sector(es) beneficiado(s)',
                    'value' => $sectores_beneficiados,
                    'type' => 'html',
                    ),
                'observaciones:ntext',
	),
)); 
    
?>

<br/>
<br/>

    <?php
        /**
         * ASISTENTES Si se están modificando los asistentes, muestra la vista de modificar los asistentes.
         * De lo contrario, sólo muestra los datos. 
         */
        if ($model->scenario === 'editar-asistentes')
            $this->renderPartial('_editarasistentes', array('model'=>$model,'dataProvider'=>$dataProvider,'errores'=>$errores));
        else
            $this->renderPartial('_verasistentes', array('model'=>$model,'dataProvider'=>$dataProvider));
    ?>
</br> </br>

<?php
    $this->renderPartial('_verInvestigadores', array('model'=>$model,'investigadores'=>$investigadores));
?>
