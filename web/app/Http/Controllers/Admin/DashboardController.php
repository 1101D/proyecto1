<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'eventsCount' => Event::count(),
            'activeEventsCount' => Event::where('status', 'active')->count(),
            'ticketsSold' => \App\Models\Ticket::where('status', 'active')->count(),
            'totalRevenue' => Order::where('status', 'paid')->sum('total'),
            'latestUsers' => User::latest()->limit(5)->get(),
            'latestEvents' => Event::with('user')->latest()->limit(5)->get(),
        ]);
    }
}
