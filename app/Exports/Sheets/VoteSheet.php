<?php
namespace App\Exports\Sheets;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithTitle;

class VoteSheet implements FromView, WithTitle
{
    private $title;
    private $view;
    private $data;
    public function __construct(string $title, string $view, $data)
    {
        $this->title = $title;
        $this->view = $view;
        $this->data = $data;
    }




    public function view(): View
    {
        return view($this->view, [
            'votes' => $this->data['votes'],
            'questions' => $this->data['questions'],
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}
