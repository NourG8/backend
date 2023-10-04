<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Intervention\Image\Facades\Image;


class CompanyController extends Controller
{

    static public function getCompany()
    {
        $companies = Company::all();
        return $companies;
    }

    public function AddCompany(Request $request)
    {
        $company = new Company();
        $company->name = $request->input('name');
        $company->country = $request->input('country');
        $company->email = $request->input('email');
        $company->phone = $request->input('phone');
        $company->creation_date = $request->input('creation_date');
        $company->logo = $request->input('logo');
        $company->description = $request->input('description');
        $company->min_cin = $request->input('min_cin');
        $company->max_cin = $request->input('max_cin');
        $company->min_passport = $request->input('min_passport');
        $company->max_passport = $request->input('max_passport');
        $company->nationality = $request->input('nationality');
        $company->regimeSocial = $request->input('regimeSocial');
        $company->color = $request->input('color');
        $company->color2 = $request->input('color2');
        $company->type = $request->input('type');
        $company->typeTeletravail = $request->input('typeTeletravail');
        $company->startTime = $request->input('startTime');
        $company->endTime = $request->input('endTime');
        $company->max_teletravail = $request->input('max_teletravail');
        $company->status = "Active";
        $company->save();
        return response()->json([
            'company' => $company,
            'success' => true
        ], 200);
    }

    public function editCompany(Request $request,$id)
    {
        $company = Company::findOrFail($id);
        $company->regimeSocial =null;
        $company->name = $request->input('name');
        $company->country = $request->input('country');
        $company->email = $request->input('email');
        $company->phone = $request->input('phone');
        $company->creation_date = $request->input('creation_date');
        $company->logo = $request->input('logo');
        $company->description = $request->input('description');
        $company->min_cin = $request->input('min_cin');
        $company->max_cin = $request->input('max_cin');
        $company->min_passport = $request->input('min_passport');
        $company->max_passport = $request->input('max_passport');
        $company->status = $request->input('status');
        $company->nationality = $request->input('nationality');
        $company->color = $request->input('color');
        $company->color2 = $request->input('color2');
        $company->typeTeletravail = $request->input('typeTeletravail');
        $company->startTime = $request->input('startTime');
        $company->endTime = $request->input('endTime');
        $company->max_teletravail = $request->input('max_teletravail');
        $tableau1 = [];
        $tableau2 = [];
        $tableau1 = $request->input('tableau_get');
        $tableau2 = $request->input('tableau_add');

        $tab  = [];
        if($tableau1 != []){
            foreach ($tableau1 as $value){
                array_push($tab,$value);
                }
            }
        if($tableau2 != []){
        foreach ($tableau2 as $value){
            array_push($tab,$value);
            }
        }
        $company->regimeSocial = $tab;
        $company->save();
        return response()->json([
            'company' => $company,
            'success' => true
        ], 200);
    }

    public function ChangePhoto(Request $request,$id){

        $extension = explode('/', explode(':', substr($request->input('base64string'), 0, strpos($request->input('base64string'), ';')))[1])[1];
        $replace = substr($request->input('base64string'), 0, strpos($request->input('base64string'), ',')+1);
        $file = str_replace($replace, '', $request->input('base64string'));
        $decodedFile = str_replace(' ', '+', $file);
        $name =  Str::random(5) . time() .'.'. $extension;

        Storage::disk('public')->put("logo/".$name, base64_decode($decodedFile));

        $company = Company::findOrFail($id);
        $company->logo = $name;
        $company->save();

        return response()->json([
            'company' => $company,
            'success' => true
        ], 200);
    }

    public function AddRegimeSocial(Request $request)
    {
        $company = new Company();
        $company->regimeSocial = $request->input('regimeSocial');
        $company->save();

        return response()->json([
            'company' => $company,
            'success' => true
        ], 200);
    }

}
