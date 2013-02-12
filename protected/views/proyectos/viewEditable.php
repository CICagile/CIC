<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $asistente Asistente */

$this->breadcrumbs=array(
	'Proyectos'=>array('index'),
	$model->codigo,
);

$this->menu=array(
	array('label'=>'Ver Proyectos', 'url'=>array('index')),
	array('label'=>'Nuevo Proyecto', 'url'=>array('create')),        
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
	/*array('label'=>'Delete Proyectos', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idtbl_Proyectos),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Proyectos', 'url'=>array('admin')),*/
);

//Columnas de la tabla de los asistentes activos del proyecto.
$columns = array (
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('carnet')),
        'name'=>'carnet',
    ),
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('nombre')),
        'name'=>'nombre',
    ),
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('apellido1')),
        'name'=>'apellido1',
    ),
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('apellido2')),
        'name'=>'apellido2',
    ),
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('rol')),
        'name'=>'rol',
    ),
    array(
        'header'=>CHtml::encode('Horas'),
        'name'=>'horas',
        'value'=>'CHtml::textField("horas[$row]",$data["horas"],array("size"=>5,"maxlength"=>5))',
        'type'=>'raw',
    ),
    array(
        'header'=>CHtml::encode('Fin de la asistencia'),
        'name'=>'fin',
    ),
    array(
        'class'=>'CButtonColumn',
        'template'=>'{view}{guardar}{delete}',
        'buttons'=>array(
            'guardar'=>array(
                'label'=>'Guardar',
                'imageUrl'=>Yii::app()->request->baseUrl . '/images/Save.png',
                'url'=>'Yii::app()->createUrl("Proyectos/actualizarInfoAsistentes",
                    array("id"=>"'.$model->idtbl_Proyectos.'","rol"=>$data["rol"],"horas"=>$data["horas"],"fin"=>$data["fin"],"carnet"=>$data["carnet"]))',
            ),
        ),
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',
        'deleteButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',),
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
                )
                
	),
)); 
      
?>

<p></p><h3>Asistentes activos</h3>

<?php $form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
));
?>

<?php echo $form->errorSummary(array($model,$asistente),'Se han detectado los siguientes errores:'); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$dataProvider,
	//'filter'=>$model,
	'columns'=>$columns,
           ));
?>

<?php echo CHtml::submitButton('Guardar');?>

<?php $this->endWidget(); ?>
