<!DOCTYPE html>
<html>
<head>
    <title>Job Applicants Report - {{ $filterName }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16pt;
            margin: 0;
        }
        .report-info {
            font-size: 10pt;
            margin-bottom: 15px;
        }
        .table-container {
            width: 100%;
            margin-top: 10px;
        }
        .applicant-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Ensures columns respect width settings */
        }
        .applicant-table th, .applicant-table td {
            border: 1px solid #ccc;
            padding: 5px 8px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word; /* Important to wrap long text */
        }
        .applicant-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9pt;
        }
        .applicant-table td {
            font-size: 9pt;
        }
        strong.label {
            font-weight: bold;
            display: inline-block;
            min-width: 50px;
        }
        /* Style for multi-line content within cells */
        .detail-block {
            margin-bottom: 5px;
            line-height: 1.3;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Job Applicant Detailed Report</h1>
    </div>

    <div class="report-info">
        <strong>Filter Applied:</strong> {{ $filterName }}<br>
        <strong>Total Applicants:</strong> {{ $applicants->count() }}<br>
        <strong>Generated On:</strong> {{ \Carbon\Carbon::now()->format('F, d Y') }}
    </div>

    @if ($applicants->isEmpty())
        <p style="text-align: center;">No job applicants found for this filter.</p>
    @else
        <div class="table-container">
            <table class="applicant-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 20%;">Personal & Contact</th>
                        <th style="width: 15%;">Applied For</th>
                        <th style="width: 30%;">Experience & Qualification</th>
                        <th style="width: 30%;">Background & Other Info</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $key => $applicant)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            
                            {{-- Personal & Contact Details --}}
                            <td>
                                <div class="detail-block">
                                    <strong class="label">Name:</strong> {{ $applicant->full_name }}
                                </div>
                                <div class="detail-block">
                                    {{-- UPDATED DATE FORMAT HERE --}}
                                    <strong class="label">DOB:</strong> {{ $applicant->date_of_birth ? \Carbon\Carbon::parse($applicant->date_of_birth)->format('F, d Y') : 'N/A' }}
                                </div>
                                <div class="detail-block">
                                    <strong class="label">Email:</strong> {{ $applicant->email }}
                                </div>
                                <div class="detail-block">
                                    <strong class="label">Phone:</strong> {{ $applicant->phone_number }}
                                </div>
                                <div class="detail-block">
                                    <strong class="label">Address:</strong> {{ $applicant->address }}
                                </div>
                            </td>

                            {{-- Applied Job Details --}}
                            <td>
                                <div class="detail-block">
                                    <strong>Title:</strong> {{ $applicant->career->title ?? 'N/A' }}
                                </div>
                                <div class="detail-block">
                                    {{-- UPDATED DATE FORMAT HERE --}}
                                    <strong>Applied On:</strong> {{ $applicant->created_at ? \Carbon\Carbon::parse($applicant->created_at)->format('F, d Y') : 'N/A' }}
                                </div>
                            </td>

                            {{-- Experience & Qualification --}}
                            <td>
                                <div class="detail-block">
                                    <strong class="label">Total Exp:</strong> {{ $applicant->total_year_of_experience ?? 0 }} Years
                                </div>
                                <div class="detail-block">
                                    <strong class="label">Qualification:</strong> {{ $applicant->qualification }}
                                </div>
                                <div class="detail-block">
                                    <strong>Working Experience:</strong><br>
                                    {{ $applicant->working_experience }}
                                </div>
                            </td>

                            {{-- Background & Other Info --}}
                            <td>
                                <div class="detail-block">
                                    <strong>Educational Background:</strong><br>
                                    {{ $applicant->educational_background }}
                                </div>
                                <div class="detail-block">
                                    <strong>Additional Info:</strong><br>
                                    {{ $applicant->additional_information }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>
</html>