<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo,
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Ver Proyecto', 'url'=>array('ver','id'=>$model->idtbl_Proyectos)),
        array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
);

?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'proyectos-agregarasistente-form',
	'enableAjaxValidation'=>false,
)); ?>
        
    <h2>Agregar investigador al Proyecto: <?php echo $model->codigo?></h2>
    <p>Periodo del proyecto: <?php echo $this->FechaMysqltoPhp($model->inicio)
    .' hasta '.$this->FechaMysqltoPhp($model->fin)?></p>

	<p class="note">Campos con <span class="required">*</span> son obligatorios.</p>	
        
        <div class="row">
        <label for="codigo">Proyecto</label>  
        <?php echo $form->textArea($model,'nombre',array('readonly' => 'readonly', 'rows'=>'4' ,'cols'=> '50')); ?>                 
	</div>
               
        <div class="row">
        <label for="investigador">Investigador (cédula)<span class="required">* </span></label>
        <?php           
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'attribute'=>'investigador',
            'name'=>'investigador', 
            'id'=>'investigador',
            'source'=>$this->createUrl('proyectos/codigoautocomplete'),
            // additional javascript options for the autocomplete plugin
            'options'=>array(
                    'showAnim'=>'fold',
            ),
        ));
        ?>
         <div class="errorMessage" id="asistente_error"></div>	
        </div>
        
        
         <div class="row">
		<label for="rol">Rol del asistente<span class="required">*</span></label>
		<?php echo $form->dropDownList(RolAsistente::model(), 'nombre',
                        CHtml::listData(RolAsistente::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija un rol', 'id'=>'rol', 'name' => 'rol')) ?>		 
                 <div class="errorMessage" id="rol_error" name="rol_error"></div>
	</div>
        
        <div class="row">
		<label for="inicio">Fecha inicio de la asistencia<span class="required">*</span></label>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'inicio',
                        'id' => 'inicio',
                        'value' => '',
                        'language' => 'es',
                        'options' => array(                            
                            'showAnim'=>'fold',
                            'dateFormat'=>'dd-mm-yy',
                            'changeYear'=>true,
                            'changeMonth'=>true,                           
                        ),
                        'htmlOptions'=>array(                            
                            'readonly' => 'readonly',                            
                        ),
                    ));?>
                 <div class="errorMessage" id="inicio_error"></div>
	</div>
        
        <div class="row">
		<label for="fin">Fecha fin de la asistencia<span class="required">*</span></label>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'fin',
                        'id' => 'fin',
                        'value' => '',
                        'language' => 'es',
                        'options' => array(                            
                            'showAnim'=>'fold',
                            'dateFormat'=>'dd-mm-yy',
                            'changeYear'=>true,
                            'changeMonth'=>true,                           
                        ),
                        'htmlOptions'=>array(                            
                            'readonly' => 'readonly',                            
                        ),
                    ));?>
                 <div class="errorMessage" id="fin_error"></div>
	</div>
        
        <div class="row">
            <label for="horas">Cantidad de horas semanales<span class="required">*</span></label>
            <input type="text" name="horas" id="horas">
            <div class="errorMessage" id="horas_error"></div>
	</div>

	
		<?php echo CHtml::submitButton('Agregar'); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->
