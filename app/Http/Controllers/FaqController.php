<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqDepartment;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class FaqController extends Controller
{
    public function getAllFaqs(Request $request)
    {
        $Faqs = Faq::where([['faqs.is_deleted','=',0],['faqs.title','like','%'.$request->search.'%']])->orwhere([['faqs.is_deleted','=',0],['faqs.tags','like','%'.$request->search.'%']])->with([
            'departments' =>[
                'department'
          ]
        ])->get();
        // $Faqs = Faq::where([['faqs.is_deleted','=',null]])->with([
        //     'departments' =>[
        //         'department'
        //   ]
        // ])->get();

        $tab= [];
        $test= [];
        $obj =null;
        $inter ;
        foreach ($Faqs as $faq) {
            $obj['id'] = $faq['id'];
            $obj['title'] = $faq['title'];
            $obj['response'] = $faq['response'];
            $obj['tags'] = $faq['tags'];
            $obj['departments'] =[];
            foreach ($faq['departments'] as $dep) {
                $inter['value']=$dep['department_id'];
                $inter['text']=$dep['department']['departmentName'];
                   array_push($obj['departments'],$inter );
                   $tab=[];
            }
            array_push($test, $obj);
            $tab = [];
        }
        // return response()->json($test);
        return response($test);
    }

    public function AddFaq(Request $request)
    {
        $faq = new Faq();
        $faq->title = $request->input('title');
        $faq->response = $request->input('response');
        $faq->tags = $request->input('tags');
        $faq->etat = $request->input('etat');
        $faq->save();
        $tab = [];
        $tab=$request->input('department_id');
        for ($i=0; $i <count( $tab) ; $i++) {
          $faqDep = new FaqDepartment();
          $faqDep->faq_id = $faq->id;
          $faqDep->department_id = $tab[$i];
          $faqDep->save();
        }
        return response()->json([
            'faq' => $faq,
            'success' => true
        ], 200);
    }


    public function getOneFaq($id)
    {
        $faq = Faq::findOrFail($id);
        return response()->json($faq);
    }

    public function editFaq(Request $request,$id)
    {
        $faq = Faq::findOrFail($id);
        $faq->title= $request->title;
        $faq->response= $request->response;
        $tab1=[];
        $tab1= $faq->tags;
        $faq->tags = $request->tags;
        $faq->etat = $request->etat;

        DB::table('faq_departments')->where('faq_id',$id)->delete();
        $faq->save();

        $tab = [];
        $tab= $request->input('departments');
        for ($i=0; $i <count( $tab) ; $i++) {
          $faqDep = new FaqDepartment();
          $faqDep->faq_id = $faq->id;
          $faqDep->department_id = $tab[$i];
          $faqDep ->save();
        }
        return response()->json([
            'faq' => $request->tags,
            'success' => true
        ], 200);
    }

    public function destroyFaq($id)
    {
        $faq = DB::table('faqs')
        ->leftJoin('faq_departments', 'faq_departments.faq_id', '=', 'faqs.id')
        ->select('faqs.*')
        ->where([
            ['faqs.is_deleted', '=', 0],
            ['faq_departments.is_deleted', '=', 0],
            ['faqs.id', '=', $id],
        ])->update(['faqs.is_deleted' =>1, 'faq_departments.is_deleted' =>1]);
        return response()->json([
            'faq' => $faq,
            'success' => true
        ], 200);

    }

}
