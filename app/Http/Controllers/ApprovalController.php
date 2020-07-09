<?php
// Copyright (c) Microsoft Corporation.
// Licensed under the MIT License.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\FormPreviewTrait;
use App\Applicant;
use App\ApplicantSignInfo;
use App\ApplicationForm;
use App\ApplicationStatus;
use App\ApplicationState;
use App\ContactInfo;
use App\AssistInfo;
use App\PrequalifyCheck;
use App\Employee;
use App\Financial;
use App\RecruitPax;
use App\RecruitPlan;
use App\SupportDoc;
use App\Officer;
use App\Role;
use App\FlowState;
use App\AssessmentScorecard;
use App\Mail\ActivateAccount;
use Log;
use DateTime;
use DateInterval;
use DatePeriod;

class ApprovalController extends Controller
{
    use FormPreviewTrait;

    public function devBypass($id)
    {
        $officer = Officer::find($id);
        $role = Role::find($officer->role_id);
        session([
            'userRoleId' =>  $officer->role_id,
            'userRole' =>  $role->name,
            'userName' =>  'Testing Account',
        ]);

        return redirect('/officer/appList');
    }

    public function viewAppList()
    {
        if (!session('userName')) 
        {
            return redirect('/officer/timeout');
        }

        $records = DB::select(
            'SELECT  afs.ref_no, afs.ssm_no, afs.co_name, afs.sector,fs.officer_in_charge, r.name as officer_name, aps.app_id, aps.state_id, '.
            'fs.name as status_name, aps.by_name, aps.comment, last_update, submission_date '.
            'FROM `application_states` aps '.
            'join (select app_id, max(created_at) as last_update, min(created_at) as submission_date from `application_states` group by app_id) '.
            'ms on aps.app_id = ms.app_id and aps.created_at = ms.last_update '.
            'join flow_states fs on aps.state_id = fs.id '.
            'join application_forms afs on aps.app_id = afs.id '.
            'left join roles r on fs.officer_in_charge = r.id '
        );

        $records = json_decode(json_encode($records), true);
        foreach($records as &$record)
        {
            $record['last_update'] = date_format(date_create($record['last_update']), 'Y-m-d');
            $record['submission_date'] = date_format(date_create($record['submission_date']), 'Y-m-d');
            $lastDate = isset($record['officer_in_charge'])? 'NOW' : $record['last_update'];
            $record['day_left'] = $this->cal7days($record['submission_date'], $lastDate );
        }
        //Log::Info(session()->all());
        //Log::Info($records);
        return view('applicationList')->with([
            'tblData' => $records
        ]);
    }

    protected function cal7days($startDate, $endDate)
    {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $begin = $begin->modify( '+1 day' );

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);
        $day=0;
        $holiday=[];
        foreach($daterange as $date){
            if(in_array($date->format('N'), [6,7]) || in_array($date->format('Y-m-d'), $holiday))
                continue;
            else
                ++$day;
        }
        return 7-$day;
    }

    public function viewAppDetail($appId)
    {
        if (!session('userName')) 
        {
            return redirect('/officer/timeout');
        }

        $prevData= $this->getFullPreviewByAppId($appId);
        $appState = ApplicationState::where('app_id', '=', $appId)->latest()->first();
        //Log::info($appState);
        $appForm = ApplicationForm::find($appId);
        $flowState = FlowState::find($appState->state_id);
        if($flowState->forward_action != 0)
            $review['forward_action'] = $flowState->forward_action;
        if($flowState->backward_action != 0)
            $review['backward_action'] = $flowState->backward_action;
        $review['refNo'] = $appForm->ref_no;
        $review['Status'] = $flowState->name;
        $review['comment'] = $appState->comment;
        if(isset($appState->system_comment))
            $review['system_comment'] = $appState->system_comment;
        $review['comment_by'] = $appState->by_name;
        $review['approved_pax'] = $appState->approved_pax;
        $review['approved_fund'] = $appState->approved_fund;

        if(AssessmentScorecard::where('app_id', '=', $appId)->exists())
        {
            $sc = AssessmentScorecard::where('app_id', '=', $appId)->latest()->first();
            $review['inputyear'] = $sc->year_in_op; 
            $review['inputstaff'] = $sc->no_staff; 
            $review['inputfiscal'] = $sc->fiscal_health; 
            $review['inputtechnical'] = $sc->tech_cap;
            $review['inputtraining'] = $sc->training_prog;
            $review['inputncer'] = $sc->val_pro;
            
        }
        log::info($review);
        $domRights = $this->getRoleRights(session('userRoleId'));

        return view('appFormAppDetails')->with([
            'prevData' => $prevData,
            'review' => $review,
            'appId' => $appId,
            'domRights' => $domRights,
        ]);
    }

    public function postAppDetail(Request $request,$appId)
    {
        if (!session('userName')) 
        {
            return redirect('/officer/timeout');
        }
        //Log::info($request);

        $roleId = session('userRoleId');
        $roleRights =$this->getRoleRights($roleId);
        if($roleRights['scorecardWrite']=='1')
        {
            $sc = new AssessmentScorecard;
            $sc->app_id = $appId;
            $sc->year_in_op = $request->inputyear;
            $sc->no_staff = $request->inputstaff;
            $sc->fiscal_health = $request->inputfiscal;
            $sc->tech_cap = $request->inputtechnical;
            $sc->training_prog = $request->inputtraining;
            $sc->val_pro = $request->inputncer;
            $sc->save();
        }

        if(isset($request->btnForward))
            $stateId = $request->btnForward;
        elseif(isset($request->btnReject))
            $stateId = $request->btnReject;
        $appState = new ApplicationState;
        $appState->app_id = $appId;
        $appState->state_id = $stateId;
        $appState->approved_pax = $request->inputPax;
        $appState->approved_fund = $request->inputFund;
        $appState->by_name = session('userName');
        $appState->comment = $request->comment;
        $appState->save();

        return redirect('/officer/appList');

    }

    protected function getRoleRights($roleId)
    {
        $dom = [];

        if(in_array($roleId, [config('enums.role')['SECL1'], config('enums.role')['SECL2']]))
        {
            $dom['scorecardWrite'] = "1";
        }
        else
        {
            $dom['scorecardWrite'] = "0";
        }

        if(in_array($roleId, 
        [config('enums.role')['SECL1'], 
        config('enums.role')['SECL2'],
        config('enums.role')['COMM']]))
        {
            $dom['fundingWrite'] = "1";
        }
        else
        {
            $dom['fundingWrite'] = "0";
        }

        return $dom;
    }

    public function viewAppAudit($appId)
    {
        $result = ApplicationState::where('app_id','=',$appId)
            ->leftJoin('flow_states','application_states.state_id' ,'=', 'flow_states.id')
            ->orderBy('application_states.created_at', 'desc')
            ->select('application_states.*','flow_States.name')
            ->get();

        Log::info('lenght of '.$appId.' is '. strlen($appId));
        $refNo = env('REF_PREFIX').(strlen($appId)<3? str_pad($appId, 4-(strlen($appId) ), "0", STR_PAD_LEFT): $appId);
        return view('appDetailsAudit')->with([
            'refNo' => $refNo,
            'result' => $result
        ]);
    }
}