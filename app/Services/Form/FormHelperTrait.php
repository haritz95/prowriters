<?php

namespace App\Services\Form;


use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;

trait FormHelperTrait
{    

    private function getBeforeElement()
    {
        ob_start(); ?>
        <div class="<?php echo ($this->column) ? 'col-md-'.$this->column : 'mb-3'; ?>">
            <label class="form-label"><?php echo $this->label; ?> <?php if ($this->required) { ?> <span class="required">*</span> <?php } ?></label>
        <?php
        return ob_get_clean();
    }

    private function getAfterElement()
    {
        ob_start(); ?>
            <div class="invalid-feedback d-block"><?php echo $this->showError($this->name); ?></div>
        </div>
    <?php
        return ob_get_clean();
    }

    private function getErrors()
    {
        return Session::get('errors', new MessageBag);
    }

    private function showErrorClass($key)
    {
        $errors = $this->getErrors();

        if ($errors->has($key)) {
            return 'is-invalid';
        }
    }

    private function showError($key)
    {
        $errors = $this->getErrors();

        if ($errors->has($key)) {
            return $errors->first($key);
        }
    }

    function setValue($name, $model)
    {
        return old($name, optional($model)->$name);
        // //$s = session('redirect_with_validation_error');
        // $errors = $this->getErrors();

        // if ($errors->any()) {
        //     return old($name, optional($model)->$name);
        // }

        // return (isset($object->$key)) ? $object->$key : old($key, $default);
    }


    /**
     * Returns HTML escaped variable.
     *
     * @param   mixed   $var        The input string or array of strings to be escaped.
     * @param   bool    $double_encode  $double_encode set to FALSE prevents escaping twice.
     * @return  mixed           The escaped string or array of strings as a result.
     */
    function html_escape($var, $double_encode = TRUE)
    {
        if (empty($var)) {
            return $var;
        }

        $charset = 'utf-8';

        if (is_array($var)) {
            array_walk_recursive($var, [$this, '_html_escape_callback'], array($charset, $double_encode));
            return $var;
        }

        return htmlspecialchars($var, ENT_QUOTES, $charset, $double_encode);
    }

    function _html_escape_callback(&$value, $key, $options)
    {
        $value = htmlspecialchars($value, ENT_QUOTES, $options[0], $options[1]);
    }



    // ------------------------------------------------------------------------


    /**
     * Multi-select menu
     *
     * @param	string
     * @param	array
     * @param	mixed
     * @param	mixed
     * @return	string
     */
    function form_multiselect($name = '', $options = array(), $selected = array(), $extra = '')
    {
        $extra = $this->_attributes_to_string($extra);
        if (stripos($extra, 'multiple') === FALSE) {
            $extra .= ' multiple="multiple"';
        }

        return $this->form_dropdown($name, $options, $selected, $extra);
    }


    // --------------------------------------------------------------------


    /**
     * Drop-down Menu
     *
     * @param	mixed	$data
     * @param	mixed	$options
     * @param	mixed	$selected
     * @param	mixed	$extra
     * @return	string
     */
    function form_dropdown($data = '', $options = array(), $selected = array(), $extra = '')
    {
        $defaults = array();

        if (is_array($data)) {
            if (isset($data['selected'])) {
                $selected = $data['selected'];
                unset($data['selected']); // select tags don't have a selected attribute
            }

            if (isset($data['options'])) {
                $options = $data['options'];
                unset($data['options']); // select tags don't use an options attribute
            }
        } else {
            $defaults = array('name' => $data);
        }

        is_array($selected) or $selected = array($selected);
        is_array($options) or $options = array($options);

        // If no selected state was submitted we will attempt to set it automatically
        if (empty($selected)) {
            if (is_array($data)) {
                if (isset($data['name'], $_POST[$data['name']])) {
                    $selected = array($_POST[$data['name']]);
                }
            } elseif (isset($_POST[$data])) {
                $selected = array($_POST[$data]);
            }
        }

        $extra = $this->_attributes_to_string($extra);

        $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select ' . rtrim($this->_parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

        foreach ($options as $key => $val) {
            $key = (string) $key;

            if (is_array($val)) {
                if (empty($val)) {
                    continue;
                }

                $form .= '<optgroup label="' . $key . "\">\n";

                foreach ($val as $optgroup_key => $optgroup_val) {
                    $sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
                    $form .= '<option value="' . $this->html_escape($optgroup_key) . '"' . $sel . '>'
                        . (string) $optgroup_val . "</option>\n";
                }

                $form .= "</optgroup>\n";
            } else {
                $form .= '<option value="' . $this->html_escape($key) . '"'
                    . (in_array($key, $selected) ? ' selected="selected"' : '') . '>'
                    . (string) $val . "</option>\n";
            }
        }

        return $form . "</select>\n";
    }



    // ------------------------------------------------------------------------


    /**
     * Attributes To String
     *
     * Helper function used by some of the form helpers
     *
     * @param	mixed
     * @return	string
     */
    function _attributes_to_string($attributes)
    {
        if (empty($attributes)) {
            return '';
        }

        if (is_object($attributes)) {
            $attributes = (array) $attributes;
        }

        if (is_array($attributes)) {
            $atts = '';

            foreach ($attributes as $key => $val) {
                $atts .= ' ' . $key . '="' . $val . '"';
            }

            return $atts;
        }

        if (is_string($attributes)) {
            return ' ' . $attributes;
        }

        return FALSE;
    }



    // ------------------------------------------------------------------------


    /**
     * Parse the form attributes
     *
     * Helper function used by some of the form helpers
     *
     * @param	array	$attributes	List of attributes
     * @param	array	$default	Default values
     * @return	string
     */
    function _parse_form_attributes($attributes, $default)
    {
        if (is_array($attributes)) {
            foreach ($default as $key => $val) {
                if (isset($attributes[$key])) {
                    $default[$key] = $attributes[$key];
                    unset($attributes[$key]);
                }
            }

            if (count($attributes) > 0) {
                $default = array_merge($default, $attributes);
            }
        }

        $att = '';

        foreach ($default as $key => $val) {
            if ($key === 'value') {
                $val = $this->html_escape($val);
            } elseif ($key === 'name' && !strlen($default['name'])) {
                continue;
            }

            $att .= $key . '="' . $val . '" ';
        }

        return $att;
    }
}
