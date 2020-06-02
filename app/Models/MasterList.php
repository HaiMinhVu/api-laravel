<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterList extends Model
{
	// Temporary hard code for V1
	const IMAGE_IDS = [14, 10];
    const PROOF_OF_PURCHASE_IDS = [23, 22];
    const MANUAL_IDS = [15, 11];
    const SPEC_SHEET_IDS = [16, 12];
    const CATALOG_IDS = [31, 30];

    const TYPES = [
    	'image' => self::IMAGE_IDS,
    	'catalog' => self::CATALOG_IDS,
    	'manual' => self::MANUAL_IDS,
    	'proof_of_purchase' => self::PROOF_OF_PURCHASE_IDS,
    	'spec_sheet' => self::SPEC_SHEET_IDS
    ];

    protected $table='master_list';

    public $timestamps = false;

    public static function getIdByName(string $name)
    {
    	if(array_key_exists($name, self::TYPES)) {
    		$ids = self::TYPES[$name];
    		return $ids[0];
    	}
    	return null;
    }

    public static function getByName(string $name)
    {
    	$id = self::getIdByName($name);
    	return self::find($id);
    }
}
