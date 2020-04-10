<?php

use Illuminate\Database\Seeder;

class FormFieldTypeSeeder extends Seeder
{
	const DEFAULT_FORM_FIELD_TYPES = ['text', 'select', 'textarea', 'radio', 'checkbox'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$types = array_map(function($type){
    		return ['name' => $type];
    	}, self::DEFAULT_FORM_FIELD_TYPES);

        DB::connection('cms')->table('form_field_types')->insert($types);
    }
}
