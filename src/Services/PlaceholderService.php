<?php

namespace Codersgarden\MultiLangMailer\Services;

use Codersgarden\MultiLangMailer\Models\Placeholder;

class PlaceholderService
{
    /**
     * Replace placeholders in the given text with provided data.
     *
     * @param string $text
     * @param array $data
     * @return string
     */
    public function replacePlaceholders(string $text, array $data): string
    {
        // Fetch all placeholders from the database
        $placeholders = Placeholder::all()->pluck('name')->toArray();

        foreach ($placeholders as $placeholder) {
            $value = $data[$placeholder] ?? '';
            // Escape to prevent XSS
            $escapedValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            $text = str_replace("{{{$placeholder}}}", $escapedValue, $text);
        }

        // Optionally remove any undefined placeholders
        $text = preg_replace('/{{\s*\w+\s*}}/', '', $text);
      
        return $text;
    }
}
