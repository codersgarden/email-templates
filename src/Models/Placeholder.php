<?php

namespace Codersgarden\MultiLangMailer\Models;

use Illuminate\Database\Eloquent\Model;

class Placeholder extends Model
{
    protected $fillable = ['name', 'description', 'data_type'];

    /**
     * The templates that belong to the placeholder.
     */
    public function templates()
    {
        return $this->belongsToMany(MailTemplate::class, 'placeholder_template');
    }
}
