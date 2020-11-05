<?php

namespace App\Http\Controllers\V2\Form;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V2\FormSubmissionRequest;
use App\Models\V2\{
    Brand,
    File,
    FileType,
    FormSubmission,
    FormField,
    FormFieldSubmission,
    FormFieldSelectedOption,
    FormFieldType,
    FormFieldValue
};
use Carbon\Carbon;
use Log;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('api')->except('store');
        $this->middleware('filter-origin')->only('index');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $formSubmissionBuilder = FormSubmission::with(['fieldSubmissions.formField', 'fieldSubmissions.selectedOption.option', 'brand']);
        if($request->has('form-id')) {
            $formSubmissionBuilder = $formSubmissionBuilder->where('form_id', $request->query('form-id'));
        }
        if($request->has('form-slug')) {
            $formSlug = $request->query('form-slug');
            $formSubmissionBuilder = $formSubmissionBuilder->whereHas('form', function($q) use ($formSlug) {
                $q->where('name', $formSlug);
            })->orderBy('form_id', 'DESC');
        }

        if($request->has('from-date')) {
            $fromDate = Carbon::parse($request->get('from-date'));
            $formSubmissionBuilder = $formSubmissionBuilder->where('created_at', '>=', $fromDate);
        }

        if($request->has('to-date')) {
            $toDate = Carbon::parse($request->get('to-date'));
            $formSubmissionBuilder = $formSubmissionBuilder->where('created_at', '<=', $toDate);
        }

        if($request->has('brand')) {
            $formSubmissionBuilder->where('brand_id', $request->brand);
        }

        if($request->has('all')) {
            return $formSubmissionBuilder->get();
        }

        return $formSubmissionBuilder->paginate(10);
        // return FormSubmission::all();
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(FormSubmissionRequest $request)
    {

        Log::info('test');

        if($request->validated()) {
            $data = $request->all();
            $brand = Brand::where('slug', $data['brand'])->first();

            $formSubmission = new FormSubmission;
            $formSubmission->form_id = $data['form_id'];
            $formSubmission->brand_id = ($brand) ? $brand->id : null;

            $formSubmission->save();

            foreach($data['fields'] as $field) {
                // TODO: refactor
                $type = FormField::getTypeById($field['id']);

                $formFieldSubmission = new FormFieldSubmission;
                $formFieldSubmission->form_submission_id = $formSubmission->id;
                $formFieldSubmission->form_field_id = (int) $field['id'];
                $formFieldSubmission->name = '';
                $formFieldSubmission->save();
                if(in_array($type, FormFieldType::SELECTABLE)) {
                    $formFieldSelectedOption = new FormFieldSelectedOption;
                    $formFieldSelectedOption->form_field_submission_id = $formFieldSubmission->id;
                    $formFieldSelectedOption->form_field_option_id = (int) $field['value'];
                    $formFieldSelectedOption->save();
                } else {
                    $formFieldValue = new FormFieldValue;
                    $formFieldValue->name = $field['value'] ?? '';
                    $formFieldValue->form_field_id = (int) $field['id'];
                    $formFieldValue->form_field_submission_id = $formFieldSubmission->id;
                    $formFieldValue->save();
                }
            }
            if(array_key_exists('files', $data)) {
                foreach($data['files'] as $field_id => $file) {
                    if($file && $file != 'undefined') {
                        $type = FormField::getTypeById($field_id);

                        $formFieldSubmission = new FormFieldSubmission;
                        $formFieldSubmission->form_submission_id = $formSubmission->id;
                        $formFieldSubmission->form_field_id = (int) $field_id;
                        $formFieldSubmission->name = '';
                        $formFieldSubmission->save();

                        $file = File::handleNewUpload($file, FileType::FORM_UPLOAD, $brand->id);

                        $formFieldSubmission->files()->attach($file->id);
                    }
                }
            }
            sleep(1);
            $formSubmission->touch();
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
    public function show(Request $request, $id)
    {
        return FormSubmission::find($id);
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
