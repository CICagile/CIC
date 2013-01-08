<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Ha ocurrido un error de tipo: <?php echo $code; ?></h2>

<div class="error">
<?php /*echo CHtml::encode($message); */
echo 'Lo sentimos, ha ocurrido un inconveniente con su petición, por favor vuelva a intentarlo.'
?>
</div>