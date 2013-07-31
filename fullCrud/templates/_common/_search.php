<div class="wide form">

    <?=
    "
    <?php
    \$form=\$this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl(\$this->route),
        'method'=>'get',
    )); ?>\n
    ";
    ?>

    <?php foreach ($this->tableSchema->columns as $column): ?>

        <div class="row">
            <?= "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
            <?= "<?php " . $this->provider()->generateActiveField($this->modelClass, $column) . "; ?>\n"; ?>

        </div>

    <?php endforeach; ?>

    <div class="row buttons">
        <?= "<?php echo CHtml::submitButton(Yii::t('" . $this->messageCatalog . "', 'Search')); ?>\n"; ?>
    </div>

    <?= "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->
