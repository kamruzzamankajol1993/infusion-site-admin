<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\Client;
use App\Models\Country;
use App\Models\ProjectCategory; // <-- Import ProjectCategory
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date; // For Excel date conversion
use Illuminate\Support\Str; // <-- Import Str for slug generation

class ProjectsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * Map Excel row data to a Project model.
    * Handles creation of related models (Country, Category, Client) if they don't exist.
    * Generates a unique slug for the project.
    *
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // --- Find or Create Country ---
        // Find by name, or create if it doesn't exist. Set status to true for new ones.
        $country = Country::firstOrCreate(
            ['name' => trim($row['country_name'])], // Trim whitespace
            ['status' => true] // Default status for newly created countries
        );
        // -----------------------------

        // --- Find or Create Category ---
        // Find by name, or create if it doesn't exist.
        // NOTE: Newly created categories won't have an image via this import.
        $category = ProjectCategory::firstOrCreate(
            ['name' => trim($row['category_name'])] // Trim whitespace
            // Add other defaults if needed, e.g., 'image' => null
        );
        // ------------------------------

        // Find Client ID from name (creates if new, same as before)
        $client = Client::firstOrCreate(
            ['name' => trim($row['client_name'])] // Trim whitespace
        );

        // --- Date Handling ---
        // Handles both Excel serial dates (numbers) and date strings
        $agreementDate = $row['agreement_signing_date'];
        $formattedDate = null; // Initialize
        if (!empty($agreementDate)) {
            try {
                if (is_numeric($agreementDate)) {
                    // Convert Excel serial date number to Y-m-d
                    $formattedDate = Date::excelToDateTimeObject($agreementDate)->format('Y-m-d');
                } else {
                    // Attempt to parse various string formats to Y-m-d
                    $formattedDate = Carbon::parse($agreementDate)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Log the error or handle invalid date format if necessary
                // For now, it will fail validation if 'required' rule is set
                 \Illuminate\Support\Facades\Log::warning("Could not parse date '{$agreementDate}' during import.");
                $formattedDate = null; // Ensure it's null if parsing fails
            }
        }
        // --- End Date Handling ---

        // --- Generate Slug ---
        // Generate a unique slug based on the title
        $slug = $this->generateUniqueSlug(trim($row['title']));
        // --------------------

        // Create and return the Project model instance
        return new Project([
            'title'                  => trim($row['title']),
            'slug'                   => $slug, // Use generated slug
            'description'            => trim($row['description']),
            'category_id'            => $category->id, // Use Category ID
            'client_id'              => $client->id,
            'country_id'             => $country->id, // Use Country ID
            'status'                 => strtolower(trim($row['status'])), // Lowercase and trim
            'agreement_signing_date' => $formattedDate, // Use the converted/parsed date
            // Convert boolean input (1/0, TRUE/FALSE, Yes/No) robustly
            'is_flagship'            => filter_var($row['is_flagship'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            'service'               => $row['service'],
        ]);
    }

    /**
     * Define the validation rules for each row.
     * Uses names from Excel headers.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Validate input fields before model creation attempt
            'title' => 'required|string', // Ensure title itself is unique initially
            'description' => 'required|string',
            'category_name' => 'required|string|max:255', // Validate the name is provided
            'client_name' => 'required|string|max:255',   // Validate the name is provided
            'country_name' => 'required|string|max:255',  // Validate the name is provided
            'status' => ['required', Rule::in(['pending', 'ongoing', 'complete'])],
            'agreement_signing_date' => 'required', // Basic required check, conversion handles format
            'is_flagship' => 'required|boolean', // Checks for 1, 0, true, false, '1', '0'
        ];
    }

    /**
     * Custom validation messages for better user feedback.
     *
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'title.required' => 'The project title is required.',
            'title.unique' => 'A project with this title already exists in the database.',
            'description.required' => 'The project description is required.',
            'category_name.required' => 'The category_name is required.',
            'client_name.required' => 'The client_name is required.',
            'country_name.required' => 'The country_name is required.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be one of: pending, ongoing, complete.',
            'agreement_signing_date.required' => 'The agreement_signing_date is required.',
            // Add custom message for date conversion failure if needed, although model() handles parsing
            'is_flagship.required' => 'The is_flagship field is required.',
            'is_flagship.boolean' => 'The is_flagship column must be 1/true/yes or 0/false/no.',
        ];
    }

    /**
     * Generate a unique slug for the project title during import.
     * Checks against the database to ensure uniqueness *before* the model is saved.
     *
     * @param string $title
     * @return string
     */
    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check uniqueness directly within the import process against the Project table
        while (Project::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        return $slug;
    }
}