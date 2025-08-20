<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::latest()->paginate(10);
        if ($request->search) {
            $contacts = $this->contactsFilter($request->search);
        }

        return Inertia::render('Dashboard', [
            'contacts' => $contacts
        ]);
    }

    private function contactsFilter($search)
    {
        $columns = ['name', 'email', 'phone', 'birthdate'];
        return Contact::where(function($query) use ($search, $columns) {
                    foreach ($columns as $column){
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
        })->paginate(10);
    }
}
