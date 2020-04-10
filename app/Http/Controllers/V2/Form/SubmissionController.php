<?php

namespace App\Http\Controllers\V2\Form;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V2\FormSubmissionRequest;
use App\Models\V2\{
	FormSubmission,
	FormFieldSubmission,
	FormFieldSelectedOption
};

class SubmissionController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FormSubmission::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormSubmissionRequest $request)
    {
		if($validated = $request->validated()) {

			$formSubmission = new FormSubmission;
			$formSubmission->form_id = $validated['form_id'];	
			$formSubmission->save();	

			foreach($validated['fields'] as $field) {
				$formFieldSubmission = new FormFieldSubmission;
				$formFieldSubmission->form_submission_id = $formSubmission->id;
				$formFieldSubmission->form_field_id = $field['id'];
				$formFieldSubmission->name = '';
				$formFieldSubmission->save();
				if(array_key_exists('selected_option_id', $field)) {
					$formFieldSelectedOption = new FormFieldSelectedOption;
					$formFieldSelectedOption->form_field_submission_id = $formFieldSubmission->id;
					$formFieldSelectedOption->form_field_option_id = $field['selected_option_id'];
					$formFieldSelectedOption->save();
				}
			}

			return $formSubmission->fresh();
		}	
		return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Form::with(['fields.options'])->find($id);
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
