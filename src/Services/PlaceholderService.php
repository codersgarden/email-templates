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
        // Fetch placeholders only once and map them by name to data_type
        $placeholders = Placeholder::all()->pluck('data_type', 'name')->toArray();

        // Loop through the data and replace placeholders
        foreach ($data as $key => $value) {
            // Skip button data handling here, it's done separately
            if ($key === 'button') {
                continue;
            }

            // Check if the key exists in the placeholders
            if (isset($placeholders[$key])) {
                // Handle simple replacements like username, appname, email
                $text = str_replace("{$key}", htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $text);
            }
        }

        // Decode 'button' if it's a valid JSON string
        if (isset($data['button']) && is_string($data['button'])) {
            $data['button'] = json_decode($data['button'], true);
        }

        // Process buttons if 'button' is set and is an array
        if (!empty($data['button']) && is_array($data['button'])) {
            foreach ($data['button'] as $buttonData) {
                // Get the button text or default to 'Click Here'
                $buttonText = $buttonData['button_text'] ?? 'Click Here';

                foreach ($buttonData as $key => $value) {
                    // Skip 'button_text' as it's not a placeholder
                    if ($key === 'button_text') {
                        continue;
                    }

                    // Check if the key exists in the placeholders
                    if (isset($placeholders[$key])) {
                        $placeholderType = $placeholders[$key];

                        // Handle 'url' type placeholders with validation
                        if ($placeholderType === 'url' && filter_var($value, FILTER_VALIDATE_URL)) {
                            // Generate button HTML
                            $buttonHtml = '
                              <div style="text-align: center; margin: 20px 0;">
                                  <a href="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '" 
                                     style="background-color: #007bff; color: white; padding: 10px 20px; 
                                            text-decoration: none; border-radius: 5px; display: inline-block;">
                                      ' . htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') . '
                                  </a>
                              </div>';

                            // Replace the placeholder in the text
                            $text = str_replace("{$key}", $buttonHtml, $text);
                        } else {
                            // Log invalid URL or unsupported type
                            Log::warning("Invalid URL or unsupported type for placeholder: {$key}");
                        }
                    } else {
                        // Log warning for missing placeholder
                        Log::warning("No placeholder found for key: {$key}");
                    }
                }
            }
        } 

        // Remove any remaining curly brackets (unmatched placeholders)
        return preg_replace('/[{}]/', '', $text);
    }

    //  function replacePlaceholders(string $text, array $data): string
    // {
    //     $placeholders = Placeholder::all()->pluck('data_type', 'name')->toArray();

    //     foreach ($placeholders as $placeholderName => $dataType) {
    //         $placeholderValue = $data[$placeholderName] ?? '';

    //         // If the placeholder value is an array, convert it to a JSON string
    //         if (is_array($placeholderValue)) {
    //             $placeholderValue = json_encode($placeholderValue);
    //         }

    //         if ($dataType == 'url' && filter_var($placeholderValue, FILTER_VALIDATE_URL)) {
    //             $buttonText = $data['button_text'] ?? 'Click Here';
    //             $buttonHtml = '
    //         <div style="text-align: center; margin: 20px 0;">
    //             <a href="' . htmlspecialchars($placeholderValue, ENT_QUOTES, 'UTF-8') . '" 
    //                 style="
    //                     background-color: #007bff; 
    //                     color: white; 
    //                     padding: 10px 20px; 
    //                     text-decoration: none; 
    //                     border-radius: 5px; 
    //                     display: inline-block;">
    //                 ' . htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') . '
    //             </a>
    //         </div>';

    //             $text = str_replace("{{$placeholderName}}", $buttonHtml, $text);
    //         } else {
    //             $escapedValue = htmlspecialchars($placeholderValue, ENT_QUOTES, 'UTF-8');
    //             $text = str_replace("{{$placeholderName}}", $escapedValue, $text);
    //         }
    //     }

    //     // Remove any extra curly brackets remaining in the text
    //     $text = preg_replace('/[{}]/', '', $text);
    //     return $text;
    // }
}
