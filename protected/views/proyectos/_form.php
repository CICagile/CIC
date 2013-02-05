<?php
/* @var $this ProyectosController */
/* @var $modelproyectos Proyectos */
/* @var $modelperiodos Periodos */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'proyectos-form',	
        'enableAjaxValidation'=>true,  
        'clientOptions'=>array('validateOnSubmit'=>true),
    
)); ?>

	<p class="note">Campos con <span class="required">*</span> son obligatorios.</p>

	<?php echo $form->errorSummary(array($modelproyectos,$modelperiodos)); ?>
        
        <div class="row">
		<?php echo $form->labelEx($modelproyectos,'codigo'); ?>
		<?php echo $form->textField($modelproyectos,'codigo',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($modelproyectos,'codigo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($modelproyectos,'nombre'); ?>
		<?php echo $form->textArea($modelproyectos,'nombre',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($modelproyectos,'nombre'); ?>
	</div>		
        
        <div class="row">
		<?php echo $form->labelEx($modelperiodos,'inicio'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => CHtml::activeName($modelperiodos, 'inicio'),
                        'value' => $modelperiodos->attributes['inicio'],
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
                <?php echo $form->error($modelperiodos,'inicio'); ?>
		
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($modelperiodos,'fin'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => CHtml::activeName($modelperiodos, 'fin'),
                        'value' => $modelperiodos->attributes['fin'],
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
                <?php echo $form->error($modelperiodos,'fin'); ?>
		
	</div>

	<div class="row">
		<?php echo $form->labelEx($modelproyectos,'tipoproyecto'); ?>
		<?php echo $form->dropDownList($modelproyectos, 'tipoproyecto',
                        CHtml::listData(TipoProyecto::model()->findAll(), 'idtbl_tipoproyecto', 'nombre'), array('empty'=>'Elija el tipo de proyecto', 'id'=>'tipoproyecto')) ?>		 
		<?php echo $form->error($modelproyectos,'tipoproyecto'); ?>
	</div>        
        
        <div class="row">
		<?php echo $form->labelEx($modelproyectos,'idtbl_objetivoproyecto'); ?>
		<?php echo $form->dropDownList($modelproyectos, 'idtbl_objetivoproyecto',
                        CHtml::listData(ObjetivoProyecto::model()->findAll(), 'idtbl_objetivoproyecto', 'nombre'), array('empty'=>'Elija el objetivo del proyecto', 'id'=>'objetivoproyecto')) ?>
		<?php echo $form->error($modelproyectos,'idtbl_objetivoproyecto'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($modelproyectos,'idtbl_adscrito'); ?>
		<?php echo $form->dropDownList($modelproyectos, 'idtbl_adscrito',
                        CHtml::listData(Adscrito::model()->findAll(), 'idtbl_adscrito', 'nombre'), array('empty'=>'Elija la adscripción del proyecto', 'id'=>'adscritoproyecto')) ?>
		<?php echo $form->error($modelproyectos,'idtbl_adscrito'); ?>
	</div>  
        
        <div class="row">
		<?php echo $form->labelEx($modelproyectos,'idtbl_estadoproyecto'); ?>
		<?php echo $form->dropDownList($modelproyectos, 'idtbl_estadoproyecto',
                        CHtml::listData(EstadoProyecto::model()->findAll(), 'idtbl_estadoproyecto', 'nombre'), array('empty'=>'Elija el estado del proyecto', 'id'=>'estadoproyecto')) ?>
		<?php echo $form->error($modelproyectos,'idtbl_estadoproyecto'); ?>
	</div>
        
        <div class="row buttons">
		<?php echo CHtml::submitButton($modelproyectos->isNewRecord ? 'Crear' : 'Guardar'); ?>
	</div>

<?php $this->endWidget(); ?>
        
<?php if($modelproyectos->hasErrors()):?>
{
    <script type="text/javascript">
        alert(' '.<?php echo var_dump($model->getErrors());?>);
    </script>
}
<?php endif;?>

</div><!-- form -->

<script type="text/javascript">
$("#tipoproyecto").change(function() {
            $("#Proyectos_tipoproyecto_em_").html('');            
            $("#Proyectos_tipoproyecto_em_").css('display', 'none');
            
            if($(this).val()== ""){
                $("#Proyectos_tipoproyecto_em_").html('Debe seleccionar el tipo de proyecto.');
                $("#Proyectos_tipoproyecto_em_").css('display', '');
            }
});
</script>