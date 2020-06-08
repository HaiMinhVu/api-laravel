<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use S3;

class FileManager extends Model
{
    const DEFAULT_DIRECTORY = 'uncategorized';
    const DEFAULT_IMAGE_ID = 16585;

     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'ID';
    }

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

    public function scopeDoesNotExistOnS3($query)
    {
        return $query->where('exists_on_s3', 0);
    }

    public function scopeExistsOnS3($query)
    {
        return $query->where('exists_on_s3', 1);
    }

    public static function defaultImage()
    {
        return self::where('ID', self::DEFAULT_IMAGE_ID)->first()->url();
    }

    public function filePath($urlencode = false)
    {
        $fileName = $this->file_name;
        if($urlencode) {
            $fileName = rawurlencode($fileName);
        }
        return $this->filePathOnly().$fileName;
    }

    public function filePathOnly()
    {
        $filePath = '';
        if($this->sitePath()->exists()) {
            $filePath = $this->sitePath->description;
        }
        return $filePath;
    }

    public function s3FilePath($withFileName = true)
    {
        $filePath = ($withFileName) ? $this->filePath() : $this->filePathOnly();
        return $this->s3FilePathOnly().$filePath;
    }

    public function s3FilePathOnly()
    {
        $s3FilePath = '';
        if($label = optional($this->siteList)->label) {
            $s3FilePath = Str::kebab($this->siteList->label).'/';
        }
        return $s3FilePath;
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

    public function getS3Url()
    {
        return route('file-view', ['key' => $this->s3FilePath()]);
    }

    public function syncWithS3()
    {
        return S3::syncFromUrl($this->s3FilePath(), $this->url());
    }

    public function scopeImages($query)
    {
        return $query->whereIn('site_folder_id', MasterList::IMAGE_IDS);
    }

    public function scopeSpecSheets($query)
    {
        return $query->whereIn('site_folder_id', MasterList::SPEC_SHEET_IDS);
    }

    public function scopeProofOfPurchases($query)
    {
        return $query->whereIn('site_folder_id', MasterList::PROOF_OF_PURCHASE_IDS);
    }

    public function scopeManuals($query)
    {
        return $query->whereIn('site_folder_id', MasterList::MANUAL_IDS);
    }

    public function scopeCatalogs($query)
    {
        return $query->whereIn('site_folder_id', MasterList::CATALOG_IDS);
    }

    public function scopeByType($query, $typeName = null)
    {
       if($typeName && array_key_exists($typeName, MasterList::TYPES)) {
            return $query->whereIn('site_folder_id', MasterList::TYPES[$typeName]);
       }
    }

    public function isImage()
    {
        $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        return in_array($extension, ['png', 'jpg', 'jpeg']);
    }

    public function scopeFuzzyMatch($query, string $search) {
        $columns = ['file_name', 'display_name', 'description'];

        if (!empty(trim($search))) {
            $fuzzySearch = implode("%", str_split($search));
            $fuzzySearch = "%$search%";

            foreach($columns as $column) {
                return $query->where($column, 'like', $fuzzySearch);
            }
        }
    }

    public function scopeByBrand($query, $brand)
    {
        $query->whereHas('siteList', function($q) use ($brand) {
            $q->where('label', 'like', "%{$brand}%");
        });
    }

    public static function handleNewUpload(UploadedFile $file, string $type, string $brand) : self
    {
        $pathInfo = pathinfo($file->getClientOriginalName());
        $fileName = $pathInfo['basename'];
        $checkIfExists = FileManager::where('file_name', $fileName)->first();
        $masterList = MasterList::getByName($type);
        $siteList = SiteList::where('label', 'like', "%{$brand}%")->first();

        if($checkIfExists) {
            $timestamp = Carbon::now()->timestamp;
            $fileName = "{$pathInfo['filename']}_{$timestamp}.{$pathInfo['extension']}";
        }

        $fileManager = new self;
        $fileManager->sitePath()->associate($masterList);
        $fileManager->siteList()->associate($siteList);
        $fileManager->file_name = $fileName;
        $fileManager->sampleurl = '';

        $fileManager->save();

        $response = $file->storeAs($fileManager->s3FilePath(false), $fileManager->file_name, 's3');

        if($response) {
            $fileManager->exists_on_s3 = 1;
            $fileManager->save();
        }

        return $fileManager;
    }
}
