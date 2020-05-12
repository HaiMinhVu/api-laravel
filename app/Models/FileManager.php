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

    public function filePath($urlencode = false)
    {
        $filePath = '';
        if($this->sitePath()->exists()) {
            $filePath = $this->sitePath->description;
        }
        $fileName = $this->file_name;
        if($urlencode) {
            $fileName = rawurlencode($fileName);
        }
        return $filePath.$fileName;
    }

    public function s3FilePath()
    {
        $s3FilePath = '';
        if($label = optional($this->siteList)->label) {
            $s3FilePath = Str::kebab($this->siteList->label).'/';
        } 
        return $s3FilePath.$this->filePath();
    }

    public function url()
    {
        if($this->siteList()->exists()) {
            $filePath = $this->filePath(true);
            $url = $this->getPublicUrl();
            return "{$url}{$filePath}";
        }
        return null;
    }

    public function getPublicUrl()
    {
        return $this->siteList->url;
    }

    public function syncWithS3()
    {
        return S3::syncFromUrl($this->s3FilePath(), $this->url());
    }
}
