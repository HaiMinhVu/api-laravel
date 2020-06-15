<?php

namespace App\Models\V2;

use App\Pivots\V2\FileProduct;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use S3;

class File extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];


    // Relations

    public function brand()
    {
		return $this->belongsTo(Brand::class);
	}

	public function products()
    {
		return $this->hasMany(Product::class)
					->using(FileProduct::class);
	}

	public function type()
	{
		return $this->belongsTo(FileType::class, 'file_type_id');
	}

	public function category()
	{
		return $this->hasOne(Category::class);
	}

    public function formFieldSubmissions()
    {
        return $this->belongsToMany(FormFieldSubmission::class);
    }


	// Methods

	public function remotePath()
	{
		return "{$this->brand->slug}/{$this->type->remote_path}";
	}

    public function fullRemotePath()
    {
        return $this->remotePath().'/'.$this->name;
    }

    public static function handleNewUpload(UploadedFile $uploadedFile, string $type, int $brandId) : ?self
    {
        $pathInfo = pathinfo($uploadedFile->getClientOriginalName());
        $fileName = $pathInfo['basename'];
        $checkIfExists = File::where('name', $fileName)->first();
        $fileType = FileType::getByType($type);
        $brand = Brand::find($brandId);

        if($checkIfExists) {
            $timestamp = Carbon::now()->timestamp;
            $fileName = "{$pathInfo['filename']}_{$timestamp}.{$pathInfo['extension']}";
        }

        $file = new self;
        $file->type()->associate($fileType);
        $file->brand()->associate($brand);
        $file->name = $fileName;
        $file->save();

        if($response = $uploadedFile->storeAs($file->remotePath(), $file->name, 's3')) {
            return $file;
        }

        return null;
    }
}
