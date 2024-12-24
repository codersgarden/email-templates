<?php

namespace Codersgarden\MultiLangMailer\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePlaceholder extends Model
{
    protected $table = 'placeholder_template';
    protected $fillable = ['placeholder_id', 'mail_template_id'];

    public function placeholder()
    {
        return $this->belongsTo(Placeholder::class);
    }

    public function template()
    {
        return $this->belongsTo(MailTemplate::class);
    }
}
