<?php

use Illuminate\Database\Seeder;
use App\Models\V2\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brandNames = [
			"Sightmark",
			"Firefield",
			"Pulsar",
			"Southern Crossbow",
			"12 Survivors",
			"Sellmark",
			"Third Eye",
			"Head Tilt",
			"Eternal",
			"Sellmark Marketing",
			"Kopfjager"
        ];

        $brands = array_map(function($name){
    		return [
    			'name' => $name,
    			'description' => null,
    			'slug' => Str::slug($name)
    		];
    	}, $brandNames);

        DB::connection('cms')->table('brands')->insert($brands);
    }
}
