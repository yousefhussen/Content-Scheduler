<?php

namespace Modules\Post\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class NotInThePast implements Rule
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

        $date = Carbon::createFromFormat('Y-m-d H:i:s', $value);

        return $date->isFuture() || $date->equalTo(Carbon::now());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must not be in the past.';
    }
}
