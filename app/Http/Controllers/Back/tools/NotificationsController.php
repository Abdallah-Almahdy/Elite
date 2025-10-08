<?php

namespace App\Http\Controllers\Back\tools;

use App\Http\Controllers\Controller;
use App\Services\notificationsService;

class NotificationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $token;

    public function index()
    {
        // Create an instance of the notifications service
        $notificationsService = new notificationsService();

        // Retrieve the server key token asynchronously
        $this->token = $notificationsService->getServerKeyToken();

        return view('pages.notifications.index', ['token' => $this->token]);
    }
}
