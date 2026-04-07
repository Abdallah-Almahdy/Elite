<?php

namespace App\Http\Controllers\Back\tools;

use App\Http\Controllers\Controller;
use App\Services\notificationsService;
use Illuminate\Support\Facades\Gate;

class NotificationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $token;

    public function index()
    {
        Gate::authorize('showNotifications');
        // Create an instance of the notifications service
        $notificationsService = new notificationsService();

        // Retrieve the server key token asynchronously
        $this->token = $notificationsService->getServerKeyToken();

        return view('pages.notifications.index', ['token' => $this->token]);
    }
}
