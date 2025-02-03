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
     */
    public function replacePlaceholders(string $text, array $data): string
    {
        $placeholders = Placeholder::all()->pluck('name')->toArray();

        foreach ($placeholders as $placeholder) {
            $value = $data[$placeholder] ?? '';

            if ($placeholder === 'url' && filter_var($value, FILTER_VALIDATE_URL)) {
                $buttonHtml = '<a href="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '" target="_blank" 
                        style="display: inline-block; padding: 10px 15px; background-color: #007bff; 
                        color: #fff; text-decoration: none; border-radius: 5px;">
                        Click Here
                     </a>';
                $text = str_replace("{{{$placeholder}}}", $buttonHtml, $text);
            } else {
                $escapedValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $text = str_replace("{{{$placeholder}}}", $escapedValue, $text);
            }
        }

        // Remove any undefined placeholders safely
        $text = preg_replace('/{{\s*\w+\s*}}/', '', $text);
        return $text;
    }
}
