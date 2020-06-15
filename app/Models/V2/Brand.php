<?php

namespace App\Models\V2;

use S3;

class Brand extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'slug'];


    // Relations

    public function products()
    {
		return $this->hasMany(Product::class);
	}

	public function files()
	{
		return $this->hasMany(File::class);
	}

	public function categoryHierarchies()
	{
		return $this->hasMany(CategoryHierarchy::class);
	}

    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function logoUrl() : ?string
    {
        $url = null;
        $key = 'logos/';
        switch ($this->slug) {
            case '12-survivors':
                $key = $key.'12s-main-logo.png';
                break;
            case 'firefield':
                $key = $key.'firefield-logo.png';
                break;
            case 'kopfjager':
                $key = $key.'kopflager_logo.png';
                break;
            case 'pulsar':
                $key = $key.'pulsar_logo.png';
                break;
            case 'sightmark':
                $key = $key.'sightmark_logo.png';
                break;
            default:
                $key = null;
                break;
        }
        if($key) {
            $url = S3::imageLink($key, 200);
        }
        return $url;
    }
}
