<?php

namespace App\Services\Form;

use App\Services\Form\FormHelperTrait;
use App\Services\Form\FormElementsTrait;


class FormService
{

    use FormHelperTrait;
    use FormElementsTrait;

    private $type;
    private $name;
    private $label;
    private $model;
    private $classNames;
    private $id;
    private $required;
    private $column;
    private $options;


    function open($name, $model, $primaryKey, $createRoute, $updateRoute)
    {
        ob_start();
    ?>
        <form role="form" class="form-horizontal px-4 py-3" enctype="multipart/form-data" action="<?php echo isset($model->{$primaryKey}) ? route($updateRoute, $model->{$primaryKey}) : route($createRoute) ?>" method="POST" autocomplete="off">
            <?php echo csrf_field();
            if (isset($model->{$primaryKey})) {
                echo method_field('PATCH');
            }
            ?>
    <?php

        return ob_get_clean();
    }

    function close()
    {
        ob_start();
    ?>
        </form>
    <?php

        return ob_get_clean();
    }

    function make($type, $name, $label, $model)
    {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->model = $model;
        return $this;
    }

    function addClass($class)
    {
        $this->classNames = $class;
        return $this;
    }

    function addId($id)
    {
        $this->id = $id;
        return $this;
    }

    function asColumn($column)
    {
        $this->column = $column;
        return $this;
    }

    function isRequired()
    {
        $this->required = TRUE;
        return $this;
    }

    function options($options)
    {
        $this->options = $options;
        return $this;
    }

    function withoutSearch()
    {
        $this->withoutSearch = TRUE;
        return $this;
    }

    function render()
    {

        switch ($this->type) {
            case 'input':
                $html = $this->input();
                break;
            case 'textarea':
                $html = $this->textarea();
                break;
            case 'select':
                $html = $this->select();
                break;
            case 'checkbox':
                $html = $this->checkbox();
                break;
            default:
                $this->html = $this->input();
                break;
        }
        $this->type =  $this->name =  $this->label = $this->model = $this->classNames = $this->id = $this->required = $this->column = $this->options = null;
        return $html;
    }
}
