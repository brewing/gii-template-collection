<div class="">
    <p class="alert">
        <?php echo "<?php echo Yii::t('" . $this->messageCatalog . "','Fields with <span class=\"required\">*</span> are required.');?> \n"; ?>
    </p>


    <?php echo "<?php
    \$this->widget('echosen.EChosen',
        array('target'=>'select')
    );
    ?>"; ?>

    <?php echo '<?php'; ?>

    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'<?php echo $this->class2id($this->modelClass); ?>-form',
    'enableAjaxValidation'=><?php echo $this->validation == 1 || $this->validation == 3 ? 'true' : 'false'; ?>,
    'enableClientValidation'=><?php echo $this->validation == 2 || $this->validation == 3 ? 'true' : 'false'; ?>,
    'type' => '<?php echo $this->formOrientation; ?>',
    ));

    echo $form->errorSummary($model);

    <?php echo '?>'; ?>

 <div class="row">
     <div class="span8"> <!-- main inputs -->

    <?php
    foreach ($this->tableSchema->columns as $column) {

        // omit pk
        if ($column->autoIncrement) {
            continue;
        }

        // omit relations, they are rendered below
        foreach ($this->getRelations() as $key => $relation) {
            if ($relation[2] == $column->name) {
                continue 2;
            }
        }

        // render a view file if present in destination folder
        if ($columnView = $this->resolveColumnViewFile($column)) {
            echo "<?php      \$this->renderPartial('{$columnView}', array('model'=>\$model)) ?>";
            continue;
        }

        // assume timestamp attribute is automated
        $automatedAttributes = array(
            'timestamp',
        );

        // check for CTimestampBehavior to determine automated attributes
        $model = new $this->modelClass();
        $behaviors = $model->behaviors();
        if (isset($behaviors['CTimestampBehavior'])) {
            $behaviorObject = $model->asa('CTimestampBehavior');
            $automatedAttributes[] = $behaviorObject->createAttribute;
            $automatedAttributes[] = $behaviorObject->updateAttribute;
        }

        // render input
        if (!in_array($column->name, $automatedAttributes)) {
            echo "\n";
            echo "    <?php echo " . $this->generateActiveRow($this->modelClass, $column) . "; ?>\n";
        }
    }

    // render relation inputs
    foreach ($this->getRelations() as $key => $relation) {
        if ($relation[0] == 'CBelongsToRelation'
            || $relation[0] == 'CHasOneRelation'
            || $relation[0] == 'CManyManyRelation'
        ) {
            if ($relationView = $this->resolveRelationViewFile($relation)) {
                echo "      <?php \$this->renderPartial('{$relationView}', array('model'=>\$model)) ?>";
                continue;
            }

            echo "    <div class=\"row-fluid input-block-level-container\">\n";
            echo "        <div class=\"span12\">\n";
            printf("        <label for=\"%s\"><?php echo Yii::t('" . $this->messageCatalog . "', '%s'); ?></label>\n", $key, ucfirst($key));
            echo "                <?php\n";
            echo "                ".$this->codeProvider->generateRelation($this->modelClass, $key, $relation);
            echo "\n              ?>\n";
            echo "        </div>\n";
            echo "    </div>\n\n";
        }
    }
    ?>
    </div> <!-- main inputs -->


    <div class="span4"> <!-- sub inputs -->

    </div> <!-- sub inputs -->
</div>


    <div class="form-actions">
        <?php
        echo "
    <?php
        echo CHtml::Button(Yii::t('" . $this->messageCatalog . "', 'Cancel'), array(
			'submit' => (isset(\$_GET['returnUrl']))?\$_GET['returnUrl']:array('" . strtolower($this->modelClass) . "/admin'),
			'class' => 'btn'
			));
        echo ' '.CHtml::submitButton(Yii::t('" . $this->messageCatalog . "', 'Save'), array(
            'class' => 'btn btn-primary'
            ));
    ?>\n";
        ?>
</div>

<?php echo "<?php \$this->endWidget() ?>"; ?>

</div> <!-- form -->