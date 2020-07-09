<?php
// Copyright (c) Microsoft Corporation.
// Licensed under the MIT License.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Traits\FormPreviewTrait;
use App\Applicant;
use App\ApplicantSignInfo;
use App\ApplicationForm;
use App\ApplicationStatus;
use App\ApplicationState;
use App\EmailActivatorKey;
use App\CountryState;
use App\District;
use App\ApplicantFamily;
use App\Spouse;
use App\Academic;
use App\TrainingProgram;

use App\ContactInfo;
use App\AssistInfo;
use App\PrequalifyCheck;
use App\Employee;
use App\Financial;
use App\RecruitPax;
use App\RecruitPlan;
use App\SupportDoc;

use App\Mail\ActivateAccount;
use Log;
use PDF;

class ApplicationController extends Controller
{
    use FormPreviewTrait;
    public function viewAppSect($sctNo)
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }

        $loadData = $this->loadSectionData($sctNo);
        //Log:info($loadData);
        return view('appFormSect'.$sctNo)->with([
            'activeLink' => config('enums.applicantSidebarLinks')['FORM'],
            'activeSct' => $sctNo,
            'appForm' => $loadData['appForm'],
            'loadData' => $loadData,
          ]);
    }

    protected function loadSectionData($sctNo)
    {
        
        $viewData = [];
        $applicant = Applicant::find(session('userId'));
        $viewData['sect_progress'] = session('userSectProgress');
        
        switch($sctNo)
        {
            case 1:
                if(isset($applicant->app_id))
                {
                    $appForm = ApplicationForm::find($applicant->app_id);
                    $viewData['appForm'] = $appForm->toArray();
                    $keluarga = ApplicantFamily::where('app_id', '=', $applicant->app_id)->get();
                    $viewData['keluarga'] = $keluarga->toArray(); 
                    $district = District::where('negeri_id', '=', $appForm->negeri_id)->get()->toArray();
                    $viewData['act'] = 'edit';
                }
                else
                {
                    
                    $appForm = new ApplicationForm;
                    $viewData['appForm'] = $appForm->toArray(); 
                    $district = District::where('negeri_id', '=', 1)->get()->toArray();

                    //Log::info($viewData);
                }
                
                $negeri = CountryState::all()->toArray();
                
                $viewData['negeri'] = $negeri; 
                $viewData['district'] = $district; 
            break;
            case 2:
                
                if(Spouse::where('app_id', '=', $applicant->app_id)->exists())
                {
                    $spouse = Spouse::where('app_id', '=', $applicant->app_id)->first();
                    $viewData['appForm'] = $spouse;
                    $viewData['jikaAda'] = "1";
                    $viewData['act'] = 'edit';
                }
                else
                {
                    $appForm = [];
                    $appForm['app_id'] = $applicant->app_id;
                    $viewData['appForm'] = $appForm; 
                }
            break;
            case 3:
                if(Academic::where('app_id','=',$applicant->app_id)->exists())
                {
                    $academic = Academic::where([['app_id', '=', $applicant->app_id]])->get();
                    $viewData['act'] = 'edit';
                    $viewData['academic'] = $academic->toArray(); 
                    $viewData['appForm']['id'] = $applicant->app_id; 
                }
                else
                {
                    $appForm = new ApplicationForm;
                    $appForm['id'] = $applicant->app_id;
                    $viewData['appForm'] = $appForm; 
                }
            break;
            case 4:

                $appForm = ApplicationForm::find($applicant->app_id);
                $viewData['appForm'] = $appForm;
                $viewData['kursus'] = $this->getTrainingList();
                Log::info($viewData['kursus']);
            break;
            case 5:

                if(1==0)
                {
                    $viewData['act'] = 'edit';
                    $recruit = [];
                    $recruitPax = RecruitPax::where([['app_id', '=', $applicant->app_id]])->get();
                    $recruitPlan = RecruitPlan::where([['app_id', '=', $applicant->app_id]])->get();
                    $tbl1 = [];
                    $tbl2 = [];
                    foreach($recruitPax as $row)
                    {
                        $tbl1['tbl1'][]=[
                            'inputPosition' => $row->position, 
                            'degree' => $row->qualification, 
                            'inputPax' => $row->pax_count,
                            'inputSalary' => $row->min_salary
                        ];
                    }

                    foreach($recruitPlan as $row)
                    {
                        $tbl2['tbl2'][]=[
                            'inputPosition2' => $row->position, 
                            'inputMonth1' => $row->month1, 
                            'inputMonth2' => $row->month2,
                            'inputMonth3' => $row->month3
                        ];
                    }

                    $recruit = array_merge($tbl1, $tbl2);
                    $recruit['id'] = $applicant->app_id;
                    $recruit['useFormula'] = $useFormula;
                    $viewData['appForm'] = $recruit;

                }
                else
                {

                    $viewData['appForm']['id'] = $applicant->app_id;

                }
            break;

        }

        return $viewData;
    }

    public function getDistrictList($negeriId)
    {
        //$negeriId = $request->negeriId;
        $result = District::where('negeri_id','=',$negeriId)->get()->toJson();

        echo $result;
    }

    public function getTrainingList()
    {
        return TrainingProgram::all()->toArray();
    }

    public function viewAppPreview()
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }
        $userId = session('userId');
        $test= $this->getFullPreviewByAppicant($userId);
        //Log::info($test);
        return view('formPreview2')->with([
            'activeLink' => config('enums.applicantSidebarLinks')['APP_STATUS'],
            'previewData' => $test
            ]);
    }

    public function viewAppPreviewPdf()
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }
        $userId = session('userId');
        $test= $this->getFullPreviewByAppicant($userId);
        //Log::info($test);
        $pdf = PDF::loadView('formPreview2', [
            'activeLink' => config('enums.applicantSidebarLinks')['APP_STATUS'],
            'previewData' => $test
            ]);

        return $pdf->download('testing.pdf'); 
    }

    public function testpdf()
    {
        ///*
        $pdf = PDF::loadView('loaTemplate', [
            'activeLink' => config('enums.applicantSidebarLinks')['APP_STATUS'],
            ]);

        return $pdf->download('testing.pdf'); 
        //*/
        
        //return view('loaTemplate');
    }

    protected function genRefNo($appId)
    {
        //str_pad($input, 10, "-=", STR_PAD_LEFT)
        if(strlen($appId)<5)
            $appId = str_pad($appId, 6-(strlen($appId) ), "0", STR_PAD_LEFT);
        return env('REF_PREFIX').$appId;
    }

    public function viewAppStatus()
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }

        $applicant = Applicant::where([['id','=', session('userId')]])->first();
        $appStatus = ApplicationStatus::where([['app_id','=',$applicant->app_id]])
            ->orderBy('id', 'desc')->first(); 
        $appForm = ApplicationForm::where([['id','=',$applicant->app_id]])->first();
        Log::info($appStatus);
        $statusData = [];
        $statusData['refNo'] = $appForm->ref_no;
        $statusData['status'] = array_flip(config('enums.applicationStatus'))[$appStatus->status_id];
        $statusData['remark'] = $appStatus->comment;
        return view('appFormApplicationStatus')->with([
            'activeLink' => config('enums.applicantSidebarLinks')['APP_STATUS'],
            'appStatus' => $statusData
            ]);
    }

    public function sct1Save(Request $request)
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }

        //log::info($request);
        $act = $request->act;
        
        if($act == 'edit')
        {
            $appForm = ApplicationForm::find($request->appId);
        }
        else
        {
            //check appform exist or not for backbutton issue
            $applicant = Applicant::find(session('userId'));
            if(isset($applicant->app_id))
            {
                $appForm = ApplicationForm::find($applicant->app_id);
            }
            else
            {
                $appForm = new ApplicationForm;
            }
        }
        
        //$appForm->id = $request->appId;
        $appForm->nama = $request->nama;
        $appForm->mycard = $request->mykad;
        $appForm->tarikh_lahir = date( "Y-m-d",strtotime($request->tarikhlahir));
        $appForm->tempat_lahir = $request->tempat;
        $appForm->jantina = $request->jantina;
        $appForm->umur = $request->umur;
        $appForm->status_kahwin = $request->statuskahwin;
        $appForm->bangsa = $request->bangsa;
        $appForm->no_fon = $request->telefon;
        $appForm->alamat = $request->alamat;
        $appForm->fb = $request->fbinsta;
        $appForm->negeri_id = $request->selNegeri;
        $appForm->district_id = $request->selDist;
        $appForm->pendapatan = $request->pendapatan;
        $appForm->nama_majikan = $request->namamajikan;
        $appForm->alamat_majikan = $request->alamatmajikan;
        $appForm->lesen_memandu = $request->sellesen;

        
        log::info($request);

        //$appForm->keluarga = $request->namaKeluarga;
        $keluarga = [];
        $errMsg=[];
        for($i = 0; $i < 6; $i++)
        {
            if(!empty($request->namaKeluarga[$i]))
            {
                $rdoName = "rdoKerja".($i+1);
                $adaKerja = $request->rdoName;
                //check other fields in the same rows
                if(empty($request->hubungan[$i]))
                    $errMsg['keluarga'][$i]['hubungan'] = "Sila isikan maklumak ini!";
                if(empty($request->umurKerluarga[$i]))
                    $errMsg['keluarga'][$i]['umur'] = "Sila isikan maklumak ini!";
                if(!isset($rdoName))
                    $errMsg['keluarga'][$i]['ada_kerja'] = "Sila pilih salah satu!";
                
                    $keluarga[] = [
                    'nama' => $request->namaKeluarga[$i], 
                    'hubungan' => $request->hubungan[$i], 
                    'umur' => $request->umurKerluarga[$i],
                    'ada_kerja' => $request->$rdoName
                    ];
            }
        }

        
        if(!isset($appForm->nama))
        {
            $errMsg['nama'] = "Sila isikan maklumak yang diperlukan!";
        }
        if(!isset($appForm->mycard))
        {
            $errMsg['mykad'] = "Sila isikan maklumak yang diperlukan!";
        }
        if(!isset($appForm->tempat_lahir))
        {
            $errMsg['tempat'] = "Sila isikan maklumak yang diperlukan!";
        }
        if(!isset($appForm->tarikh_lahir))
        {
            $errMsg['tarikhlahir'] = "Sila isikan maklumak yang diperlukan!";
        }
        if(!isset($appForm->umur))
        {
            $errMsg['umur'] = "Sila isikan maklumak yang diperlukan!";
        }
        if(!isset($appForm->alamat))
        {
            $errMsg['alamat'] = "Sila isikan maklumak yang diperlukan!";
        }
        if(!isset($appForm->pendapatan))
        {
            $errMsg['pendapatan'] = "Sila isikan maklumak yang diperlukan!";
        }
        if(!isset($appForm->no_fon))
        {
            $errMsg['telefon'] = "Sila isikan maklumak yang diperlukan!";
        }

        if($act != 'edit' && !array_key_exists("mykad", $errMsg) && ApplicationForm::where([['mycard', '=', $appForm->mycard]])->exists())
        {
            $errMsg["mykad"] ="Nombor Mykad/KP/Tentera/Polis telah ada di dalam sistem!";
        }

        //log::info($appForm->toArray());
        //end validation
        if(!empty($errMsg)){
            $loadData =[];
            $loadData['sect_progress'] = session('userSectProgress');
            $negeri = CountryState::all()->toArray();
            $district = District::where('negeri_id', '=', $appForm->negeri_id)->get()->toArray();
            $loadData['negeri'] = $negeri; 
            $loadData['district'] = $district; 
            $loadData['keluarga'] = $keluarga; 
            Log::info($loadData);
            return view('appFormSect1')->with([
                'activeLink' => config('enums.applicantSidebarLinks')['FORM'],
                'activeSct' => 1,
                'appForm' => $appForm->toArray(),
                'loadData' => $loadData,
                'errMsg' => $errMsg,
              ]);;
            
        }
        else
        {
            //$appForm->status = config('enums.applicationStatus')['SAVED'];
            $appForm->save();

            $applicant = Applicant::find(session('userId'));
            $applicant->app_id = $appForm->id;

            ApplicantFamily::where('app_id', '=',$appForm->id)->delete();
            foreach($keluarga as $row)
            {
                $appFamily = new ApplicantFamily;
                $appFamily->app_id = $appForm->id;
                $appFamily->nama = $row['nama'];
                $appFamily->umur = $row['umur'];
                $appFamily->hubungan = $row['hubungan'];
                $appFamily->ada_kerja = $row['ada_kerja'];
                $appFamily->save();

            }

            if($applicant->sect_progress <= 1)
            {
                $applicant->sect_progress = 2;
                session(['userSectProgress' => 2]);
            }
            $applicant->app_id = $appForm->id;
            $applicant->save();

            return redirect('/formSct/2');
            
        }

    }

    public function sct2Save(Request $request)
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }

        //log::info($request);
        $jikaAda = $request->jikaAda;
        if (!isset($jikaAda)) 
        {
            $applicant = Applicant::find(session('userId'));
            Spouse::where('app_id','=',$request->appId)->delete();
            if($applicant->sect_progress <= 2)
            {
                $applicant->sect_progress = 3;
                session(['userSectProgress' => 3]);
            }
            
            $applicant->save();

            return redirect('/formSct/3');
        }
        $act = $request->act;
        
        if($act == 'edit')
        {
            $spouseForm = Spouse::where('app_id' ,'=',$request->appId)->first();
        }
        else
        {
            //check appform exist or not for backbutton issue
            $applicant = Applicant::find(session('userId'));
            if(Spouse::where('app_id','=',$request->appId)->exists())
            {
                $spouseForm = Spouse::where('app_id','=',$request->appId)->first();
            }
            else
            {
                $spouseForm = new Spouse;
            }
        }
        
        $spouseForm->app_id = $request->appId;
        $spouseForm->nama = $request->nama;
        $spouseForm->mycard = $request->mykad;
        $spouseForm->tempat_lahir = $request->tempat;
        $spouseForm->tarikh_lahir = date( "Y-m-d",strtotime($request->tarikhlahir));
        $spouseForm->jantina = $request->jantina;
        $spouseForm->umur = $request->umur;
        $spouseForm->bangsa = $request->bangsa;
        $spouseForm->no_fon = $request->telefon;
        $spouseForm->fb = $request->fbinsta;
        $spouseForm->pendapatan = $request->pendapatan;
        $spouseForm->nama_majikan = $request->namamajikan;
        $spouseForm->alamat_majikan = $request->alamatmajikan;
        $spouseForm->lesen_memandu = $request->sellesen;

        //validate input
        $errMsg=[];
        if(!isset($spouseForm->nama))
        {
            $errMsg['nama'] = "Please fill in mandatory field!";
        }
        if(!isset($spouseForm->mycard))
        {
            $errMsg['mykad'] = "Please fill in mandatory field!";
        }
        if(!isset($spouseForm->tempat_lahir))
        {
            $errMsg['tempat'] = "Please fill in mandatory field!";
        }
        if(!isset($spouseForm->tarikh_lahir))
        {
            $errMsg['tarikhlahir'] = "Please fill in mandatory field!";
        }
        if(!isset($spouseForm->umur))
        {
            $errMsg['umur'] = "Please fill in mandatory field!";
        }
        if(!isset($spouseForm->pendapatan))
        {
            $errMsg['pendapatan'] = "Please fill in mandatory field!";
        }
        if(!isset($spouseForm->no_fon))
        {
            $errMsg['telefon'] = "Please fill in mandatory field!";
        }

        //log::info($appForm->toArray());
        //end validation
        if(!empty($errMsg)){
            $loadData =[];
            $loadData['jikaAda'] = $jikaAda;
            $loadData['sect_progress'] = session('userSectProgress');
             Log::info($loadData);
            return view('appFormSect2')->with([
                'activeLink' => config('enums.applicantSidebarLinks')['FORM'],
                'activeSct' => 2,
                'appForm' => $spouseForm->toArray(),
                'loadData' => $loadData,
                'errMsg' => $errMsg,
              ]);;
            
        }
        else
        {
            //$appForm->status = config('enums.applicationStatus')['SAVED'];
            $spouseForm->save();

            $applicant = Applicant::find(session('userId'));

            if($applicant->sect_progress <= 2)
            {
                $applicant->sect_progress = 3;
                session(['userSectProgress' => 3]);
            }
            
            $applicant->save();

            return redirect('/formSct/3');
            
        }

    }

    public function sct3Save(Request $request)
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }

        Log::info($request);
        $errMsg=[];

        $act = $request->act;
        $appForm = ApplicationForm::find($request->appId);
        

        $academic = [];

        for($i = 0; $i < 5; $i++)
        {
            if(!empty($request->bidang[$i]))
            {
                $dateMula = "tempohMula".($i+1);
                $dateTamat = "tempohTamat".($i+1);
                $academic[] = [
                    'pengajian' => $request->bidang[$i], 
                    'tempoh_mula' => date( "Y-m-d",strtotime($request->$dateMula)), 
                    'tempoh_tamat' => date( "Y-m-d",strtotime($request->$dateTamat)),
                    'app_id' => $request->appId
                ];
                //log::info($request->$rdoName);
            }
        }


        //validation
        /*
        foreach($req as $key => $value)
        {
            if(!isset($value))
            {
                $errMsg[$key] = "Please fill in mandatory fields!";
            }
        }*/

        if(!empty($errMsg)){
            $req['id']=$request->appId;
            $loadData =[];
            $loadData['sect_progress'] = session('userSectProgress');
            return view('appFormSect3')->with([
                'activeLink' => config('enums.applicantSidebarLinks')['FORM'],
                'activeSct' => 3,
                'appForm' => $req,
                'loadData' => $loadData,
                'errMsg' => $errMsg,
            ]);;
            
        }
        else
        {
            Academic::where('app_id', '=',$request->appId)->delete();
            foreach($academic as $row)
            {
                $ac = new Academic;
                $ac->pengajian = $row['pengajian'];
                $ac->tempoh_mula = $row['tempoh_mula'];
                $ac->tempoh_tamat = $row['tempoh_tamat'];
                $ac->app_id = $request->appId;
                $ac->save();
                
            }

            $applicant = Applicant::find(session('userId'));
                
            if($applicant->sect_progress <= 3)
            {
                $applicant->sect_progress = 4;
                session(['userSectProgress' => 4]);
            }
            $applicant->save();
            return redirect('/formSct/4');
        }

    }

    public function sct4Save(Request $request)
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }

        $errMsg=[];
        $req=[];
        $act = $request->act;
        $appForm = ApplicationForm::find($request->appId);
        $appForm->kursus_pertama_id = $request->sel1;
        $appForm->kursus_kedua_id = $request->sel2;
        $appForm->kursus_pertama_lain = $request->lain1;
        $appForm->kursus_kedua_lain = $request->lain2;
 


        //validation
        if($appForm->kursus_pertama_id == $appForm->kursus_kedua_id)
        {
            $errMsg['err'] = "Tidak boleh pilih kedua-dua pilihan yang sama!";
        }

        if(!empty($errMsg)){
            $loadData =[];
            $loadData['sect_progress'] = session('userSectProgress');
            return view('appFormSect4')->with([
                'activeLink' => config('enums.applicantSidebarLinks')['FORM'],
                'activeSct' => 4,
                'loadData' => $loadData,
                'appForm' => $appForm,
                'errMsg' => $errMsg,
            ]);;
            
        }
        else
        {
            
            $appForm->save();
            $applicant = Applicant::find(session('userId'));
            if($applicant->sect_progress <= 4)
            {
                $applicant->sect_progress = 5;
                session(['userSectProgress' => 5]);
            }
            $applicant->save();
            return redirect('/formSct/5');
        }
    }

    public function sct5Save(Request $request)
    {
        if (!session('userName')) 
        {
            return redirect('/timeout');
        }

        //Log::info($request);
        $errMsg=[];


        $chkTnc = $request->chkTnc;
        if(empty($chkTnc))
        {
            $errMsg['chkTnc'] = "Please check to agree!";
        }

        if(!empty($errMsg))
        {
            $loadData =[];

            $req['id'] = $request->appId;
            //Log::info($req);
            $loadData['sect_progress'] = session('userSectProgress');
            return view('appFormSect5')->with([
                'activeLink' => config('enums.applicantSidebarLinks')['FORM'],
                'activeSct' => 5,
                'appForm' => $req,
                'loadData' => $loadData,
                'errMsg' => $errMsg,
            ]);;
        }
        else
        {   
            $applicant = Applicant::find(session('userId'));
            $appStatus = new ApplicationStatus;
            $appStatus->app_id = $request->appId;
            $appStatus->status_id = config('enums.applicationStatus')['DITERIMA'];
            $appStatus->comment = "Dalam Proses";
            $appStatus->save();
            if($applicant->sect_progress <= 5)
            {
                $applicant->sect_progress = 6;
                session(['userSectProgress' => 6]);
            }

            $appForm = ApplicationForm::where([['id', '=', $request->appId]])->first();
            $appForm->ref_no = $this->genRefNo($request->appId);
            $appForm->save();

            session(['hasForm' => '0']);
            return redirect('/appStatus');
        }
    }



}