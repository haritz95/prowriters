<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CommaSeparatedFileExtensions implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $words = explode(',', $value);

        foreach ($words as $word) {
            $word = trim($word);
            if (strpos($word, '.') !== 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'File extensions should be listed with commas, each starting with a dot (.)';
    }
}
