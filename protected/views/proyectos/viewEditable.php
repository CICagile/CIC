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
        'value'=>'CHtml::dropDownList("rol[$row]", $data["rol"],
            CHtml::listData(RolAsistente::model()->findAll(), "nombre", "nombre"), array("empty"=>"Elija un rol"))',
        'type'=>'raw',
    ),
    array(
        'header'=>CHtml::encode('Horas'),
        'name'=>'horas',
        'value'=>'CHtml::textField("horas[$row]",$data["horas"],array("size"=>4,"maxlength"=>4))',
        'type'=>'raw',
    ),
    array(
        'header'=>CHtml::encode('Fin de la asistencia'),
        'name'=>'fin',
    ),
    array(
        'class'=>'CButtonColumn',
        'template'=>'{view}{update}',
        /*'buttons'=>array(
            'guardar'=>array(
                'label'=>'Guardar',
                'imageUrl'=>Yii::app()->request->baseUrl . '/images/Save.png',
                'url'=>'Yii::app()->createUrl("Proyectos/actualizarInfoAsistentes",
                    array("id"=>"'.$model->idtbl_Proyectos.'","rol"=>$data["rol"],"horas"=>$data["horas"],"fin"=>$data["fin"],"carnet"=>$data["carnet"]))',
            ),
        ),*/
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',
        'viewButtonLabel' => 'Ver información detallada del asistente',
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/updateDP", array("id"=>$data["carnet"]))',
        'updateButtonLabel' => 'Editar información personal del asistente',
        ),
    
);

?>

<h3>Detalle del proyecto.</h3>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(            
                'codigo',
		'nombre',
                array(
                        'label' => 'Estado del proyecto',
                        'value' => ($model->estado == $model->codaprobado)? $model->labelaprobado : $model->labelampliado,
                ),
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
                '_adscrito.nombre'             
	),
)); 
      
?>

<p></p><h3>Asistentes activos</h3>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>false,
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

</div>
