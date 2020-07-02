<?php

namespace App\Http\Controllers\V2\Form;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\V2\Form as FormResource;
use App\Models\V2\{
    Form,
    FormField,
    FormFieldOption,
    FormFieldType
};

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('api')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Form::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $form = Form::create(['name' => $data['name']]);
        if($request->has('fields')) {
            foreach($data['fields'] as $field) {
                $type = FormFieldType::getByName($field['type']);
                $formField = new FormField;
                $formField->name = $field['name'];
                $formField->description = $field['description'];
                $formField->required = array_key_exists('required', $field) ? $field['required'] : 0;
                $formField->form_id = $form->id;
                $formField->form_field_type_id = $type->id ?? 1;
                $formField->save();
                if(array_key_exists('options', $field)) {
                    foreach($field['options'] as $option) {
                        $formFieldOption = new FormFieldOption;
                        $formFieldOption->name = $option['name'];
                        $formFieldOption->value = $option['value'];
                        $formFieldOption->form_field_id = $formField->id;
                        $formFieldOption->save();
                    }
                }
            }
        }
        return $form->load(['fields.options']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form = Form::with(['fields.options', 'fields.type']);
        if(is_numeric($id)) {
            $form = $form->find($id);
        } else {
            $form = $form->where('name', $id)->latest()->first();
        }
        $data = (new FormResource($form))->jsonSerialize();
        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
