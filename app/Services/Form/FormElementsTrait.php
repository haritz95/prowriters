<?php

namespace App\Services\Form;

trait FormElementsTrait
{
    function input()
    {
        ob_start();
        echo $this->getBeforeElement();
?>
        <input type="text" <?php if ($this->id) { ?> id="<?php echo $this->id; ?>" <?php } ?> class="form-control form-control-sm <?php echo $this->classNames; ?> <?php echo $this->showErrorClass($this->name); ?>" name="<?php echo $this->name; ?>" value="<?php echo $this->setValue($this->name, $this->model); ?>">
    <?php
        echo $this->getAfterElement();
        return ob_get_clean();
    }

    function textarea()
    {
        ob_start();
        echo $this->getBeforeElement();
    ?>

        <textarea <?php if ($this->id) { ?> id="<?php echo $this->id; ?>" <?php } ?> class="form-control form-control-sm <?php echo $this->classNames; ?> <?php echo $this->showErrorClass($this->name); ?>" name="<?php echo $this->name; ?>"><?php echo $this->setValue($this->name, $this->model); ?></textarea>
    <?php
        echo $this->getAfterElement();
        return ob_get_clean();
    }


    function select()
    {
        ob_start();
        echo $this->getBeforeElement();
    ?>

        <?php
        $extra = 'class="' . $this->classNames . ' form-select' . $this->showErrorClass($this->name) . '"';
        $extra = ($this->id) ? 'id="' . $this->id . '" ' . $extra : $extra;
        echo form_dropdown($this->name, $this->options, $this->setValue($this->name, $this->model), $extra);
        ?>

    <?php
        echo $this->getAfterElement();
        return ob_get_clean();
    }


    function checkbox()
    {
        ob_start();
        $id = ($this->id) ? $this->id : $this->name; 
        $isChecked = (old($this->name, (optional($this->model)->{$this->name}))) ? 'checked' : ''
    ?>
        <div class="form-check">
            <input <?php echo $isChecked; ?> class="form-check-input" type="checkbox" value="1" id="<?php echo $id; ?>">
            <label class="form-check-label" for="<?php echo $id; ?>">
                <?php echo $this->label; ?>
            </label>
        </div>

   
  
<?php

        return ob_get_clean();
    }
}
