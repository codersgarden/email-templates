<?php

namespace Codersgarden\MultiLangMailer\Services;

use Codersgarden\MultiLangMailer\Models\Placeholder;

// use Codersgarden\MultiLangMailer\Models\Placeholder;

class PlaceholderService
{
    /**
     * Replace placeholders in the given text with provided data.
     *
     * @param string $text
     * @param array $data
     * @return string
     * 
     */ public function replacePlaceholders(string $text, array $data): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace("{{{$key}}}", htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $text);
        }
        return $text;
    }
    
}
