<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use ContactImporter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactImportController extends Controller
{
  public function index(){
    $contacts = Contact::lastest()->paginate(10);

    return Inertia::render('Contacts/index.vue' , [
        'contacts' => $contacts,
        'summary' => session('summary'),
    ]);
  }

  public function store(Request $request, ContactImporter $importer){
      $request->validate([
          'file' => 'required|mimes:csv,txt'
      ]);

      $path = $request->file('file')->store('imports');
      $summary = $importer->import(storage_path('app/' . $path));
      return redirect()->route('contacts.index')->with('summary', $summary);
  }
}
