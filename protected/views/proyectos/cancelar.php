<?php
$this->breadcrumbs = array(
    'Proyectos' => array('admin'),
    'Cancelar Proyecto',
);

$this->menu = array(
    array('label' => 'Ver información de este proyecto', 'url' => array('ver', 'id' => $modelproyectos->idtbl_Proyectos)),
    array('label' => 'Ver Proyectos', 'url' => array('admin')),
);
?>

<h4>Información del Proyecto</h4>
<div>
    <?php
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $modelproyectos,
        'attributes' => array(
            'nombre',
            array(
                'label' => 'Fecha finalización',
                'value' => $modelproyectos->fin,
            ),
            array(
                'label' => 'Fecha Inicio',
                'value' => $modelproyectos->inicio,
            ),
        ),
    ));
    ?>
</div>
<br/>
<br/>
<div class="form"> 
    <?php
    echo CHtml::beginForm('', 'post', array('id' => 'form-cancelar'));
    ?>
    <fieldset>
        <div class="row">

            <?php
            echo CHtml::label('Fecha de cancelación', 'fecha_cancelacion');

            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'fecha_cancelacion',
                'id' => 'fecha_cancelacion',
                'language' => 'es',
                'options' => array(
                    'dateFormat' => 'dd-mm-yy',
                    'changeYear' => true,
                    'changeMonth' => true,
                ),
                'htmlOptions' => array(
                    'readonly' => 'readonly'
                ),
            ));
            ?>
        </div>
        <div class="errorMessage" id="cancelacion_error"></div>
        <div class="row">
            <div class="row">
                <?php echo CHtml::label('Motivo de cancelación', 'motivo_cancelacion'); ?>
<?php echo CHtml::textArea('motivo_cancelacion', '', array('size' => 60, 'maxlength' => 500, 'style' => 'width:60%; heigth:100%;')); ?>
                <div class="errorMessage" id="motivo_error"></div>
            </div>	

        </div>
        <div class="row buttons">

            <?php
            echo CHtml::ajaxSubmitButton('Cancelar Proyecto', '', array(
                'type' => 'POST',
                'dataType' => 'json',
                'beforeSend' => 'function(data){
                  $("#cancelacion_error").html("");                 
            }',
                'data' => array(
                    'cancelacion' => 'js:$("#fecha_cancelacion").val()',
                    'detalle_motivo' => 'js:$("#motivo_cancelacion").val()',
                ),
                'success' => 'js:function(data){
               if(data.ok){
                   $("#cancelacion_error").html("");
                   alert(data.msg);
                   var url = "../admin/";    
                   $(location).attr("href",url);
                }else{                  
                   $("#cancelacion_error").html(data.msg);
                }
              }',
                    ), array('id' => 'btn_cancelar'));
            echo CHtml::endForm();
            ?>
        </div>
    </fieldset>
</div>


<script type="text/javascript">
    //Esto se utiliza para que el calendario inicie a partir de la fecha de inicio del proyecto,
    //y además permitir seleccionar solo fechas despues de esa fecha.
    $(document).ready(function() {
      
        var fecha_fin_array = $('.detail-view > tbody > tr:last > td').text().match(/(\d+)/g);

        var dia = (parseInt(fecha_fin_array[0])+1).toString(); //Se suma un dia para evitar que seleccione el actual
        var mes = (parseInt(fecha_fin_array[1])-1).toString(); //El mes en JS empieza en 0 por lo tanto siempre se debe restar -1
        var ano = fecha_fin_array[2].toString();
      
        $("#fecha_cancelacion").click(function(){
            $("#ampliacion_error").html("");
            $( "#fecha_cancelacion" ).datepicker( "option", "minDate", new Date(ano, mes, dia) );  
            $( "#fecha_cancelacion" ).datepicker("show"); 
        });
    });  
</script>
