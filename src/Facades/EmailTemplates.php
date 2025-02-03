<?php
// src/Facades/EmailTemplates.php
namespace Codersgarden\MultiLangMailer\Facades;

use Codersgarden\MultiLangMailer\Services\EmailTemplateService;
use Illuminate\Support\Facades\Facade;

class EmailTemplates extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EmailTemplateService::class;
    }
}
