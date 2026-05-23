<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TerimaDomainPortofolio implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $host = parse_url($value, PHP_URL_HOST);
        $terima_domain = config('portofolio.terima_domain');

        if (!$host) {
            $fail('Link portofolio tidak valid.');
            return;
        }

        foreach($terima_domain as $domain)
        {
            if($host == $domain || str_ends_with($host, '.' . $domain))
            {
                return;
            }
        }
        $fail('Domain link portofolio tidak diizinkan.');
    }
}
