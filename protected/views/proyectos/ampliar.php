
<?php
Yii::app()->clientScript->registerCoreScript('jquery');
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
                        'value' => $this->FechaMysqltoPhp($modelproyectos->inicio),
                ),
                array(
                        'label' => 'Fecha finalización',
                        'value' => $this->FechaMysqltoPhp($modelproyectos->fin),
                ),                         
	),
)); 
?>
</div>
<br/>
<br/>
<div class="form"> 
<?php
echo CHtml::beginForm();
?>

<div class="row">
      
<?php
echo CHtml::label('Fecha de ampliación', 'fecha_ampliacion');

echo CHtml::textField('fecha_ampliacion', '', array('id'=>'fecha_ampliacion'))
?>
</div>

<div class="row buttons">
<?php
echo CHtml::SubmitButton('Ampliar Proyecto'); 
echo CHtml::endForm();
?>
</div>
</div>


<script type="text/javascript">
  //Esto se utiliza para que el calendario inicie a partir de la fecha de finalizacion del proyecto,
  //y además permitir seleccionar solo fechas despues de esa fecha.
  $(document).ready(function() {      
      var fecha_fin = $('.detail-view > tbody > tr:last > td').text();
      var fecha_array = $('.detail-view > tbody > tr:last > td').text().match(/(\d+)/g);
      
      var dia = parseInt(fecha_array[0]);
      var mes = parseInt(fecha_array[1]);
      var ano = parseInt(fecha_array[2]);
      
        
        $( "#fecha_ampliacion" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3      
              
        });
  });
 
</script>
