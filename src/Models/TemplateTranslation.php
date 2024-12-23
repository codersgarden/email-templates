<?php

namespace Codersgarden\MultiLangMailer\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateTranslation extends Model
{
    protected $fillable = ['locale', 'subject', 'body'];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
