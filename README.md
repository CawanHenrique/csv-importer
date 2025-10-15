# CSV Contact Importer

A Laravel + Vue.js application that allows importing contacts from a CSV (or Excel) file, deduplicating by email, validating fields, and displaying a processing summary with a paginated contact list.

This project was developed as part of the Alberon technical test.

---

## 🚀 Features
- **CSV/Excel Upload**
  - Supports `.csv`, `.txt`, `.xlsx`, `.xls`
  - Flexible column order (`name`, `email`, `phone`, `birthdate`)

- **Processing**
  - Deduplicate by email (both existing DB entries and duplicates inside the same file)
  - Validation rules for name, email, phone, and birthdate
  - Batch insert for performance

- **Summary**
  - Total rows read
  - Successfully imported
  - Ignored due to duplicates
  - Ignored due to validation errors

- **Contacts Listing**
  - Paginated list of imported contacts
  - Simple, clean UI with Vue.js + Inertia

---

## 🛠️ Tech Stack
- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Vue.js 3 + Inertia.js
- **Database:** MySQL 8
- **CSV/Excel Parsing:** `league/csv`, `phpoffice/phpspreadsheet`

---

## ⚙️ Installation

### Requirements
- PHP 8.5+
- Composer
- Node.js 20+
- MySQL 8

### Setup
```bash
git clone https://github.com/CawanHenrique/csv-importer.git
cd csv-importer

# Backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

# Frontend
npm install
npm run dev
```

### Run
```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000).

---

## 📊 Example Workflow
1. Go to the **Upload CSV** page.
2. Select and upload a CSV file.
3. System processes rows and displays a **summary**:
   - total, imported, duplicates, invalid rows.
4. View imported contacts in the **Contact List** page (with pagination).

---

## ✅ Automated Tests

This project includes automated tests written with **Pest** (the default testing framework in Laravel 11).

### Unit-style tests (implemented as Feature Tests)
We verify the behavior of the `ContactImporter` service with different scenarios:

- **Imports valid CSV rows**  
  Ensures that valid rows are processed and persisted in the database.

- **Ignores duplicate emails inside the file**  
  Ensures that rows with the same email are only imported once, and duplicates are tracked in the summary.

- **Skips invalid rows**  
  Ensures that rows missing required fields or with invalid data (e.g. invalid email, wrong date format) are not persisted.

### Feature test (full integration)
We simulate the real upload process through the application:

- **Uploads and imports a CSV file via endpoint**  
  Creates a fake file and posts it to the `/contacts/import` endpoint, verifying that:
  - The request redirects on success (`302`).
  - The expected number of contacts are saved in the database.

### Running the tests
```bash
php artisan test
```

---

## 📘 License
This project was created for technical evaluation purposes.
