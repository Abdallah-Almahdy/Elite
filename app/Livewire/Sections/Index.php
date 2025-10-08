<?php

namespace App\Livewire\Sections;

use App\Models\Product;
use App\Models\Section;
use Livewire\Component;
use App\Models\SubSection;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use WithPagination; // Add WithFileUploads to handle file uploads

    protected $paginationTheme = 'bootstrap';
    public $show = [];
    public $perPage = 5;
    public function delete($id)
    {
        $checkIfMain = Section::find($id);
        if ($checkIfMain) {
            $checkSubSection = SubSection::where(['main_section_id' => $id])->get();

            if (count($checkSubSection) == 0) {
                Section::destroy($id);
            }
        }
    }

    public function deleteSubSection($id)
    {
        $checkIfsub = subSection::find($id);
        if ($checkIfsub) {
            $checkSubSectionProducts = Product::where(['section_id' => $id])->get();

            if ($checkSubSectionProducts->count() == 0) {
                SubSection::destroy($id);
            }
        }
    }


    public function showSubSection(int $id)
    {
        if (isset($this->show[$id])) {
            $this->show[$id] = false;
        } else {
            $this->show[$id] = true;
        }
    }

    // Custom paginate method to paginate the merged collection
    public function paginateItems($items)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->perPage;

        // Slice the items for the current page
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage);

        // Create the paginator
        return new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    public function render()
    {

        $sections = Section::paginate($this->perPage); // Paginate sections
        $subSections = SubSection::all(); // Get all subsections (not paginated)

        return view('livewire.sections.index', [
            'sections' => $sections,
            'subSections' => $subSections,
        ]);
    }
}
