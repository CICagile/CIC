<?php
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Ampliar proyecto',
);

$this->menu=array(
        array('label'=>'Ver información de este proyecto', 'url'=>array('ver', 'id'=>$modelproyectos->idtbl_Proyectos)),
	array('label'=>'Ver Proyectos', 'url'=>array('admin')),        	
);
?>





<h4>Información del Proyecto</h4>
<div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$modelproyectos,
	'attributes'=>array( 
                'nombre',                
		array(
                        'label' => 'Fecha Inicio',
                        'value' => $modelproyectos->inicio,
                ),
                array(
                        'label' => 'Fecha finalización',
                        'value' => $modelproyectos->fin,
                ),                         
	),
)); 
?>
</div>
<br/>
<br/>
<div class="form"> 
<?php
echo CHtml::beginForm('', 'post', array('id'=>'form-ampliar'));
?>
<fieldset>
<div class="row">
      
<?php
echo CHtml::label('Fecha de ampliación', 'fecha_ampliacion');

$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'fecha_ampliacion',
                    'id'  => 'fecha_ampliacion',
                    'language' => 'es',
                    'options' => array(   
                        'dateFormat'=>'dd-mm-yy',
                        'changeYear'=>true,
                        'changeMonth'=>true,                       
                    ),
                    'htmlOptions'=>array(                            
                        'readonly' => 'readonly'
                    ),
                ));              
?>
</div>
<div class="errorMessage" id="ampliacion_error"></div>
<div class="row buttons">

<?php
echo CHtml::ajaxSubmitButton('Ampliar Proyecto', '', array(
    'type'=>'POST',
    'dataType'=>'json',
    'beforeSend' => 'function(data){
                  $("#ampliacion_error").html("");                 
            }',
    'data'=>array('ampliacion'=>'js:$("#fecha_ampliacion").val()'),
    'success' => 'js:function(data){
               if(data.ok){
                   $("#ampliacion_error").html("");
                   alert(data.msg);
                   var url = "../admin/";    
                   $(location).attr("href",url);
                }else{                  
                   $("#ampliacion_error").html(data.msg);
                }
              }', 
), array('id' => 'btn_ampliar')); 
echo CHtml::endForm();
?>
</div>
</fieldset>
</div>


<script type="text/javascript">
  //Esto se utiliza para que el calendario inicie a partir de la fecha de finalizacion del proyecto,
  //y además permitir seleccionar solo fechas despues de esa fecha.
  $(document).ready(function() {
      
        var fecha_fin_array = $('.detail-view > tbody > tr:last > td').text().match(/(\d+)/g);

        var dia = (parseInt(fecha_fin_array[0])+1).toString(); //Se suma un dia para evitar que seleccione el actual
        var mes = (parseInt(fecha_fin_array[1])-1).toString(); //El mes en JS empieza en 0 por lo tanto siempre se debe restar -1
        var ano = fecha_fin_array[2].toString();
      
      $("#fecha_ampliacion").click(function(){
          $("#ampliacion_error").html("");
          $( "#fecha_ampliacion" ).datepicker( "option", "minDate", new Date(ano, mes, dia) );  
          $( "#fecha_ampliacion" ).datepicker("show"); 
      });
  });  
</script>
