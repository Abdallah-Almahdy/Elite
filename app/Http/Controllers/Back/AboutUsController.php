<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\AboutUS;
use App\Models\AboutUsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUS::with('images')->first();
        return view('pages.AboutUs.index', compact('about'));
    }

    public function create()
    {
        return view('pages.AboutUs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'        => 'required|string|max:255',
            'short_description'   => 'nullable|string|max:500',
            'full_description'    => 'nullable|string',
            'facebook'            => 'nullable|url',
            'whatsapp'            => 'nullable|string|max:20',
            'instagram'           => 'nullable|url',
            'location'            => 'nullable|url',
            'phone'               => ['nullable','regex:/^(010|011|012|015)[0-9]{8}$/'],
            'email'               => 'nullable|email',
            'address'             => 'nullable|string|max:255',
            'work_from'           => 'nullable|date_format:H:i',
            'work_to'             => 'nullable|date_format:H:i|after:work_from',
            'experience_years'    => 'nullable|integer|min:0',
            'happy_clients'       => 'nullable|integer|min:0',
            'successful_projects' => 'nullable|integer|min:0',
            'logo'                => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'photos.*'            => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
        ]);

        $about = AboutUS::create(Arr::except($validated, ['logo', 'photos']));

        $about->work_from = $request->work_from;
        $about->work_to = $request->work_to;
        $about->save();


        if ($request->hasFile('logo')) {
            if ($about->logo) {
                $oldPath = public_path('uploads/about/' . $about->logo);
                if (File::exists($oldPath)) File::delete($oldPath);
            }

            $image_name = 'about_logo_' . uniqid() . '.' . $request->logo->getClientOriginalExtension();
            $request->logo->storeAs('uploads/about', $image_name, 'my_public');

            $about->logo = $image_name;
            $about->save();
        }


        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $image_name = 'about_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('uploads/about/photos', $image_name, 'my_public');

                AboutUsImage::create([
                    'about_us_id' => $about->id,
                    'photo'       => $image_name,
                ]);
            }
        }

        return redirect()->route('about.index')->with('success', 'تم إضافة بيانات الشركة بنجاح');
    }

    public function edit(AboutUS $about)
    {
        $about->load('images');
        return view('pages.AboutUs.update', compact('about'));
    }

    public function update(Request $request, AboutUS $about)
    {
        $validated = $request->validate([
            'company_name'        => 'required|string|max:255',
            'short_description'   => 'nullable|string|max:500',
            'full_description'    => 'nullable|string',
            'facebook'            => 'nullable|url',
            'whatsapp'            => 'nullable|string|max:20',
            'instagram'           => 'nullable|url',
            'location'            => 'nullable|url',
            'phone'               => ['nullable','regex:/^(010|011|012|015)[0-9]{8}$/'],
            'email'               => 'nullable|email',
            'address'             => 'nullable|string|max:255',
            'work_from'           => 'nullable|date_format:H:i',
            'work_to'             => 'nullable|date_format:H:i|after:work_from',
            'experience_years'    => 'nullable|integer|min:0',
            'happy_clients'       => 'nullable|integer|min:0',
            'successful_projects' => 'nullable|integer|min:0',
            'logo'                => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'photos.*'            => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
        ]);

        $about->update(Arr::except($validated, ['logo', 'photos']));
        $about->work_from = $request->work_from;
        $about->work_to = $request->work_to;
        $about->save();


        if ($request->hasFile('logo')) {
            if ($about->logo) {
                $oldPath = public_path('uploads/about/' . $about->logo);
                if (File::exists($oldPath)) File::delete($oldPath);
            }

            $image_name = 'about_logo_' . uniqid() . '.' . $request->logo->getClientOriginalExtension();
            $request->logo->storeAs('uploads/about', $image_name, 'my_public');

            $about->logo = $image_name;
            $about->save();
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $image_name = 'about_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('about/photos', $image_name, 'my_public');

                AboutUsImage::create([
                    'about_us_id' => $about->id,
                    'photo'       => $image_name,
                ]);
            }
        }

        return redirect()->route('about.index')->with('success', 'تم تحديث بيانات الشركة بنجاح');
    }

    public function deleteImage($id)
    {
        $image = AboutUsImage::findOrFail($id);

        $path = public_path('uploads/about/photos/' . $image->photo);
        if (File::exists($path)) {
            File::delete($path);
        }

        $image->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح');
    }

    public function destroy(AboutUS $about)
    {

        if ($about->logo) {
            $logoPath = public_path('uploads/about/' . $about->logo);
            if (File::exists($logoPath)) File::delete($logoPath);
        }

        foreach ($about->images as $image) {
            $imagePath = public_path('about/photos/' . $image->photo);
            if (File::exists($imagePath)) File::delete($imagePath);
            $image->delete();
        }

        $about->delete();

        return redirect()->route('about.index')->with('success', 'تم حذف بيانات الشركة بالكامل بنجاح');
    }
}
