<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary of L&D</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/development-objectives-list.css') }}">
    @vite(['resources/css/lnd-forms.css'])
</head>
<body class="min-h-screen" style="background-color: #fff7ed;">
    <div class="flex">
        @include('development-objectives.sidebar')

        <div class="flex-1 ml-64">
            <div class="p-8 page-content">
                <!-- Topbar -->
                <div class="header-bar page-header-fixed">
                    <div class="flex items-center justify-between h-full min-h-16">
                        <div>
                            <p class="text-gray-600 text-base">CEIT / <span class="text-orange-600 font-semibold">Summary of L&amp;D</span></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <form method="GET" action="/development-objectives/summary" class="flex items-center gap-2 m-0">
                                <label for="summaryYearSelect" class="text-gray-500 text-sm">Year</label>
                                <select id="summaryYearSelect" name="summaryYear" onchange="this.form.submit()" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                    @if(($summaryAvailableYears ?? collect())->count() > 0)
                                        @foreach($summaryAvailableYears as $year)
                                            <option value="{{ $year }}" {{ (string) ($summarySelectedYear ?? '') === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ $summarySelectedYear ?? now()->format('Y') }}">{{ $summarySelectedYear ?? now()->format('Y') }}</option>
                                    @endif
                                </select>
                            </form>
                            <button
                                onclick="printAttendedPage()"
                                class="px-4 py-1.5 rounded-lg text-white hover:bg-orange-600 transition flex items-center gap-2 text-sm whitespace-nowrap"
                                style="background-color: #ff6b35;"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </button>
                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 text-base">{{ now()->format('F d, Y') }}</p>
                            <span class="text-gray-300 text-base">|</span>
                            <span id="live-time" class="text-orange-500 font-semibold text-base"></span>
                        </div>
                    </div>
                </div>
                <div class="page-header-spacer"></div>



                <div id="attendedContent" style="padding: 20px; border-radius: 8px;">
                    @include('development-objectives.partials.form-attended')
                </div>
            </div>
        </div>
    </div>

    <script>
        function printAttendedPage() {
            const content = document.getElementById('attendedContent');
            if (!content) return;

            const btn = document.querySelector('button[onclick="printAttendedPage()"]');
            if (btn) { btn.disabled = true; btn.textContent = 'Generating...'; }

            // A4 landscape at 96dpi = 1122px wide. We fix every .a4-page to render
            // at exactly this width so html2canvas captures the full layout regardless
            // of the screen/sidebar viewport width.
            const CAPTURE_WIDTH = 1122;

            (async () => {
                try {
                    const pages = Array.from(content.querySelectorAll('.a4-page'));
                    const targets = pages.length > 0 ? pages : [content];
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF({ unit: 'mm', format: 'a4', orientation: 'landscape' });
                    const pdfW = pdf.internal.pageSize.getWidth();
                    const pdfH = pdf.internal.pageSize.getHeight();

                    for (let i = 0; i < targets.length; i++) {
                        if (i > 0) pdf.addPage();

                        // Force fixed width for capture, then restore
                        const el = targets[i];
                        const prevStyle = el.getAttribute('style') || '';
                        const prevMargin = el.style.margin;
                        const prevBoxShadow = el.style.boxShadow;
                        const prevOverflow = el.style.overflow;
                        const prevTransform = el.style.transform;
                        const prevBackgroundColor = el.style.backgroundColor;
                        el.style.width = CAPTURE_WIDTH + 'px';
                        el.style.minWidth = CAPTURE_WIDTH + 'px';
                        el.style.maxWidth = CAPTURE_WIDTH + 'px';
                        el.style.margin = '0 auto';
                        el.style.boxShadow = 'none';
                        el.style.overflow = 'visible';
                        el.style.transform = 'none';
                        el.style.backgroundColor = '#ffffff';

                        // Reduce side padding for PDF only so content fits like other forms.
                        const prevPaddingLeft = el.style.paddingLeft;
                        const prevPaddingRight = el.style.paddingRight;
                        el.style.paddingLeft = '32px';
                        el.style.paddingRight = '32px';

                        // Patch dept span for PDF only (html2canvas ignores vertical-align)
                        const deptSpans = el.querySelectorAll('.dept-underline-span');
                        deptSpans.forEach(s => { s.style.paddingTop = '0px'; s.style.paddingBottom = '6px'; s.style.position = 'relative'; s.style.top = '3px'; });

                        // Force Arial 15px on all table cells for PDF only
                        const allCells = el.querySelectorAll('table td, table th');
                        const prevFontFamily = Array.from(allCells).map(c => c.style.fontFamily);
                        const prevFontSize = Array.from(allCells).map(c => c.style.fontSize);
                        allCells.forEach(c => { c.style.fontFamily = 'Arial'; c.style.fontSize = '15px'; });

                        el.scrollIntoView({ block: 'start' });
                        await new Promise(r => setTimeout(r, 120));

                        const canvas = await window.html2canvas(el, {
                            scale: 2,
                            useCORS: true,
                            allowTaint: true,
                            backgroundColor: '#ffffff',
                            logging: false,
                            scrollX: 0,
                            scrollY: -window.scrollY,
                            windowWidth: CAPTURE_WIDTH,
                            width: CAPTURE_WIDTH,
                            imageTimeout: 0,
                            removeContainer: true
                        });

                        // Restore original style
                        el.setAttribute('style', prevStyle);
                        el.style.margin = prevMargin;
                        el.style.boxShadow = prevBoxShadow;
                        el.style.overflow = prevOverflow;
                        el.style.transform = prevTransform;
                        el.style.backgroundColor = prevBackgroundColor;

                        // Restore page side padding
                        el.style.paddingLeft = prevPaddingLeft;
                        el.style.paddingRight = prevPaddingRight;

                        // Restore dept span padding
                        deptSpans.forEach(s => { s.style.paddingTop = ''; s.style.paddingBottom = ''; s.style.position = ''; s.style.top = ''; });

                        // Restore table cell font
                        allCells.forEach((c, i) => { c.style.fontFamily = prevFontFamily[i]; c.style.fontSize = prevFontSize[i]; });

                        // Match other forms: fill width first, then scale down only if height exceeds page.
                        const imgHRaw = (canvas.height / canvas.width) * pdfW;
                        let drawW = pdfW;
                        let drawH = imgHRaw;
                        if (drawH > pdfH) {
                            const scaleDown = pdfH / drawH;
                            drawW = pdfW * scaleDown;
                            drawH = pdfH;
                        }
                        const offsetX = (pdfW - drawW) / 2;
                        const offsetY = 0;
                        pdf.addImage(canvas.toDataURL('image/jpeg', 0.98), 'JPEG', offsetX, offsetY, drawW, drawH);
                    }

                    pdf.save('Summary_LD.pdf');
                } catch (err) {
                    alert('Error generating PDF: ' + err.message);
                } finally {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="h-4 w-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Download';
                    }
                }
            })();
        }

        function updateTime() {
            var now = new Date();
            var h = now.getHours();
            var ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            var m = now.getMinutes().toString().padStart(2,'0');
            var s = now.getSeconds().toString().padStart(2,'0');
            document.getElementById('live-time').textContent = h+':'+m+':'+s+' '+ampm;
        }
        updateTime(); setInterval(updateTime, 1000);

    </script>
</body>
</html>
