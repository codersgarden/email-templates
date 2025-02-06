<?php

namespace Codersgarden\MultiLangMailer\Services;

use Codersgarden\MultiLangMailer\Models\Placeholder;
use Illuminate\Support\Facades\Log;

class PlaceholderService
{
    /**
     * Replace placeholders in the given text with provided data.
     *
     * @param string $text
     * @param array $data
     * @return string
     */


     function replacePlaceholders(string $text, array $data): string
    {
        $placeholders = Placeholder::all()->pluck('data_type', 'name')->toArray();

        foreach ($placeholders as $placeholderName => $dataType) {
            $placeholderValue = $data[$placeholderName] ?? '';

            // If the placeholder value is an array, convert it to a JSON string
            if (is_array($placeholderValue)) {
                $placeholderValue = json_encode($placeholderValue);
            }

            if ($dataType == 'url' && filter_var($placeholderValue, FILTER_VALIDATE_URL)) {
                $buttonText = $data['button_text'] ?? 'Click Here';
                $buttonHtml = '
            <div style="text-align: center; margin: 20px 0;">
                <a href="' . htmlspecialchars($placeholderValue, ENT_QUOTES, 'UTF-8') . '" 
                    style="
                        background-color: #007bff; 
                        color: white; 
                        padding: 10px 20px; 
                        text-decoration: none; 
                        border-radius: 5px; 
                        display: inline-block;">
                    ' . htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') . '
                </a>
            </div>';

                $text = str_replace("{{$placeholderName}}", $buttonHtml, $text);
            } else {
                $escapedValue = htmlspecialchars($placeholderValue, ENT_QUOTES, 'UTF-8');
                $text = str_replace("{{$placeholderName}}", $escapedValue, $text);
            }
        }

        // Remove any extra curly brackets remaining in the text
        $text = preg_replace('/[{}]/', '', $text);
        return $text;
    }
}
