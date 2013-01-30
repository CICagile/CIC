<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Lista de Proyectos',
);

$this->menu=array(	
	array('label'=>'Nuevo Proyecto', 'url'=>array('create')),
);
?>

<h1>Lista de Proyectos</h1>




<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'proyectos-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,  
        'afterAjaxUpdate'=>'cssfechas',
	'columns'=>array(
                'codigo',
		'nombre',		
		array(
                        'name' => 'fecha_inicio_search',
                        'value' => '$this->grid->owner->FechaMysqltoPhp($data->periodos->inicio)',                        
                        'id' => 'fecha_ini',
                ),
                array(
                        'name' => 'fecha_fin_search',
                        'value' => '$this->grid->owner->FechaMysqltoPhp($data->periodos->fin)',                        
                        'id' => 'fecha_fin'
                ),
		array(
			'class'=>'CButtonColumn',
		),
	)
    )); 

//Esto se utiliza para mantener el estilo css de las columnas fechas del Grid luego de un request AJAX
$string = "'";//Esto string se utiliza para poder crear correctamente el string del Script
Yii::app()->clientScript->registerScript('css-fechas', '
function cssfechas(id,data)
{
    $('.$string.'[name="Proyectos[fecha_inicio_search]"]'.$string.').css({"visibility": "hidden", "width" : "70px"});
    $('.$string.'[name="Proyectos[fecha_fin_search]"]'.$string.').css({"visibility": "hidden", "width" : "70px"});
}

'   
);
?>
<script type="text/javascript">
  //Esto se utiliza para mantener el estilo css de las columnas fechas del Grid en Load
  $(document).ready(function() {
    $('[name="Proyectos[fecha_inicio_search]"]').css({'visibility': 'hidden', 'width' : '70px'})
    $('[name="Proyectos[fecha_fin_search]"]').css({'visibility': 'hidden', 'width' : '70px'})   
  });
</script>
