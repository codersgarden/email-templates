<?php

namespace Codersgarden\MultiLangMailer\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{

    protected $table = 'mail_templates';
    protected $fillable = ['identifier'];
    public function translations()
    {
        return $this->hasMany(TemplateTranslation::class, 'mail_template_id');
    }

    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function placeholders()
    {
        return $this->belongsToMany(Placeholder::class, 'placeholder_template');
    }
}
