@php
    $allAttendedObjectives = collect($objectives ?? [])->values();
    $rowsPerPage = 3;
    $paginatedAttendedObjectives = $allAttendedObjectives->chunk($rowsPerPage);
    if ($paginatedAttendedObjectives->isEmpty()) {
        $paginatedAttendedObjectives = collect([collect()]);
    }
@endphp

@foreach($paginatedAttendedObjectives as $pageIndex => $pageObjectives)
<div class="a4-page" style="page-break-after: {{ $loop->last ? 'auto' : 'always' }};">
<!-- Header Section with Logos -->
<div class="flex items-start justify-between mb-4 pb-3" style="border-bottom: 2px solid #000; padding-bottom: 24px;">
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
          <div style="text-align: center; padding: 0;">
             <p style="font-family: Arial; font-size: 13px; color: #000000; margin: 0;">Republic of the Philippines</p>
              <h1 style="font-family: Bookman Old Style; font-size: 20px; font-weight: bold; color: #000000; margin: 2px 0;">CAVITE STATE UNIVERSITY</h1>
              <p style="font-family: Arial; font-size: 13px; font-weight: bold; color: #000000; margin: 2px 0; line-height: 1;">Don Severino de las Alas Campus</p>
              <p style="font-family: Arial; font-size: 13px; color: #000000; margin: 1px 0; line-height: 1;">Indang, Cavite</p>
             <p style="font-family: Arial; font-size: 13px; color: #000000; margin: 0; line-height: 1;">(046) 483-9250</p>
             <a href="https://www.cvsu.edu.ph" style="font-family: Arial; font-size: 13px; font-style: italic; margin: 0; display: block; line-height: 1;">www.cvsu.edu.ph</a>
            <p style="font-family: Arial; font-size: 17px; font-weight: bold; color: #000000; margin-top: 30px;">COLLEGE OF ENGINEERING AND INFORMATION TECHNOLOGY</p>
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
<div style="text-align: center; line-height: 1; margin-top: 20px;">
    <h3 style="font-size: 15px; font-weight: bold; margin: 8px 0; line-height: 1;">SUMMARY OF LEARNING & DEVELOPMENT INTERVENTIONS ATTENDED</h3>
</div>
<div style="text-align: center; margin-bottom: 12px; font-size: 11px;">
    <p style="font-size: 15px; font-weight: bold; margin: 0; line-height: 1;">FY {{ $summarySelectedYear ?? now()->year }}</p>
</div>

<!-- Campus/Department Field -->
<div style="margin-bottom: 20px;">
    <p style="font-family: Arial; font-size: 14px; color: #000000; margin: 0;">
        <span>Campus/College/Unit/Department: </span>
        <span class="dept-underline-span" style="font-family: Arial; font-size: 14px; color: #000000; border-bottom: 1px solid #000; display: inline-block; min-width: 400px; padding-left: 5px; vertical-align: bottom; line-height: 1.3;">
            {{ Auth::user()->department ?? 'N/A' }}
        </span>
    </p>
</div>

<!-- Interventions Table -->
<table style="font-family: Arial; font-size: 14px; color: #000000; margin-bottom: 30px; width: 100%; table-layout: fixed; border-collapse: collapse;">
    <colgroup>
        <col style="width: 7%;">
        <col style="width: 10%;">
        <col style="width: 9%;">
        <col style="width: 8%;">
        <col style="width: 9%;">
        <col style="width: 10%;">
        <col style="width: 9%;">
        <col style="width: 8%;">
        <col style="width: 8%;">
        <col style="width: 11%;">
        <col style="width: 11%;">
    </colgroup>
    <tbody>
        {{-- Header Row 1: group spans + standalone labels (top half of fake-merged cells) --}}
        <tr>
            <td style="font-family: Arial; border: 1px solid #000; border-bottom: 0; padding: 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 14px; line-height: 1.2; vertical-align: middle; background-color: #fff; word-break: break-word; overflow-wrap: break-word;">Office</td>
            <td colspan="4" style="font-family: Arial; border: 1px solid #000; padding: 2px 8px 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 14px; line-height: 1.2; vertical-align: middle; word-break: break-word; overflow-wrap: break-word;">Target L &amp; D Intervention Based on IDAP</td>
            <td colspan="4" style="font-family: Arial; border: 1px solid #000; padding: 2px 8px 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 14px; line-height: 1.2; vertical-align: middle; word-break: break-word; overflow-wrap: break-word;">Actual L &amp; D Intervention Attended</td>
            <td style="font-family: Arial; border: 1px solid #000; border-bottom: 0; padding: 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 14px; line-height: 1.2; vertical-align: middle; background-color: #fff; word-break: break-word; overflow-wrap: break-word;">Proof of Completions</td>
            <td style="font-family: Arial; border: 1px solid #000; border-bottom: 0; padding: 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 14px; line-height: 1.2; vertical-align: middle; background-color: #fff; word-break: break-word; overflow-wrap: break-word;">REMARKS</td>
        </tr>
        {{-- Header Row 2: sub-column labels + bottom half of fake-merged cells --}}
        <tr>
            <td style="font-family: Arial; border: 1px solid #000; border-top: 0; padding: 2px 8px; text-align: center; font-weight: bold; color: #000; font-size: 13px; line-height: 1.2; background-color: #fff;"></td>
            <td style="font-family: Arial; border: 1px solid #000; padding: 6px 8px 12px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; word-break: break-word; overflow-wrap: break-word;">Type of L&amp;D<br>(training,<br>short courses etc)</td>
            <td style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; word-break: break-word; overflow-wrap: break-word;">Title</td>
            <td style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; word-break: break-word; overflow-wrap: break-word;">Period Date</td>
            <td style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; word-break: break-word; overflow-wrap: break-word;">Date of Completion</td>
            <td style="font-family: Arial; border: 1px solid #000; padding: 6px 8px 12px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; word-break: break-word; overflow-wrap: break-word;">Type of L&amp;D<br>(training,<br>short courses etc)</td>
            <td style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; word-break: break-word; overflow-wrap: break-word;">Title</td>
            <td style="font-family: Arial; border: 1px solid #000; padding: 6px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; word-break: break-word; overflow-wrap: break-word;">Period Date</td>
            <td style="font-family: Arial; border: 1px solid #000; padding: 6px 8px 12px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; word-break: break-word; overflow-wrap: break-word;">Hours/Units<br>Completed</td>
            <td style="font-family: Arial; border: 1px solid #000; border-top: 0; padding: 2px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; background-color: #fff;"></td>
            <td style="font-family: Arial; border: 1px solid #000; border-top: 0; padding: 2px 8px; text-align: center; font-weight: bold; color: #000; font-size: 12px; line-height: 1.2; background-color: #fff;"></td>
        </tr>
        {{-- Data rows --}}
        @foreach($pageObjectives as $objective)
            @php
                $periodFrom = $objective->target_date_from ?? '';
                $periodTo = $objective->target_date_to ?? '';
                $periodDate = trim($periodFrom . ($periodFrom && $periodTo ? ' - ' : '') . $periodTo);
                $completionDate = $periodDate;
            @endphp
            <tr>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ Auth::user()->department ?? '' }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $objective->objective ?? '' }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $objective->title ?? '' }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $periodDate }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $completionDate }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $objective->lnd_type ?? '' }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $objective->lnd_title ?? '' }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $objective->lnd_period_date ?? '' }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $objective->lnd_hours ?? '' }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; vertical-align: top; font-size: 14px; word-break: break-word; overflow-wrap: break-word;">{{ $objective->lnd_proof_completion ?? '' }}</td>
                <td style="font-family: Arial; border: 1px solid #000; padding: 6px 4px; font-size: 14px;">&nbsp;</td>
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
