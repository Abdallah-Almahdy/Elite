<?php

namespace App\Http\Controllers\Back\Statics;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SubSection;
use Illuminate\Http\Request;

class SectionsStaticsController extends Controller
{
    public function mainSections()
    {
        $sections = Section::paginate(15);
        return view(
            'admin.statics.sections.mainSections.index',
            [
                'sections' => $sections,
            ]
        );
    }
    public function subSections()
    {
        $subSections = SubSection::paginate(15);

        return view('admin.statics.sections.subSections.index', [
            'subSections' => $subSections,
        ]);
    }
    public function subSection($id)
    {
        $subSections = SubSection::where('main_section_id', $id)->paginate(15);

        return view('admin.statics.sections.subSections.index', [
            'subSections' => $subSections,
        ]);
    }
}
