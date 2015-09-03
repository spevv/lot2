<?php

use kartik\form\ActiveForm;
//use kartik\spinner\Spinner;
use kartik\helpers\Html;
use dosamigos\multiselect\MultiSelect;

?>


<?php 
$js = <<< JS
 var Text = {
  plural: function(n, forms) {
    var plural = 0;
    if (n % 10 == 1 && n % 100 != 11) {
      plural = 0;
    } else {
      if ((n % 10 >= 2 && n % 10<=4) && (n % 100 < 10 || n % 100 >= 20)) {
        plural = 1;
      } else {
        plural = 2;
      }
    }
    return forms[plural];
  }
};

 	$('#lotsearch-city_id').multiselect({
	    buttonClass: 'btn btn-link',
	    includeSelectAllOption: true,
	    allSelectedText: 'Все',
	    selectAllText: 'Выбрать все',
	    maxHeight: 300,
	    numberDisplayed: 2,
	    buttonText: function(options, select) {
	      if (options.length === 0) {
	        return 'Ничего не выбрано';
	      }
	      else if (options.length > 1) {
	        if (options.length == select.find('option').length) {
	          return 'Все'
	        } else {
	          return options.length + ' ' + Text.plural(options.length, ['город', 'города', 'городов']);
	        }
	      }
	      else {
	        var labels = [];
	        options.each(function() {
	          if ($(this).attr('label') !== undefined) {
	            labels.push($(this).attr('label'));
	          }
	          else {
	            labels.push($(this).html());
	          }
	        });
	        return labels.join(', ') + ' ';
	      }
	    }
	});
	
	$('#lotsearch-subjects').multiselect({
	    buttonClass: 'btn btn-link',
	    includeSelectAllOption: true,
	    allSelectedText: 'Все',
	    selectAllText: 'Выбрать все',
	    maxHeight: 300,
	    numberDisplayed: 2,
	    buttonText: function(options, select) {
	      if (options.length === 0) {
	        return 'Ничего не выбрано';
	      }
	      else if (options.length > 1) {
	        if (options.length == select.find('option').length) {
	          return 'Все'
	        } else {
	          return options.length + ' ' + Text.plural(options.length, ['тема', 'темы', 'тем']);
	        }
	      }
	      else {
	        var labels = [];
	        options.each(function() {
	          if ($(this).attr('label') !== undefined) {
	            labels.push($(this).attr('label'));
	          }
	          else {
	            labels.push($(this).html());
	          }
	        });
	        return labels.join(', ') + ' ';
	      }
	    }
	});
	
	$('#lotsearch-branchs').multiselect({
	    buttonClass: 'btn btn-link',
	    includeSelectAllOption: true,
	    allSelectedText: 'Все',
	    selectAllText: 'Выбрать все',
	    maxHeight: 300,
	    numberDisplayed: 2,
	    buttonText: function(options, select) {
	      if (options.length === 0) {
	        return 'Ничего не выбрано';
	      }
	      else if (options.length > 1) {
	        if (options.length == select.find('option').length) {
	          return 'Все'
	        } else {
	          return options.length + ' ' + Text.plural(options.length, ['отрасль', 'отрасли', 'отраслей']);
	        }
	      }
	      else {
	        var labels = [];
	        options.each(function() {
	          if ($(this).attr('label') !== undefined) {
	            labels.push($(this).attr('label'));
	          }
	          else {
	            labels.push($(this).html());
	          }
	        });
	        return labels.join(', ') + ' ';
	      }
	    }
	});
	
	
	
JS;
$this->registerJs($js,  $this::POS_READY);
?>


<div class="lot-search">

    <?php $form = ActiveForm::begin([
        'action' => $action, //['index'],
        'method' => 'post',
        'options' => ['data-pjax' => 'true', 'id'=>'lot-search'],
        //'id' => 'lot-search', 
        'formConfig' => [
        	'showLabels'=> false,
        ],
    ]); ?>
	<div class="block-multi-city">
	    <div class="title-multi-city-icon"></div> 
	    <div class="title-multi-city">Города</div> 
	    	<?php if($region): ?>
	    		<?= $form->field($model, 'city_id')->widget(MultiSelect::classname(), [
					    "options" => ['multiple'=>"multiple",  "id" => 'lotsearch-city_id'], // for the actual multiselect
					    'data' => $region, // data as array
					])
				 ?>	 
	    	<?php else: ?>
	    		<span class="multiselect-selected-text">Нет данных</span>
	    	<?php endif; ?>
	   
	</div>
		
	<div class="block-multi-subjects">
		<div class="title-multi-subjects-icon"></div> 
		<div class="title-multi-subjects">Темы</div> 
		<?php if($subjects): ?>	
		 		 <?= $form->field($model, 'subjects')->widget(MultiSelect::classname(), [
				    "options" => ['multiple'=>"multiple", "id" => 'lotsearch-subjects'], // for the actual multiselect
				    'data' => $subjects, // data as array
					])
			 ?>
	    	<?php else: ?>
	    		<span class="multiselect-selected-text">Нет данных</span>
	    	<?php endif; ?>
	   
	</div>

	<div class="block-multi-branchs">
		<div class="title-multi-branchs-icon"></div> 
		<div class="title-multi-branchs">Отрасли</div> 
		<?php if($branchs): ?>	 
				<?= $form->field($model, 'branchs')->widget(MultiSelect::classname(), [
					    "options" => ['multiple'=>"multiple", "id" => 'lotsearch-branchs'], // for the actual multiselect
					    'data' => $branchs, // data as array
						])
				 ?>
	    	<?php else: ?>
	    		<span class="multiselect-selected-text">Нет данных</span>
	    	<?php endif; ?>
	    
	</div>
	
	<div class="block-multi-button">
	    <div class="form-group">
	 	<?php 

	 	$subnmae = '<span>Найти</span> <span id="spinner" style="display: none; margin-top: 10px; float:right; margin-left: 20px;"></span>';
	        ?>
	        <?= Html::submitButton($subnmae, ['class' => ' btn-lot', 'id'=>'idb']) ?> 
	    </div>
	</div>

    <?php ActiveForm::end(); ?>
</div>




