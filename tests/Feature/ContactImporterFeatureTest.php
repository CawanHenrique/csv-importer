<?php

use App\Models\Contact;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;

uses(WithoutMiddleware::class);

it('uploads and imports a csv file via endpoint', function () {
    Storage::fake('local');

    $csvContent = "name;email;phone;birthdate\n".
                  "John Doe;john@example.com;123456789;1990-01-01\n".
                  "Jane Doe;jane@example.com;987654321;1992-05-10";

    $file = UploadedFile::fake()->createWithContent('contacts.csv', $csvContent);

    $response = $this->post(route('contacts.import'), [
        'file' => $file,
    ]);

    $response->assertStatus(302);
    expect(Contact::count())->toBe(2);
});
