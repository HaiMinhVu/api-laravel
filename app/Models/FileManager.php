<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use S3;

class FileManager extends Model
{
    const DEFAULT_DIRECTORY = 'uncategorized';

    protected $table='file_manager';

    public $timestamps = false;

    protected $with = ['siteList', 'sitePath'];

    public function siteList()
    {
        return $this->belongsTo(SiteList::class, 'site_id');
    }

    public function sitePath()
    {
        return $this->belongsTo(MasterList::class, 'site_folder_id');
    }

    public static function defaultImage()
    {
        return self::where('ID', 0)->first()->url();
    }

    public function filePath()
    {
        return "{$this->sitePath->description}{$this->file_name}";
    }

    public function s3FilePath()
    {
        if($label = optional($this->siteList)->label) {
            $manufacturerPrefix = Str::kebab($this->siteList->label);
        } else {
            $manufacturerPrefix = self::DEFAULT_DIRECTORY;
        }
        $filePath = $this->filePath();
        return "{$manufacturerPrefix}/{$filePath}";
    }

    public function url()
    {
        if($this->siteList()->exists()) {
            $filePath = $this->filePath();
            $url = $this->getPublicUrl();
            return "{$url}{$filePath}";
        }
        return null;
    }

    public function getPublicUrl()
    {
        $url = $this->siteList->url;
        return str_replace('pulsarnv', 'old.pulsarnv', $url);
    }

    public function syncWithS3()
    {
        return S3::syncFromUrl($this->s3FilePath(), $this->url());
    }
}
