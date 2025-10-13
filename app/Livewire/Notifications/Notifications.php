<?php

namespace App\Livewire\Notifications;

use App\Models\Delivery;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use App\Models\Notification;
use App\Models\User;
use App\Services\notificationsService;

class Notifications extends Component
{
    use WithFileUploads;

    public $token;
    public $message;
    public $title;
    public $type; // general or user
    public $photo;
    public $user_id;
    public $area;
    public $gender;
    public $minAge;
    public $maxAge;
    public $areaSearch = '';

    public function getFilteredAreasProperty()
    {
        if (empty($this->areaSearch)) {
            return Delivery::limit(10)->get(); // عرض أول 10 مناطق فقط عندما لا يكون هناك بحث
        }

        return Delivery::where('name', 'like', '%' . $this->areaSearch . '%')
            ->limit(20)
            ->get();
    }

    public function send()
    {

        $this->validate([
            'message' => 'required|string',
            'title'   => 'required|string',
            'type'    => 'required|string|in:general,user,people',
            'photo'   => 'nullable|image|max:1024', // 1MB Max
            'user_id' => 'nullable|exists:users,id',
            'area'    => 'nullable|string|required_if:type,people',
            'minAge'  => 'nullable|integer|required_if:type,people',
            'maxAge'  => 'nullable|integer|required_if:type,people',
        ]);

        $gender = $this->gender;
        $minAge = $this->minAge;
        $maxAge = $this->maxAge;
        $place_name = $this->area;

        $data = [
            'title'   => $this->title ?? 'إشعار جديد',
            'body'    => $this->message,
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('notifications', 'public');
        }

        $notification = Notification::create([
            'title'   => $data['title'],
            'body'    => $data['body'],
            'photo'   => $data['photo'] ?? null,
        ]);



        $payload = [
            'message' => [
                'notification' => [
                    'title' => $data['title'],
                    'body'  => $data['body'],
                ],
            ]
        ];

        if ($this->type === 'general'){

            $payload['message']['topic'] = 'elitetopic';
            Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/v1/projects/resturant-38fd1/messages:send', $payload);
                //gitbub copilot


        } elseif ($this->type === 'user' && $this->user_id) {
            $user = User::find($this->user_id);

            if ($user) {
                $notification->users()->attach($user->id, ['is_read' => false]);
                if ($user->customerInfo && $user->customerInfo->notification_token) {
                    $payload['message']['token'] = $user->customerInfo->notification_token;

                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-Type'  => 'application/json',
                    ])->post('https://fcm.googleapis.com/v1/projects/resturant-38fd1/messages:send', $payload);
                }
            }
        } elseif ($this->type === 'people') {



            $users = User::whereHas('customerInfo', function ($query) use ($gender, $maxAge,  $minAge, $place_name) {
                $query->where('gender', $gender)
                    ->whereBetween('age', [$minAge, $maxAge])
                    ->whereHas('delivery', function ($deliveryQuery) use ($place_name) {
                        $deliveryQuery->where('id', $place_name);
                    });
            })->get();


            foreach ($users as $user) {
                $notification->users()->attach($user->id, ['is_read' => false]);
                if ($user->customerInfo && $user->customerInfo->notification_token) {
                    $payload['message']['token'] = $user->customerInfo->notification_token;

                    Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-Type'  => 'application/json',
                    ])->post('https://fcm.googleapis.com/v1/projects/resturant-38fd1/messages:send', $payload);
                }
            }
        }



        // $payload['message']['token'] = $user->customerInfo->notification_token;

        // Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $this->token,
        //     'Content-Type'  => 'application/json',
        // ])->post('https://fcm.googleapis.com/v1/projects/resturant-38fd1/messages:send', $payload);

        session()->flash('done', 'تم إرسال الإشعار بنجاح');
        $this->reset(['message', 'title', 'user_id', 'type', 'photo']);
    }

    public function render()
    {
        return view('livewire.notifications.notifications', [
            'users' => User::all(),
            'filteredAreas' => $this->filteredAreas,
            'deliveryAreas' => Delivery::all()
        ]);
    }
}
