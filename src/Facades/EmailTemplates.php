<?php
// src/Facades/EmailTemplates.php
namespace Codersgarden\MultiLangMailer\Facades;

use Illuminate\Support\Facades\Facade;

class EmailTemplates extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EmailTemplateService::class;
    }
}
