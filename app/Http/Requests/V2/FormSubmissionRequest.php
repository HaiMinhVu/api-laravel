<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\V2\{
    Form,
    FormField
};

class FormSubmissionRequest extends FormRequest
{
    protected $rules;
    protected $fields;

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $test = collect($this->request->all()['fields'])->map(function($field){
            $data = (object)[];
            $data->model = FormField::find($field['id']);
            $data->field = $field;
            return $data;
        });
        $this->fields = Form::with('fields.type')->find($this->form_id)->fields;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules = [
            "form_id" => "integer|required", 
            "brand" => "string|required"
        ];

        $this->fields->map(function($field, $idx) {
            $this->addFieldRules($field, $idx);
        })->toArray();

        return $this->rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return $this->fields->mapWithKeys(function($field, $idx) {
            return ["fields.{$idx}.{$this->attributeByType($field)}" => $field->description];
        })->toArray();
    }

    protected function addFieldRules(FormField $field, $idx)
    {
        if($field->required) {
            $this->rules["fields.{$idx}.id"] = "integer|required";
            $this->rules["fields.{$idx}.value"] = "required";
        }
    }

    protected function attributeByType(FormField $field) : string
    {
        return in_array($field->type->name, ['text', 'textarea']) ? 'value' : 'selected_option_id';
    }
}
