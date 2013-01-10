<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'proyectos-agregarasistente-form',
	'enableAjaxValidation'=>true,
)); ?>
        
    <h2>Agregar asistente al Proyecto: <?php echo $model->codigo?></h2>    
        
        <div class="errorSummary" id="errorSummary" style="display:none"></div>
	

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>	
        
        <div class="row">
        <label for="codigo">Proyecto</label>  
        <?php echo $form->textArea($model,'nombre',array('value' => $model->codigo,  'readonly' => 'readonly', 'rows'=>'4' ,'cols'=> '50')); ?>                 
	</div>
               
        <div class="row">
        <label for="asistente">Asistente<span class="required">*</span></label>
        <?php           
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'attribute'=>'asistente',
            'name'=>'asistente', 
            'id'=>'asistente',
            'source'=>$this->createUrl('proyectos/asistenteautocomplete'),
            // additional javascript options for the autocomplete plugin
            'options'=>array(
                    'showAnim'=>'fold',
            ),
        ));
        ?>
         <div class="errorMessage" id="asistente_error" style="display:none"></div>	
        </div>
        
        
         <div class="row">
		<label for="rol">Rol del asistente<span class="required">*</span></label>
		<?php echo $form->dropDownList(RolAsistente::model(), 'nombre',
                        CHtml::listData(RolAsistente::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija un rol', 'id'=>'rol')) ?>
		 <div class="errorMessage" id="rol_error" style="display:none"></div>	
	</div>
        
        <div class="row">
		<label for="inicio">Fecha inicio<span class="required">*</span></label>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'ininio',
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
                            'readonly' => 'readonly'
                        ),
                    ));?>
                 <div class="errorMessage" id="inicio_error" style="display:none"></div>
	</div>
        
        <div class="row">
		<label for="inicio">Fecha fin<span class="required">*</span></label>
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
                            'readonly' => 'readonly'
                        ),
                    ));?>
                 <div class="errorMessage" id="fin_error" style="display:none"></div>
	</div>
	        
        <div class="row">
            <label for="horas">Cantidad de horas<span class="required">*</span></label>
            <input type="text" name="horas" id="horas">
            <div class="errorMessage" id="horas_error" style="display:none"></div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Agregar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->