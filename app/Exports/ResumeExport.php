<?php

namespace App\Exports;

use App\Exports\Sheets\ResumeSheet;
use App\Models\Resume;

use App\Exports\Sheets\ResumeQuery;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ResumeExport implements WithMultipleSheets
{
    use Exportable;
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }


    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $resume = Resume::find($this->id);
        if(!$resume){
            abort(404);
        }

        $sheets[] = new ResumeSheet("اطلاعات رزومه", "admin.resumes.exports", $resume);
        $sheets[] = new ResumeSheet("سوابق تحصیلی", "admin.resumes.educationRecordsList", $resume);
        $sheets[] = new ResumeSheet("دانش زبانی", "admin.resumes.languagesList", $resume);
        $sheets[] = new ResumeSheet("سوابق کاری", "admin.resumes.worksList", $resume);
        $sheets[] = new ResumeSheet("دانش نرم افزاری", "admin.resumes.softwareKnowledgesList", $resume);
        $sheets[] = new ResumeSheet("دوره ها و مدارک", "admin.resumes.coursesList", $resume);
        $sheets[] = new ResumeSheet("سوابق پژوهشی ، افتخارات", "admin.resumes.researchsList", $resume);
        $sheets[] = new ResumeSheet("تفریحات", "admin.resumes.hobbiesList", $resume);
        

        return $sheets;
    }
}