OJO!!! http://stackoverflow.com/questions/13376842/loading-data-from-another-table-in-yii
TAMBIEN HAY QUE FIJARSE QUE LOS CARACTERES MAXIMOS COINCIDAN CON LOS DE LA BASE DE DATOS!!!



<?php
/* @var $this AsistenteController */
/* @var $model Asistente */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asistente-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Los campos marcados con <span class="required">*</span> son obligatorios.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'nombre'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'apellido1'); ?>
		<?php echo $form->textField($model,'apellido1',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'apellido1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'apellido2'); ?>
		<?php echo $form->textField($model,'apellido2',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'apellido2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cedula'); ?>
		<?php echo $form->textField($model,'cedula',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'cedula'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'numerocuenta'); ?>
		<?php echo $form->textField($model,'numerocuenta',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'numerocuenta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'banco'); ?>
		<?php echo $form->textField($model,'banco',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'banco'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cuentacliente'); ?>
		<?php echo $form->textField($model,'cuentacliente',array('size'=>17,'maxlength'=>17)); ?>
		<?php echo $form->error($model,'cuentacliente'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'carnet'); ?>
		<?php echo $form->textField($model,'carnet',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'carnet'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'carrera'); ?>
		<?php echo $form->textField($model,'carrera',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'carrera'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'codigo'); ?>
		<?php echo $form->textField($model,'codigo',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'codigo'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Create'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->