<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);

        return Inertia::render('Dashboard', [
            'contacts' => $contacts
        ]);
    }
}
