<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Summary of L&D Plan - L&D Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/development-objectives-list.css') }}">
    <style>
        body {
            background-color: #fff7ed;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #ff6b35;
        }
        .btn-primary:hover {
            background-color: #e55a2b;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-in_progress {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .progress-bar {
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background-color: #ff6b35;
            transition: width 0.3s ease;
        }
        .input-field {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background-color: #fff7ed;
            width: 100%;
            padding: 0.625rem 1rem;
            color: #374151;
        }
        .input-field:focus {
            border-color: #ff6b35;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }
        .input-field::placeholder {
            color: #9ca3af;
        }
        .header-bar {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        :root {
            --page-header-height: 84px;
            --page-header-gap: 16px;
        }
        .page-header-fixed {
            position: fixed;
            top: 0;
            left: 256px;
            right: 0;
            z-index: 20;
            margin: 0;
            height: var(--page-header-height);
        }
        .page-content {
            padding-top: 0;
        }
        .page-header-spacer {
            height: calc(var(--page-header-height) + var(--page-header-gap));
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        @include('chairperson.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64 overflow-y-auto">
            <div class="p-8 page-content">
                <!-- Header -->
                <div class="header-bar page-header-fixed">
                    <div class="flex items-center justify-between h-full min-h-16">
                        <div>
                            <p class="text-gray-600 text-base">Chairperson / <span class="text-orange-600 font-semibold">Summary of LND</span></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Year Filter -->
                            <form method="GET" action="{{ route('chairperson.summary-lnd') }}" class="flex items-center gap-2">
                                <label for="summaryYear" class="text-gray-500 text-sm">Year</label>
                                <select name="summaryYear" id="summaryYear" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:border-orange-600 focus:ring-2 focus:ring-orange-200" style="background-color: white;">
                                    @foreach($summaryAvailableYears as $year)
                                        <option value="{{ $year }}" {{ $year == $summarySelectedYear ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            <span class="text-gray-300 text-base">|</span>
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

                <!-- Forms Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Form Proposed Card -->
                    <div class="card p-8 shadow-lg hover:shadow-xl transition">
                        <div class="flex items-center justify-center mb-6">
                            <div class="p-4 rounded-full bg-orange-100">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0A5 5 0 017 12z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4 mt-4 text-center">PROPOSED LEARNING & DEVELOPMENT INTERVENTIONS</h3>
                        <p class="text-gray-600 text-center mb-6">View and manage proposed learning development objectives and plans.</p>
                        <button onclick="openIdapModal('proposed')" class="w-full px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition">
                            View Form
                        </button>
                    </div>

                    <!-- Form Conducted Card -->
                    <div class="card p-8 shadow-lg hover:shadow-xl transition">
                        <div class="flex items-center justify-center mb-6">
                            <div class="p-4 rounded-full bg-orange-100">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-800 mb-2 text-center">SUMMARY OF LEARNING & DEVELOPMENT INTERVENTIONS CONDUCTED</h3>
                        <p class="text-gray-600 text-center mb-6">View and manage conducted learning development activities and results.</p>
                        <button onclick="openIdapModal('conducted')" class="w-full px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition">
                            View Form
                        </button>
                    </div>
                </div>

                <!-- Add Data Form -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8">
                    <!-- Modal-style header -->
                    <div class="flex items-center gap-3 px-8 pt-8 pb-6 border-b border-gray-100">
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600 flex-shrink-0">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Add New Entry</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Fill in the details to add a new row to the summary forms</p>
                        </div>
                    </div>

                    <div class="px-8 py-6 space-y-4">
                        <!-- Tab Navigation -->
                        <div class="flex border-b border-gray-200 mb-2">
                            <button id="proposedTab" class="px-5 py-2.5 text-sm font-semibold text-orange-600 border-b-2 border-orange-600" onclick="switchTab('proposed')">
                                Proposed Form
                            </button>
                            <button id="conductedTab" class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700" onclick="switchTab('conducted')">
                                Conducted Form
                            </button>
                        </div>

                        <!-- Proposed Form Tab -->
                        <div id="proposedFormTab" class="tab-content space-y-4">
                            <form id="proposedForm">
                                <!-- Section 1: Basic Information -->
                                <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                                    <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                        <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">1</span>
                                        <h3 class="font-semibold text-gray-800 text-sm">Basic Information</h3>
                                    </div>
                                    <div class="px-5 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="my-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                                                <input type="text" name="proposed_title" placeholder="Enter title" class="input-field" required>
                                            </div>
                                            <div class="my-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Objectives <span class="text-red-500">*</span></label>
                                                <input type="text" name="proposed_objectives" placeholder="Enter objectives" class="input-field" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Schedule & Participants -->
                                <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                                    <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                        <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">2</span>
                                        <h3 class="font-semibold text-gray-800 text-sm">Schedule &amp; Participants</h3>
                                    </div>
                                    <div class="px-5 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Budget</label>
                                                <input type="number" name="proposed_budget" placeholder="Enter budget" class="input-field">
                                            </div>
                                            <div class="my-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Expected Number of Participants</label>
                                                <input type="number" name="proposed_expected_participants" placeholder="Enter number" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Date/s</label>
                                                <input type="date" name="proposed_dates" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Person Responsible</label>
                                                <input type="text" name="proposed_person_responsible" placeholder="Enter person responsible" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Participants</label>
                                                <input type="text" name="proposed_target_participants" placeholder="Enter target participants" class="input-field">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button id="addProposedRowBtn" type="button" onclick="addProposedRow()" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl transition text-sm">
                                    Add Row to Proposed
                                </button>
                            </form>
                        </div>

                        <!-- Conducted Form Tab -->
                        <div id="conductedFormTab" class="tab-content hidden space-y-4">
                            <form id="conductedForm">
                                <!-- Section 1: Activity Information -->
                                <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                                    <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                        <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">1</span>
                                        <h3 class="font-semibold text-gray-800 text-sm">Activity Information</h3>
                                    </div>
                                    <div class="px-5 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Type of L&amp;D <span class="text-red-500">*</span></label>
                                                <input type="text" name="conducted_type" placeholder="Enter type" class="input-field" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                                                <input type="text" name="conducted_title" placeholder="Enter title" class="input-field" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Schedule & Details -->
                                <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                                    <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                        <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">2</span>
                                        <h3 class="font-semibold text-gray-800 text-sm">Schedule &amp; Details</h3>
                                    </div>
                                    <div class="px-5 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Date Conducted</label>
                                                <input type="date" name="conducted_date" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                                                <input type="text" name="conducted_duration" placeholder="Enter duration" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Leaving Service Provided</label>
                                                <input type="text" name="conducted_leaving_service" placeholder="Enter service" class="input-field">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 3: Participants & Results -->
                                <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                                    <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                        <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">3</span>
                                        <h3 class="font-semibold text-gray-800 text-sm">Participants &amp; Results</h3>
                                    </div>
                                    <div class="px-5 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Number of Participants</label>
                                                <input type="number" name="conducted_target_participants" placeholder="Enter number" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Actual Number of Participants</label>
                                                <input type="number" name="conducted_actual_participants" placeholder="Enter number" class="input-field">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Completion Rate</label>
                                                <input type="number" name="conducted_completion_date" placeholder="Enter completion rate (0-100)" class="input-field" min="0" max="100">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Documentation</label>
                                                <input type="text" name="conducted_proof" placeholder="Enter proof documentation" class="input-field">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button id="addConductedRowBtn" type="button" onclick="addConductedRowFromForm()" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl transition text-sm">
                                    Add Row to Conducted
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- IDAP Modal (same pattern as list.blade.php) -->
    <div id="idapModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 idap-modal-container">
        <div class="bg-white rounded-lg w-full max-h-[95vh] overflow-y-auto" style="max-width: 1400px;">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex items-center justify-between" style="z-index: 10; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <h2 id="idapModalTitle" class="text-xxl font-bold text-gray-800">Summary Form</h2>
                <button id="closeIdapModal" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="idapDocument">
                <div id="proposedDocument" class="hidden">
                    @include('development-objectives.partials.form-proposed')
                </div>
                <div id="conductedDocument" class="hidden">
                    @include('development-objectives.partials.form-conducted')
                </div>
            </div>
        </div>
    </div>

    <script>
        function openIdapModal(formType) {
            const modal = document.getElementById('idapModal');
            const modalTitle = document.getElementById('idapModalTitle');
            const proposedDocument = document.getElementById('proposedDocument');
            const conductedDocument = document.getElementById('conductedDocument');

            proposedDocument.classList.add('hidden');
            conductedDocument.classList.add('hidden');

            if (formType === 'proposed') {
                modalTitle.textContent = 'Proposed Form';
                proposedDocument.classList.remove('hidden');
            } else {
                modalTitle.textContent = 'Conducted Form';
                conductedDocument.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeIdapModal() {
            const modal = document.getElementById('idapModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function switchTab(tabType) {
            const proposedTab = document.getElementById('proposedTab');
            const conductedTab = document.getElementById('conductedTab');
            const proposedFormTab = document.getElementById('proposedFormTab');
            const conductedFormTab = document.getElementById('conductedFormTab');

            if (tabType === 'proposed') {
                proposedTab.classList.add('text-orange-600', 'border-b-2', 'border-orange-600');
                proposedTab.classList.remove('text-gray-600', 'hover:text-gray-800');
                conductedTab.classList.remove('text-orange-600', 'border-b-2', 'border-orange-600');
                conductedTab.classList.add('text-gray-600', 'hover:text-gray-800');
                proposedFormTab.classList.remove('hidden');
                conductedFormTab.classList.add('hidden');
            } else {
                conductedTab.classList.add('text-orange-600', 'border-b-2', 'border-orange-600');
                conductedTab.classList.remove('text-gray-600', 'hover:text-gray-800');
                proposedTab.classList.remove('text-orange-600', 'border-b-2', 'border-orange-600');
                proposedTab.classList.add('text-gray-600', 'hover:text-gray-800');
                conductedFormTab.classList.remove('hidden');
                proposedFormTab.classList.add('hidden');
            }
        }

        let isSavingProposed = false;

        function addProposedRow() {
            if (isSavingProposed) {
                return;
            }

            const form = document.getElementById('proposedForm');
            const addButton = document.getElementById('addProposedRowBtn');
            const title = form.elements['proposed_title'].value;
            const objectives = form.elements['proposed_objectives'].value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

            if (!title || !objectives) {
                alert('Please fill in required fields (Title and Objectives)');
                return;
            }

            isSavingProposed = true;
            if (addButton) {
                addButton.disabled = true;
                addButton.classList.add('opacity-60', 'cursor-not-allowed');
            }

            const payload = new URLSearchParams({
                _token: csrfToken,
                proposed_title: title,
                proposed_objectives: objectives,
                proposed_budget: form.elements['proposed_budget'].value,
                proposed_expected_participants: form.elements['proposed_expected_participants'].value,
                proposed_dates: form.elements['proposed_dates'].value,
                proposed_person_responsible: form.elements['proposed_person_responsible'].value,
                proposed_target_participants: form.elements['proposed_target_participants'].value,
            });

            fetch('{{ route("chairperson.add-proposed") }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: payload.toString()
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    form.reset();
                    alert('✓ Proposed form data saved successfully.');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to save'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving data: ' + error.message);
            })
            .finally(() => {
                isSavingProposed = false;
                if (addButton) {
                    addButton.disabled = false;
                    addButton.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            });
        }

        let isSavingConducted = false;

        function addConductedRowFromForm() {
            if (isSavingConducted) {
                return;
            }

            const form = document.getElementById('conductedForm');
            const addButton = document.getElementById('addConductedRowBtn');
            const type = form.elements['conducted_type'].value;
            const title = form.elements['conducted_title'].value;
            const completionRateRaw = (form.elements['conducted_completion_date'].value || '').trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

            if (!type || !title) {
                alert('Please fill in required fields (Type and Title)');
                return;
            }

            if (completionRateRaw !== '') {
                const completionRate = Number(completionRateRaw);
                if (Number.isNaN(completionRate) || completionRate < 0 || completionRate > 100) {
                    alert('Completion Rate must be between 0 and 100.');
                    return;
                }
            }

            isSavingConducted = true;
            if (addButton) {
                addButton.disabled = true;
                addButton.classList.add('opacity-60', 'cursor-not-allowed');
            }

            const payload = new URLSearchParams({
                _token: csrfToken,
                conducted_type: type,
                conducted_title: title,
                conducted_date: form.elements['conducted_date'].value,
                conducted_duration: form.elements['conducted_duration'].value,
                conducted_leaving_service: form.elements['conducted_leaving_service'].value,
                conducted_target_participants: form.elements['conducted_target_participants'].value,
                conducted_actual_participants: form.elements['conducted_actual_participants'].value,
                conducted_completion_date: completionRateRaw,
                conducted_proof: form.elements['conducted_proof'].value,
            });

            fetch('{{ route("chairperson.add-conducted") }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: payload.toString()
            })
            .then(response => response.json()
                .catch(() => ({}))
                .then(data => ({ ok: response.ok, status: response.status, data })))
            .then(({ ok, status, data }) => {
                console.log('Response status:', status);
                console.log('Response data:', data);

                if (!ok || !data.success) {
                    const message = data.message || `Request failed (${status})`;
                    throw new Error(message);
                }

                form.reset();
                alert('✓ Conducted form data saved successfully.');
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving data: ' + error.message);
            })
            .finally(() => {
                isSavingConducted = false;
                if (addButton) {
                    addButton.disabled = false;
                    addButton.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('idapModal');
            const closeButton = document.getElementById('closeIdapModal');

            closeButton.addEventListener('click', closeIdapModal);

            // Event delegation handles BOTH forms' Print and Close buttons.
            // Both partials share the same id="printIdapButton" / id="closeIdapModal2",
            // so getElementById would only ever find the first one — delegation avoids that.
            modal.addEventListener('click', async function(e) {
                // Close buttons inside each form footer
                if (e.target.closest('#closeIdapModal2')) {
                    closeIdapModal();
                    return;
                }

                // Print button (either proposed or conducted)
                const printBtn = e.target.closest('#printIdapButton');
                if (!printBtn) return;

                // Find which sub-document is visible right now.
                // html2canvas returns a 0×0 canvas for hidden (display:none) elements,
                // which makes jsPDF throw "Invalid argument passed to jsPDF.scale".
                const proposed  = document.getElementById('proposedDocument');
                const conducted = document.getElementById('conductedDocument');
                const documentTarget =
                    (proposed  && !proposed.classList.contains('hidden'))  ? proposed  :
                    (conducted && !conducted.classList.contains('hidden')) ? conducted : null;

                if (!documentTarget) return;

                const filename = (documentTarget === proposed)
                    ? 'Proposed_Learning_Development_Form.pdf'
                    : 'Conducted_Learning_Development_Form.pdf';
                const originalButtonHtml = printBtn.innerHTML;

                printBtn.disabled = true;
                printBtn.textContent = 'Generating...';

                // Temporarily remove the modal's overflow clipping so html2canvas
                // can measure content that is scrolled out of view.
                const modalScroller = document.querySelector('#idapModal > div');
                const savedOverflow  = modalScroller ? modalScroller.style.overflow  : '';
                const savedMaxHeight = modalScroller ? modalScroller.style.maxHeight : '';
                if (modalScroller) {
                    modalScroller.style.overflow  = 'visible';
                    modalScroller.style.maxHeight = 'none';
                }

                try {
                    const pages   = Array.from(documentTarget.querySelectorAll('.a4-page'));
                    const targets = pages.length > 0 ? pages : [documentTarget];
                    const { jsPDF } = window.jspdf;
                    const pdf  = new jsPDF({ unit: 'mm', format: 'a4', orientation: 'landscape' });
                    const pdfW = pdf.internal.pageSize.getWidth();
                    const pdfH = pdf.internal.pageSize.getHeight();

                    const CAPTURE_WIDTH = 1122;

                    // Apply download-only styles during capture.
                    documentTarget.classList.add('downloading');

                    for (let i = 0; i < targets.length; i++) {
                        if (i > 0) pdf.addPage();

                        // Force A4-landscape width so the capture matches the real form width
                        const prevStyle = targets[i].getAttribute('style') || '';
                        targets[i].style.width    = CAPTURE_WIDTH + 'px';
                        targets[i].style.minWidth = CAPTURE_WIDTH + 'px';
                        targets[i].style.maxWidth = CAPTURE_WIDTH + 'px';

                        // Patch <th> padding inline so html2canvas picks it up
                        const thCells = Array.from(targets[i].querySelectorAll('thead th'));
                        const thPrevPaddings = thCells.map(th => th.style.padding);
                        const thPrevVAligns = thCells.map(th => th.style.verticalAlign);
                        thCells.forEach(th => { th.style.padding = '8px 6px'; th.style.verticalAlign = 'top'; });

                        // Lower the header divider line for proposed/conducted downloads.
                        const headerSections = Array.from(targets[i].querySelectorAll('div[style*="border-bottom: 2px solid #000"]'));
                        const prevHeaderPaddingBottom = headerSections.map(section => section.style.paddingBottom);
                        if (documentTarget === proposed || documentTarget === conducted) {
                            headerSections.forEach(section => { section.style.paddingBottom = '1.5rem'; });
                        }

                        const proposedTitle = Array.from(targets[i].querySelectorAll('h3')).find(title =>
                            title.textContent.includes('PROPOSED LEARNING & DEVELOPMENT INTERVENTIONS')
                        );
                        const proposedTitleWrapper = proposedTitle ? proposedTitle.parentElement : null;
                        const prevProposedTitleMarginTop = proposedTitleWrapper ? proposedTitleWrapper.style.marginTop : '';
                        const proposedFy = proposedTitleWrapper ? proposedTitleWrapper.querySelector('p') : null;
                        const prevProposedFyMargin = proposedFy ? proposedFy.style.margin : '';
                        const prevProposedFyLineHeight = proposedFy ? proposedFy.style.lineHeight : '';
                        if (documentTarget === proposed && proposedTitleWrapper) {
                            proposedTitleWrapper.style.marginTop = '10px';
                        }
                        if (documentTarget === proposed && proposedFy) {
                            proposedFy.style.margin = '-2px 0 0 0';
                            proposedFy.style.lineHeight = '1';
                        }

                        const conductedTitle = Array.from(targets[i].querySelectorAll('h3')).find(title =>
                            title.textContent.includes('SUMMARY OF LEARNING & DEVELOPMENT INTERVENTIONS CONDUCTED')
                        );
                        const conductedTitleWrapper = conductedTitle ? conductedTitle.parentElement : null;
                        const prevConductedTitleMarginTop = conductedTitleWrapper ? conductedTitleWrapper.style.marginTop : '';
                        const conductedFy = conductedTitleWrapper ? conductedTitleWrapper.querySelector('p') : null;
                        const prevConductedFyMargin = conductedFy ? conductedFy.style.margin : '';
                        const prevConductedFyLineHeight = conductedFy ? conductedFy.style.lineHeight : '';
                        if (documentTarget === conducted && conductedTitleWrapper) {
                            conductedTitleWrapper.style.marginTop = '10px';
                        }
                        if (documentTarget === conducted && conductedFy) {
                            conductedFy.style.margin = '-2px 0 0 0';
                            conductedFy.style.lineHeight = '1';
                        }

                        const proposedDeptValue = targets[i].querySelector('.dept-value');
                        const proposedDeptBlock = proposedDeptValue ? proposedDeptValue.closest('div') : null;
                        const prevProposedDeptMarginTop = proposedDeptBlock ? proposedDeptBlock.style.marginTop : '';
                        const proposedTable = targets[i].querySelector('table');
                        const prevProposedTableMarginTop = proposedTable ? proposedTable.style.marginTop : '';
                        if ((documentTarget === proposed || documentTarget === conducted) && proposedDeptBlock) {
                            proposedDeptBlock.style.marginTop = '8px';
                        }
                        if ((documentTarget === proposed || documentTarget === conducted) && proposedTable) {
                            proposedTable.style.marginTop = '8px';
                        }

                        targets[i].scrollIntoView({ block: 'start' });
                        await new Promise(r => setTimeout(r, 150));

                        const canvas = await window.html2canvas(targets[i], {
                            scale: 2,
                            useCORS: true,
                            allowTaint: true,
                            backgroundColor: '#ffffff',
                            logging: false,
                            scrollX: 0,
                            scrollY: -window.scrollY,
                            windowWidth:  CAPTURE_WIDTH,
                            width:        CAPTURE_WIDTH,
                            imageTimeout: 0,
                            removeContainer: true
                        });

                        targets[i].setAttribute('style', prevStyle);
                        thCells.forEach((th, idx) => { th.style.padding = thPrevPaddings[idx]; });
                        thCells.forEach((th, idx) => { th.style.verticalAlign = thPrevVAligns[idx]; });
                        headerSections.forEach((section, idx) => { section.style.paddingBottom = prevHeaderPaddingBottom[idx]; });
                        if (proposedTitleWrapper) {
                            proposedTitleWrapper.style.marginTop = prevProposedTitleMarginTop;
                        }
                        if (proposedFy) {
                            proposedFy.style.margin = prevProposedFyMargin;
                            proposedFy.style.lineHeight = prevProposedFyLineHeight;
                        }
                        if (conductedTitleWrapper) {
                            conductedTitleWrapper.style.marginTop = prevConductedTitleMarginTop;
                        }
                        if (conductedFy) {
                            conductedFy.style.margin = prevConductedFyMargin;
                            conductedFy.style.lineHeight = prevConductedFyLineHeight;
                        }
                        if (proposedDeptBlock) {
                            proposedDeptBlock.style.marginTop = prevProposedDeptMarginTop;
                        }
                        if (proposedTable) {
                            proposedTable.style.marginTop = prevProposedTableMarginTop;
                        }

                        if (!canvas || canvas.width === 0 || canvas.height === 0) {
                            throw new Error('Page ' + (i + 1) + ' rendered with zero dimensions.');
                        }

                        const imgW    = pdfW;
                        const imgH    = (canvas.height / canvas.width) * pdfW;
                        const downloadYOffset = -8;
                        const offsetY = downloadYOffset;
                        pdf.addImage(canvas.toDataURL('image/jpeg', 0.98), 'JPEG', 0, offsetY, imgW, Math.min(imgH, pdfH));
                    }

                    pdf.save(filename);
                } catch (err) {
                    alert('Error generating PDF: ' + err.message);
                } finally {
                    documentTarget.classList.remove('downloading');
                    if (modalScroller) {
                        modalScroller.style.overflow  = savedOverflow;
                        modalScroller.style.maxHeight = savedMaxHeight;
                    }
                    printBtn.disabled = false;
                    printBtn.innerHTML = originalButtonHtml;
                }
            });

            // Close on backdrop click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeIdapModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeIdapModal();
                }
            });
        });
    </script>
</body>
</html>
