<?php

use App\Models\Contact;
use App\Services\ContactImporter;

beforeEach(function () {
    Contact::truncate();
});

it('imports valid csv rows', function () {
    $csvContent = "name;email;phone;birthdate\n" .
        "John Doe;john@example.com;123456789;1990-01-01\n" .
        "Jane Doe;jane@example.com;987654321;1992-05-10";

    $tmp = tempnam(sys_get_temp_dir(), 'csv');
    file_put_contents($tmp, $csvContent);

    $importer = new ContactImporter();
    $summary = $importer->import($tmp, 'csv');

    expect($summary['imported'])->toBe(2);
    expect(Contact::count())->toBe(2);
});

it('ignores duplicate emails inside file', function () {
    $csvContent = "name;email;phone;birthdate\n" .
        "John Doe;john@example.com;123456789;1990-01-01\n" .
        "Jane Doe;john@example.com;987654321;1992-05-10";

    $tmp = tempnam(sys_get_temp_dir(), 'csv');
    file_put_contents($tmp, $csvContent);

    $importer = new ContactImporter();
    $summary = $importer->import($tmp, 'csv');

    expect($summary['imported'])->toBe(1);
    expect($summary['duplicates'])->toBe(1);
    expect(Contact::count())->toBe(1);
});

it('skips invalid rows', function () {
    $csvContent = "name;email;phone;birthdate\n" .
        "Invalid;;123456;not-a-date";

    $tmp = tempnam(sys_get_temp_dir(), 'csv');
    file_put_contents($tmp, $csvContent);

    $importer = new ContactImporter();
    $summary = $importer->import($tmp, 'csv');

    expect($summary['invalid'])->toBe(1);
    expect(Contact::count())->toBe(0);
});
