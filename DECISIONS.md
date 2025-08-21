\# Architectural Decisions

\## 1. Contact Importer Structure

\- Implemented a dedicated service (\`App\Services\ContactImporter\`) to
centralize import logic.

\- The service supports multiple formats: CSV, TXT, XLSX, and XLS.

\- Decision to use \*\*batch inserts\*\* to optimize performance when
handling large data volumes.

\## 2. Validation Strategy

\- Laravel\'s \`Validator\` was chosen to ensure data integrity before
insertion.

\- Applied validation rules:

 - Name required

 - Email must be valid and unique

 - Phone optional (max 20 characters)

 - Birthdate must be a valid date

\- In case of failures, invalid records are collected in a summary for
user feedback.

\## 3. Duplicate Handling

\- Emails already existing in the database are skipped before insertion
(comparison in lowercase).

\- Additional check prevents duplicates within the same imported file.

\## 4. Performance

\- Use of \`Contact::insert()\` in a buffer of 250 records, avoiding
multiple queries.

\- Preloading all existing emails into memory for efficient comparison.

\- This reduced the number of queries during processing.

\## 5. Automated Testing

\- Framework chosen: \*\*PestPHP\*\*, due to its simple syntax and
Laravel integration.

\- Tests were placed inside the \`Feature/\` folder since the importer
relies on Eloquent and database interaction.

\- Implemented tests:

 - Import valid rows (confirms successful insert)

 - Ignore duplicates inside the same file

 - Skip invalid records (e.g., missing email or invalid date)

 - Endpoint test to upload and import a real CSV

\## 6. Unit vs Feature Tests Decision

\- Unit tests in Laravel do not boot the full application container nor
the database.

\- Since import logic depends on the database (Eloquent), these tests
are categorized as \*\*Feature Tests\*\*.

\- Decision: keep importer tests under \`Feature/\` to avoid unnecessary
mocks and ensure realistic validation.

\## 7. Adopted Standards

\- Code organized into clear layers (Controllers ~~Models).~~

\- Documentation files included: \`README.md\` (project overview) and
\`DECISIONS.md\` (technical decisions).
