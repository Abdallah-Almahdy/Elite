<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use App\Models\Notification;
use App\Models\User;

class Notifications extends Component
{
    use WithFileUploads;

    public $token;
    public $message;
    public $title;
    public $type = 'general'; // general or user
    public $user_id;
    public $photo;

    public function send()
    {
        $data = [
            'title'   => $this->title ?? 'إشعار جديد',
            'body'    => $this->message,
            'type'    => $this->type,
            'user_id' => $this->type === 'user' ? $this->user_id : null,
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('notifications', 'public');
        }

        $notification = Notification::create($data);

        $payload = [
            'message' => [
                'notification' => [
                    'title' => $data['title'],
                    'body'  => $data['body'],
                ],
            ]
        ];

        if ($this->type === 'general') {
            $payload['message']['topic'] = 'general';
        } else {
            $user = User::find($this->user_id);
            if ($user && $user->fcm_token) {
                $payload['message']['token'] = $user->fcm_token;
            }
        }

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/v1/projects/smapp-8349a/messages:send', $payload);

        session()->flash('done', 'تم إرسال الإشعار بنجاح');
        $this->reset(['message', 'title', 'user_id', 'type', 'photo']);
    }

    public function render()
    {
        return view('livewire.notifications.notifications', [
            'users' => User::all()
        ])->layout('admin.livewireLayout');
    }
}
