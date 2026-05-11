@php
    $allProposedInterventions = collect($proposedInterventions ?? [])->values();
    $rowsPerPage = 3;
    $paginatedProposedInterventions = $allProposedInterventions->chunk($rowsPerPage);
    if ($paginatedProposedInterventions->isEmpty()) {
        $paginatedProposedInterventions = collect([collect()]);
    }
@endphp

<style>
    /* Download-specific styles for proposed form */
    #proposedDocument.downloading .dept-value {
        padding-bottom: 7px !important;
        vertical-align: baseline !important;
    }

    #proposedDocument.downloading table thead th {
        padding: 2px 6px 14px !important;
    }
</style>

@foreach($paginatedProposedInterventions as $pageInterventions)
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
             <p style="font-family: Arial; font-size: 14px; color: #000000; margin: 0;">Republic of the Philippines</p>
             <h1 style="font-family: Bookman Old Style; font-size: 20px; font-weight: bold; color: #000000; margin: 0;">CAVITE STATE UNIVERSITY</h1>
             <p style="font-family: Arial; font-size: 14px; font-weight: bold; color: #000000; margin: 1px 0 0 0; line-height: 1;">Don Severino de las Alas Campus</p>
             <p style="font-family: Arial; font-size: 14px; color: #000000; margin: 0; line-height: 1;">Indang, Cavite</p>
             <p style="font-family: Arial; font-size: 14px; color: #000000; margin: 0; line-height: 1;">(046) 483-9250</p>
             <a href="https://www.cvsu.edu.ph" style="font-family: Arial; font-size: 14px; font-style: italic; margin: 0; display: block; line-height: 1;">www.cvsu.edu.ph</a>
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
    <h3 style="font-size: 15px; font-weight: bold; margin: 0;">PROPOSED LEARNING & DEVELOPMENT INTERVENTIONS</h3>
    <p style="font-size: 15px; font-weight: bold; margin: 5px 0;">FY {{ $summarySelectedYear ?? now()->format('Y') }}</p>
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
<table style="font-family: Arial; font-size: 14px; color: #000000; margin-bottom: 30px; width: 100%; table-layout: fixed; border-collapse: collapse;">
    <thead>
        <tr>
            <th colspan="2" style="font-family: Arial; border: 1px solid #000; solid #000; padding: 6px 8px; text-align: center; vertical-align: top; width: 28.56%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; white-space: normal; word-break: break-word; overflow-wrap: anywhere;">L & D INTERVENTIONS</th>
            <th rowspan="2" style="font-family: Arial; border: 1px solid #000; solid #000; padding: 6px 8px; text-align: center; vertical-align: top; width: 14.28%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; white-space: normal; word-break: break-word; overflow-wrap: anywhere;">BUDGET</th>
            <th rowspan="2" style="font-family: Arial; border: 1px solid #000; solid #000; padding: 6px 8px; text-align: center; vertical-align: top; width: 14.28%; color: #000; font-size: 13px; font-weight: 400; line-height: 1.05; white-space: normal; word-break: break-word; overflow-wrap: anywhere;">EXPECTED NUMBER OF PARTICIPANTS</th>
            <th rowspan="2" style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; vertical-align: top; width: 14.28%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; white-space: normal; word-break: break-word; overflow-wrap: anywhere;">DATE/S</th>
            <th rowspan="2" style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; vertical-align: top; width: 14.28%; color: #000; font-size: 13px; font-weight: 400; line-height: 1.05; white-space: normal; word-break: break-word; overflow-wrap: anywhere;">PERSON RESPONSIBLE</th>
            <th rowspan="2" style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; vertical-align: top; width: 14.28%; color: #000; font-size: 13px; font-weight: 400; line-height: 1.05; white-space: normal; word-break: break-word; overflow-wrap: anywhere;">TARGET PARTICIPANTS</th>
        </tr>
        <tr>
            <th style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; vertical-align: top; width: 14.28%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; white-space: normal; word-break: break-word; overflow-wrap: anywhere;">TITLE</th>
            <th style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; vertical-align: top; width: 14.28%; color: #000; font-size: 14px; font-weight: 400; line-height: 1.05; white-space: normal; word-break: break-word; overflow-wrap: anywhere;">OBJECTIVES</th>
        </tr>
    </thead>
    <tbody class="proposed-table-body">
        @foreach($pageInterventions as $row)
            <tr>
                <td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top;">
                    <div style="white-space: normal; word-break: break-all; overflow-wrap: anywhere; line-height: 1.2;">{{ $row->title ?? '' }}</div>
                </td>
                <td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top;">
                    <div style="white-space: normal; word-break: break-all; overflow-wrap: anywhere; line-height: 1.2;">{{ $row->objectives ?? '' }}</div>
                </td>
                <td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->budget ?? '' }}</td>
                <td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->expected_number_of_participants ?? '' }}</td>
                <td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->dates ? \Carbon\Carbon::parse($row->dates)->format('F j, Y') : '' }}</td>
                <td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->person_responsible ?? '' }}</td>
                <td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; white-space: normal; word-break: break-all; overflow-wrap: anywhere;">{{ $row->target_participants ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

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
        <div style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 4px 0; width: 70%;">Willie Buclatin</div>
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
        Download
    </button>
    <button id="closeIdapModal2" class="btn-danger text-white px-6 py-2 rounded hover:bg-gray-700 transition flex items-center gap-2" style="background-color: #6b7280;">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Close
    </button>
</div>
