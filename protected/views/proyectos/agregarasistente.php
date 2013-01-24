<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'proyectos-agregarasistente-form',
	'enableAjaxValidation'=>false,
)); ?>
        
    <h2>Agregar asistente al Proyecto: <?php echo $model->codigo?></h2>
    <p>Periodo del proyecto: <?php echo $this->FechaMysqltoPhp($model->periodos->inicio)
    .' hasta '.$this->FechaMysqltoPhp($model->periodos->fin)?></p>
   
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

	<div class="row buttons">
		<?php echo CHtml::submitButton('Agregar'); ?>
                <p id="infovalidacion"></p>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
    $(document).ready(function() { 
        //En el campo de fecha inicio coloco la fecha de hoy por default.
        var d = new Date();
        $('#inicio').val(d.getDate() + "-" + d.getMonth() + 1 + "-" + d.getFullYear());
        
        $('#horas').val('20');
        
        $("#rol").blur(function() {
            $("#rol_error").html('');
            $("#errorSummary").html('');  
            $("#errorSummary").css('display', 'none');
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
        
        $("#asistente").focusout(function() {
            $("#asistente_error").html('');
            $("#errorSummary").html('');  
            $("#errorSummary").css('display', 'none');
            var form_data = {
                action: 'validate_asistente',
                carne: $(this).val(),
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
                        $("#asistente_error").html(result.msg);
                        //falta agregar los  CSS de invalido.
                    }				
                }
            });
        });
        
        $("#inicio").change(function() {
            $("#inicio_error").html('');  
            $("#errorSummary").html('');  
            $("#errorSummary").css('display', 'none');
            var form_data = {
                action: 'validate_fecha_inicio',
                fecha_inicio: $(this).val(),
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
        
        $("#fin").change(function() {
            $("#fin_error").html('');  
            $("#errorSummary").html('');  
            $("#errorSummary").css('display', 'none');
            var form_data = {
                action: 'validate_fecha_fin',
                fecha_fin: $(this).val(),
                fecha_inicio: $('#inicio').val(),
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
                        $("#fin_error").html(result.msg);
                        //falta agregar los  CSS de invalido.
                    }				
                }
            });
	});
        
         $("#horas").blur(function() {
            $("#horas_error").html('');
            $("#errorSummary").html('');  
            $("#errorSummary").css('display', 'none');
            var form_data = {
                action: 'validate_horas',
                horas: $(this).val()
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
                        $("#horas_error").html(result.msg);
                        //falta agregar los  CSS de invalido.
                    }				
                }
            });
	}); 
        
        $("#proyectos-agregarasistente-form").submit(function(e){
        e.preventDefault();    
        
        $("#errorSummary").html('');  
        $("#errorSummary").css('display', 'none');
        $("#infovalidacion").html('Validando datos...');  
        
        var form_data = {
            action: 'validate_form_agregar',
            rol: $("#rol").val(),            
            horas: $("#horas").val(),
            fecha_inicio: $("#inicio").val(),
            fecha_fin: $("#fin").val(),
            codigo: $("#idproyecto").html(),
            carne: $("#asistente").val()
        };
        $.ajax({
            type: "POST",
            url: "../ValidarAgregarAsistente",
            data: form_data,
            dataType: 'json',
            success: function(result) {
                if(result.ok){
                        $("#infovalidacion").html('');
                        var idproyecto = $("#idproyecto").html(); 
                        var form_data = $("#proyectos-agregarasistente-form").serialize();
                        $.ajax({              
                        type: "POST",
                        url: "../AgregarAsistente/" + idproyecto,
                        data: form_data,
                        dataType: 'json',
                        success: function(result) {  
                            if(result.ok){
                                alert(result.msg);
                                var url = "../view/" + idproyecto;    
                                $(location).attr('href',url);
                            }
                            else
                               alert(result.msg); 
                        }
                        });
                }
                else{
                    $("#errorSummary").css('display', '');
                    $("#errorSummary").html(result.msg);
                    $("#infovalidacion").html('');  
                    //falta agregar los  CSS de invalido.
                }				
            }
        });

        });
});
</script>


