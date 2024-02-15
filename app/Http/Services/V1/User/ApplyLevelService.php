<?php

namespace App\Http\Services\V1\User;

use App\Models\ApplyLevel;
use App\Models\UserApplyLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplyLevelService
{
    public function applyLevels()
    {
        $phase1 = DB::select('select id,title,text,link,pos,phase,filename,next_level_button,created_at,updated_at from nag_apply_levels where phase = 1 order by pos desc');
        $phase2 = DB::select('select id,title,text,link,pos,phase,filename,next_level_button,created_at,updated_at from nag_apply_levels where phase = 2 order by pos desc');
        $phase3 = DB::select('select id,title,text,link,pos,phase,filename,next_level_button,created_at,updated_at from nag_apply_levels where phase = 3 order by pos desc');
        $phase4 = DB::select('select id,title,text,link,pos,phase,filename,next_level_button,created_at,updated_at from nag_apply_levels where phase = 4 order by pos desc');
        $phase5 = DB::select('select id,title,text,link,pos,phase,filename,next_level_button,created_at,updated_at from nag_apply_levels where phase = 5 order by pos desc');

        return ['phase1'=>$phase1,'phase2'=>$phase2,'phase3'=>$phase3,'phase4'=>$phase4,'phase5'=>$phase5];
    }

    public function checkApplyLevel(Request $request)
    {
        $applyLevel = ApplyLevel::find($request->id);
        if($applyLevel) {
            $userApplyLevel = auth()->guard('api')->user()->userApplyLevels()->where('apply_level_id',$applyLevel->id)->first();
            if(!$userApplyLevel) {
                $userApplyLevel = new UserApplyLevel();
                $userApplyLevel->user_id = auth()->guard('api')->id();
                $userApplyLevel->apply_level_id = $applyLevel->id;
                $userApplyLevel->save();
            }
            $userApplyLevelStatus = auth()->guard('api')->user()->userApplyLevelStatus;
            if($applyLevel->phase == 1) {
                $userApplyLevelStatus->phase1 += $applyLevel->phase_percent;
                $userApplyLevelStatus->total += $applyLevel->progress_percent;
            }
            if($applyLevel->phase == 2) {
                $userApplyLevelStatus->phase2 += $applyLevel->phase_percent;
                $userApplyLevelStatus->total += $applyLevel->progress_percent;
            }
            if($applyLevel->phase == 3) {
                $userApplyLevelStatus->phase3 += $applyLevel->phase_percent;
                $userApplyLevelStatus->total += $applyLevel->progress_percent;
            }
            if($applyLevel->phase == 4) {
                $userApplyLevelStatus->phase4 += $applyLevel->phase_percent;
                $userApplyLevelStatus->total += $applyLevel->progress_percent;
            }
            if($applyLevel->phase == 5) {
                $userApplyLevelStatus->phase5 += $applyLevel->phase_percent;
                $userApplyLevelStatus->total += $applyLevel->progress_percent;
            }
            $userApplyLevelStatus->save();
            return 1;
        }
        return 0;
    }
}
