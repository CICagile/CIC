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
    <p id="idproyecto" style="display:none"><?php echo $model->idtbl_Proyectos?></p>
        
        <div class="errorSummary" id="errorSummary" style="display:none"></div>	

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>	
        
        <div class="row">
        <label for="codigo">Proyecto</label>  
        <?php echo $form->textArea($model,'nombre',array('readonly' => 'readonly', 'rows'=>'4' ,'cols'=> '50')); ?>                 
	</div>
               
        <div class="row">
        <label for="asistente">Asistente<span class="required">* </span><span class="note">(Numero de carnet)</span></label>
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
            <label for="horas">Cantidad de horas semanales<span class="required">*</span></label>
            <input type="text" name="horas" id="horas">
            <div class="errorMessage" id="horas_error" style="display:none"></div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Agregar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
    $(document).ready(function() { 
        //En el campo de fecha inicio coloco la fecha de hoy por default.
        var d = new Date();
        $('#inicio').val(d.getDate() + "-" + d.getMonth() + 1 + "-" + d.getFullYear());
        
        $("#rol").blur(function() {
            $("#rol_error").html('');
            var form_data = {
                action: 'validate_rol',
                rol: $(this).val()
            };
            $.ajax({
                type: "POST",
                url: "../ValidarAgregarAsistente",
                data: form_data,
                dataType: 'json',
                success: function(result) {
                    if(result.ok){
                        //falta agregar los  CSS de valido.
                    }
                    else{
                        $("#rol_error").html(result.msg);
                        //falta agregar los  CSS de invalido.
                    }				
                }
            });
	});
        
        $("#asistente").blur(function() {
            $("#asistente_error").html('');                
            var form_data = {
                action: 'validate_asistente',
                carne: $(this).val()
            };
            $.ajax({
                type: "POST",
                url: "../ValidarAgregarAsistente",
                data: form_data,
                dataType: 'json',
                success: function(result) {
                    if(result.ok){
                        //falta agregar los  CSS de valido.
                    }
                    else{
                        $("#asistente_error").html(result.msg);
                        //falta agregar los  CSS de invalido.
                    }				
                }
            });
        });
        
        $("#inicio").change(function() {
            $("#inicio_error").html('');                
            var form_data = {
                action: 'validate_fecha',
                fecha: $(this).val(),
                codigo: $("#idproyecto").html()
            };
            $.ajax({
                type: "POST",
                url: "../ValidarAgregarAsistente",
                data: form_data,
                dataType: 'json',
                success: function(result) {
                    if(result.ok){
                        //falta agregar los  CSS de valido.
                    }
                    else{
                        $("#inicio_error").html(result.msg);
                        //falta agregar los  CSS de invalido.
                    }				
                }
            });
	});
        
         $("#horas").blur(function() {

		

		var form_data = {
			action: 'check_rol',
			rol: $(this).val()
		};

		$.ajax({
			type: "POST",
			url: "../checkrol",
			data: form_data,
			success: function(result) {
				$("#rol_error").html(result);
			}
		});

	}); 
});
</script>