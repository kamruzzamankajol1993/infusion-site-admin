<?php

namespace App\Exports;

use App\Models\JobApplicant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class JobApplicantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $applicants;

    public function __construct(Collection $applicants)
    {
        $this->applicants = $applicants;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->applicants;
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'Email',
            'Phone Number',
            'Date of Birth',
            'Applied Job Title',
            'Total Experience (Years)',
            'Educational Background',
            'Working Experience',
            'Address',
            'Additional Info',
            'Applied On',
            'CV File Name',
        ];
    }

    /**
     * Map the data row from the collection to the columns defined in headings().
     * @param JobApplicant $applicant
     * @return array
     */
    public function map($applicant): array
    {
        $appliedDate = $applicant->created_at ? Carbon::parse($applicant->created_at)->format('Y-m-d H:i') : 'N/A';
        $dob = $applicant->date_of_birth ? Carbon::parse($applicant->date_of_birth)->format('Y-m-d') : 'N/A';
        
        return [
            $applicant->id,
            $applicant->full_name,
            $applicant->email,
            $applicant->phone_number,
            $dob,
            $applicant->career->title ?? 'N/A', // Assuming 'career' relationship is loaded
            $applicant->total_year_of_experience,
            $applicant->educational_background,
            $applicant->working_experience,
            $applicant->address,
            $applicant->additional_information,
            $appliedDate,
            basename($applicant->cv) ?? 'N/A',
        ];
    }
}