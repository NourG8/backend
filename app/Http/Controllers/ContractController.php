<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{

    public function AddDefaultContract()
    {
        $cdd_contract_test = 0;
        $cdi_contract_test = 0;

        $contracts = Contract::all();
        foreach ($contracts as $contract) {
            if($contract['type'] == "CDD" ){
                $cdd_contract_test = 1;
            }
            if($contract['type'] == "CDI" ){
                $cdi_contract_test = 1;
            }
        }
        if($cdi_contract_test == 0 ){
            $contract = new Contract();
            $contract->type = "CDI";
            $contract->isDeleted = 0;
            $contract->save();
        }
        if($cdd_contract_test == 0 ){
            $contract = new Contract();
            $contract->type = "CDD";
            $contract->isDeleted = 0;
            $contract->save();
        }
        return response()->json($contract);
    }

    public function getAllContracts()
    {
        ContractController::AddDefaultContract();
        $contract = Contract::where('isDeleted', '=', 0)->get();
        return $contract;
    }

    public function AddContract(Request $request)
    {
        $liste_contracts = $request;
        $myArray = collect($liste_contracts);
        foreach ($myArray as $c) {
            $contract = new Contract();
            $contract->type= $c['type'];
            $contract->file= $c['file'];
            $contract->isDeleted = 0;
            $contract->save();
        }
        return response()->json([
            'contracts' => $myArray,
            'success' => true
        ], 200);
    }

    public function getOneContract($id)
    {
        $c = Contract::findOrFail($id);
        return response()->json($c);
    }

    public function editContract(Request $request)
    {
        $liste_contracts = $request;
        $myArray = collect($liste_contracts);
        foreach ($myArray as $c) {
            $contract = Contract::findOrFail($c['id']);
            $contract->type= $c['type'];
            $contract->file= $c['file'];
            $contract->save();
        }
        return response()->json([
            'contracts' => $myArray,
            'success' => true
        ], 200);
    }

    public function destroyContract($id)
    {
        // $c = Contract::findOrFail($id);
        // if($c->delete());
        // return response('success',200);
        $contract = Contract::findOrFail($id);
        $contract->isDeleted= 1;
        $contract->save();
        return response('success',200);
    }

    function updateContract(Request $request,$id)
    {
        // 1er methode to upload file
        $validatedData = $request->validate([
            'file' => 'required',
           ]);

           $name = null;
           if($request->file('file')){
                $name = "Contrat_" . $request->input('type') .".docx";
                $path = $request->file('file')->move('contract',$name );
           }

        $contract = Contract::findOrFail($id);
        $contract->type= $request->input('type');
        $contract->file= $name;
        $contract->isDeleted = 0;
        $contract->save();
        return response()->json([
            'file'=>$request->file('file'),
            'contract' => $contract,
            'success' => true
        ], 200);
    }

    function uploadContract(Request $request)
    {
        // 1er methode to upload file
        $validatedData = $request->validate([
            'file' => 'required',
           ]);
        $name = "";
        if($request->file('file')){
             $name = "Contrat_" . $request->input('type') .".docx";
             $path = $request->file('file')->move('contract',$name);
        }

        $contract = new Contract();
        $contract->type= $request->input('type');
        if($name != ""){
            $contract->file= $name;
       }

        $contract->isDeleted = 0;
        $contract->save();
        return response()->json([
            'contract' => $contract,
            'success' => true
        ], 200);
    }

    function downloadContract($id)
    {
        // 1er methode to download file
        $contract = Contract::findOrFail($id);
        $file_name = "contract\\" .$contract['file'];
        $path =  public_path($file_name);
        return response()->download($path);
    }

}
