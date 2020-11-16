<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = ['company_name', 'company_email', 'company_phone', 'website', 'address', 'timezone', 'latitude', 'longitude', 'locale', 'logo', 'system_update'];
    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute()
    {
        if (is_null($this->logo)) {
            return asset('app-logo.png');
        }
        return asset_url('app-logo/' . $this->logo);
    }
}
