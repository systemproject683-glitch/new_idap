@php
    $allConductedInterventions = collect($conductedInterventions ?? [])->values();
    $rowsPerPage = 3;
    $paginatedConductedInterventions = $allConductedInterventions->chunk($rowsPerPage);
    if ($paginatedConductedInterventions->isEmpty()) {
        $paginatedConductedInterventions = collect([collect()]);
    }
@endphp

<style>
    /* Download-specific styles for conducted form */
    #conductedDocument.downloading .dept-value {
        padding-bottom: 7px !important;
        vertical-align: baseline !important;
    }

    #conductedDocument.downloading table thead th {
        padding: 4px 6px 12px !important;
        font-size: 14px !important;
    }

    #conductedDocument.downloading table tbody td {
        padding: 10px 4px !important;
    }
</style>

@foreach($paginatedConductedInterventions as $pageInterventions)
<div class="a4-page" style="page-break-after: {{ $loop->last ? 'auto' : 'always' }};">
<!-- Header Section with Logos -->
<div class="flex items-start justify-between mb-4 pb-3" style="border-bottom: 2px solid #000;">
    <!-- CVSU Logo -->
    <div style="width: 110px; text-align: center; flex-shrink: 0; padding-top: 0;">
            @if(file_exists(public_path('images/cvsu-logo.png')))
        <img src="{{ asset('images/cvsu-logo.png') }}" alt="CVSU Logo" style="width: 110px; height: 110px; object-fit: contain; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
        @else
            <svg viewBox="0 0 100 100" style="width: 80px; height: 80px; display: block; margin: 0 auto;">
                <defs>
                    <linearGradient id="cvsuGradAttended" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#4a7c59;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#2d5016;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <polygon points="50,5 95,50 50,95 5,50" fill="url(#cvsuGradAttended)" stroke="#1a3a0a" stroke-width="2"/>
                <polygon points="50,15 85,50 50,85 15,50" fill="#a8d5a8" stroke="none"/>
                <circle cx="50" cy="45" r="8" fill="#f5b041"/>
                <path d="M 50 30 Q 45 35 45 42 Q 45 50 50 55 Q 55 50 55 42 Q 55 35 50 30" fill="#ff8c00"/>
                <circle cx="50" cy="38" r="5" fill="#ffd700"/>
            </svg>
        @endif
    </div>

     <!-- Center Header Text -->
      <div style="text-align: center; padding: 0; line-height: 1;">
             <p style="font-family: Arial; font-size: 13px; color: #000000; margin: 0;">Republic of the Philippines</p>
             <h1 style="font-family: Bookman Old Style; font-size: 20px; font-weight: bold; color: #000000; margin: 0;">CAVITE STATE UNIVERSITY</h1>
             <p style="font-family: Arial; font-size: 13px; font-weight: bold; color: #000000; margin: 1px 0 0 0; line-height: 1;">Don Severino de las Alas Campus</p>
             <p style="font-family: Arial; font-size: 13px; color: #000000; margin: 0; line-height: 1;">Indang, Cavite</p>
             <p style="font-family: Arial; font-size: 13px; color: #000000; margin: 0; line-height: 1;">(046) 483-9250</p>
             <a href="https://www.cvsu.edu.ph" style="font-family: Arial; font-size: 13px; font-style: italic; margin: 0; display: block; line-height: 1;">www.cvsu.edu.ph</a>
            <p style="font-family: Arial; font-size: 16px; font-weight: bold; color: #000000; margin-top: 30px;">COLLEGE OF ENGINEERING AND INFORMATION TECHNOLOGY</p>
    </div>

    <!-- Bagong Pilipinas Logo -->
    <div style="width: 110px; text-align: center; flex-shrink: 0; padding-top: 0;">
                                        @if(file_exists(public_path('images/bagong-pilipinas-logo.png')))
                                            <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" alt="Bagong Pilipinas Logo" style="width: 110px; height: 110px; object-fit: contain; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
        @else
            <svg viewBox="0 0 100 100" style="width: 80px; height: 80px; display: block; margin: 0 auto;">
                <defs>
                    <linearGradient id="bpGradTopAttended" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ffd700;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#ffed4e;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="bpGradBotAttended" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#1f4788;stop-opacity:1" />
                        <stop offset="50%" style="stop-color:#c41e3a;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#ffd700;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <circle cx="50" cy="35" r="22" fill="url(#bpGradTopAttended)"/>
                <path d="M 35 50 Q 30 60 35 75 Q 50 85 65 75 Q 70 60 65 50 Z" fill="url(#bpGradBotAttended)"/>
                <circle cx="50" cy="65" r="8" fill="#ffd700"/>
            </svg>
        @endif
    </div>
</div>


<!-- Document Title -->
<div style="text-align: center; margin-bottom: 20px;">
    <h3 style="font-size: 15px; font-weight: bold; margin: 0;">SUMMARY OF LEARNING & DEVELOPMENT INTERVENTIONS CONDUCTED</h3>
    <p style="font-size: 15px; font-weight: bold; margin: 5px 0;">FY {{ now()->year }}</p>
</div>

<!-- Campus/Department Field -->
<div style="margin-bottom: 20px;">
    <p style="font-size: 14px; margin: 0;">
        <span>Campus/College/Unit/Department: </span>
        <span class="dept-value" style="border-bottom: 1px solid #000; display: inline-block; min-width: 400px; padding-left: 5px; line-height: 1; padding-bottom: 2px; vertical-align: baseline;">
            {{ Auth::user()->department ?? 'N/A' }}
        </span>
    </p>
</div>

<!-- Interventions Table -->
<div style="overflow-x: auto;">
<table style="font-family: Arial; font-size: 14px; color: #000000; margin-bottom: 30px; width: 100%; min-width: 800px; table-layout: fixed; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 10%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Type of L&D</th>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 12%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Title</th>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 11%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Date Conducted</th>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 8%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Duration</th>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 11%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Leaving Service Provided</th>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 11%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Target Number of Participants</th>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 11%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Actual Number of Participants</th>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 11%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Completion Rate</th>
            <th style="font-family: Arial; border: 0.5px solid #000; padding: 10px 8px; text-align: center; vertical-align: top; width: 15%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; word-break: normal; overflow-wrap: normal;">Proof of Documentation</th>
        </tr>
    </thead>
    <tbody class="conducted-table-body">
        @foreach($pageInterventions as $row)
            <tr>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->type_of_lnd ?? '' }}</td>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->title ?? '' }}</td>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->date_conducted ? \Carbon\Carbon::parse($row->date_conducted)->format('F j, Y') : '' }}</td>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->duration ?? '' }}</td>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->leaving_service_provided ?? '' }}</td>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->target_number_of_participants ?? '' }}</td>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->actual_number_of_participants ?? '' }}</td>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->completion_rate ? $row->completion_rate . '%' : '' }}</td>
                <td style="border: 0.5px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->proof_of_documentation ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

<!-- Signature Section -->
@if($loop->last)
<div style="page-break-inside: avoid; display: flex; justify-content: space-between; margin-top: 32px; font-size: 9px; gap: 40px;">
    <div style="flex: 1; text-align: left;">
        <p style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 8px 0;">Prepared by:</p>
        <div style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 4px 0; width: 70%;">{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->middle_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</div>
        <div style="border-top: 1px solid #000; padding-top: 1px; width: 70%;"></div>
        <p style="font-family: Arial; font-size: 15px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Department Chair</p>
    </div>
    <div style="flex: 1; text-align: left;">
        <p style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 8px 0;">Recommending Approval:</p>
        <div style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 4px 0; width: 70%;">Willie Buclatiin</div>
        <div style="border-top: 1px solid #000; padding-top: 1px; width: 70%;"></div>
        <p style="font-family: Arial; font-size: 15px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Dean/Campus Administrator/Director</p>
    </div>
    <div style="flex: 1; text-align: left;">
        <p style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 8px 0;">Approved by:</p>
        <div style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 4px 0; width: 70%;">(name)</div>
        <div style="border-top: 1px solid #000; padding-top: 1px; width: 70%;"></div>
        <p style="font-family: Arial; font-size: 15px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Vice President</p>
    </div>
</div>
@endif
</div>
@endforeach

<!-- Action Buttons Footer -->
<div class="bg-white border-t border-gray-200 p-4 flex items-center justify-center gap-3 sticky bottom-0">
    <button id="printIdapButton" class="btn-primary text-white px-6 py-2 rounded hover:bg-orange-600 transition flex items-center gap-2">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h-2a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm0 0h2a2 2 0 002-2m0 0V9" />
        </svg>
        Print
    </button>
    <button id="closeIdapModal2" class="btn-danger text-white px-6 py-2 rounded hover:bg-gray-700 transition flex items-center gap-2" style="background-color: #6b7280;">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Close
    </button>
</div>
