<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\ContactImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ContactImportController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate();

        return Inertia::render('Contacts/index', [
            'contacts' => $contacts,
            'summary' => session('summary'),
        ]);
    }

    public function store(Request $request, ContactImporter $importer)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls'
        ]);

        $file = $request->file('file');
        $path = $file->store('imports');
        $extension = strtolower($file->getClientOriginalExtension());
        $fullPath = Storage::path($path);

        $summary = $importer->import($fullPath, $extension);

        return redirect()->route('contacts.index')->with('summary', $summary);
    }
}
