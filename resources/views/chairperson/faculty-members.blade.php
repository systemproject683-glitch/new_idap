<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Members - IDAP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #fff7ed;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
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
        .btn-primary {
            background-color: #ff6b35;
        }
        .btn-primary:hover {
            background-color: #e55a2b;
        }
        .table-header {
            background-color: #f9fafb;
            border-bottom: 2px solid #ff6b35;
        }
        table thead th {
            background-color: #f9fafb;
            border-bottom: 2px solid #ff6b35;
        }
        table tbody tr {
            transition: all 0.2s ease;
        }
        table tbody tr:hover {
            background-color: #fef3e2;
        }
        .role-badge {
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.5px;
        }
        .role-badge.faculty {
            color: #3b82f6;
        }
        .action-links a, .action-links button {
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal.show {
            display: block;
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .modal-header {
            background-color: #ff6b35;
            color: white;
            padding: 20px 24px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        .modal-close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        .modal-close:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
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
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
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
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mt-0">Faculty Members</h1>
                            <p class="text-gray-600 mt-1 mb-0 leading-tight">View and manage faculty members in the {{ Auth::user()->department }} department</p>
                        </div>
                    </div>
                </div>
                <div class="page-header-spacer"></div>
                <div class="px-5">

                <!-- Faculty Members Table -->
                <div class="card">
                    <div class="p-6">
                        @if($facultyMembers->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="table-header">
                                        <tr>
                                            <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Name</th>
                                            <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Email</th>
                                            <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Department</th>
                                            <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Role</th>
                                            <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($facultyMembers as $faculty)
                                            <tr class="border-b border-gray-100">
                                                <td class="py-4 px-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center flex-shrink-0">
                                                            <span class="text-white font-normal text-sm">
                                                                {{ strtoupper(substr($faculty->first_name ?? '', 0, 1)) . strtoupper(substr($faculty->last_name ?? '', 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div class="font-medium text-gray-900 text-sm">
                                                            {{ $faculty->first_name }} {{ $faculty->last_name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4 text-gray-600 text-sm">{{ $faculty->email }}</td>
                                                <td class="py-4 px-4">
                                                    <div class="text-gray-700 text-sm">
                                                        {{ $faculty->department ?? 'Not Assigned' }}
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <span class="role-badge faculty text-xs">
                                                        Faculty Member
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <div class="action-links flex gap-2 text-sm">
                                                        <button 
                                                            onclick="openDetailsModal({{ $faculty->id }})" 
                                                            class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer bg-transparent border-0 p-0"
                                                        >
                                                            View
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $facultyMembers->links() }}
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">No faculty members found in your department.</p>
                            </div>
                        @endif
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Faculty Member Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="text-lg font-semibold">Faculty Member Details</h2>
                <button class="modal-close" onclick="closeDetailsModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <script>
        function openDetailsModal(facultyId) {
            const modal = document.getElementById('detailsModal');
            const modalBody = document.getElementById('detailsModalBody');
            
            // Show loading state
            modalBody.innerHTML = '<div class="text-center py-8"><p class="text-gray-600">Loading...</p></div>';
            modal.classList.add('show');
            
            // Fetch details via AJAX
            fetch(`/chairperson/faculty-member/${facultyId}`)
                .then(response => response.text())
                .then(html => {
                    // Extract only the relevant content from the full page
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Get all the content from the main content area
                    const mainContent = doc.querySelector('.p-8');
                    if (mainContent) {
                        // Extract the Faculty Information card
                        const facultyInfoCard = mainContent.querySelector('.card');
                        
                        // Extract the Statistics Overview grid
                        const statsGrid = mainContent.querySelector('.grid.grid-cols-1.md\\:grid-cols-4');
                        
                        // Extract other cards
                        const allCards = mainContent.querySelectorAll('.card');
                        let completionRateCard = null;
                        let objectivesCard = null;
                        
                        let cardIndex = 0;
                        for (let card of allCards) {
                            // Skip first card (Faculty Info)
                            if (cardIndex === 0) {
                                cardIndex++;
                                continue;
                            }
                            // Skip stats grid
                            if (card.querySelector('.md\\:grid-cols-4')) {
                                cardIndex++;
                                continue;
                            }
                            // Next card should be Completion Rate
                            if (completionRateCard === null && card.innerHTML.includes('Completion Rate')) {
                                completionRateCard = card;
                                cardIndex++;
                                continue;
                            }
                            // Next card should be Objectives
                            if (objectivesCard === null && card.innerHTML.includes('Development Objectives')) {
                                objectivesCard = card;
                                cardIndex++;
                                continue;
                            }
                            cardIndex++;
                        }
                        
                        // Extract stats data
                        let statsHtml = '';
                        if (statsGrid) {
                            const statCards = statsGrid.querySelectorAll('.card');
                            statCards.forEach(card => {
                                const pElements = card.querySelectorAll('p');
                                if (pElements.length >= 2) {
                                    const number = pElements[0].innerText.trim();
                                    const label = pElements[1].innerText.trim();
                                    
                                    // Determine the color based on the content or existing classes
                                    let colorClass = 'text-gray-900';
                                    if (card.querySelector('.text-green-600')) colorClass = 'text-green-600';
                                    else if (card.querySelector('.text-blue-600')) colorClass = 'text-blue-600';
                                    else if (card.querySelector('.text-yellow-600')) colorClass = 'text-yellow-600';
                                    
                                    statsHtml += `
                                        <div class="flex justify-between items-center pb-3 border-b border-gray-200 last:border-b-0 last:pb-0">
                                            <span class="text-sm text-gray-600">${label}</span>
                                            <span class="text-lg font-bold ${colorClass}">${number}</span>
                                        </div>
                                    `;
                                }
                            });
                        }
                        
                        // Add border to faculty info card and change avatar to orange
                        if (facultyInfoCard) {
                            facultyInfoCard.style.border = '1px solid #e5e7eb';
                            facultyInfoCard.style.borderRadius = '8px';
                            
                            // Change avatar background to orange and update initials
                                const avatar = facultyInfoCard.querySelector('.rounded-full.flex.items-center.justify-center');
                                if (avatar) {
                                    avatar.style.backgroundColor = '#ff6b35';
                                    avatar.style.color = 'white';
                                    const span = avatar.querySelector('span');
                                    if (span) span.style.color = 'white';
                                    
                                    // Extract first + last name initials from name element
                                    const nameElement = facultyInfoCard.querySelector('h3.text-lg.font-semibold');
                                    if (nameElement) {
                                        const nameParts = nameElement.textContent.trim().split(/\s+/).filter(p => p.length > 0);
                                        if (nameParts.length >= 2) {
                                            const initials = nameParts[0].charAt(0).toUpperCase() + nameParts[nameParts.length - 1].charAt(0).toUpperCase();
                                            if (span) span.textContent = initials;
                                            else avatar.textContent = initials;
                                        }
                                    }
                                }
                        }

                        // Build the layout with all sections
                        let content = `
                            <div class="grid grid-cols-1 lg:grid-cols-10 gap-4">
                                <!-- Left Column (70%) -->
                                <div class="lg:col-span-7">
                                    <!-- Faculty Information -->
                                    <div style="overflow: visible; margin-bottom: 8px;">
                                        ${facultyInfoCard ? facultyInfoCard.outerHTML : ''}
                                    </div>
                                    <!-- Development Objectives -->
                                    <div>
                                        ${objectivesCard ? objectivesCard.outerHTML : '<div class="card p-6"><p class="text-gray-600">No development objectives found</p></div>'}
                                    </div>
                                </div>
                                <!-- Right Column (30%) - STICKY -->
                                <div class="lg:col-span-3" style="position: sticky; top: 0; align-self: start;">
                                    <!-- Objectives Summary -->
                                    <div class="card p-6" style="height: 220px; overflow: hidden; margin-bottom: 8px;">
                                        <h3 class="text-base font-semibold text-gray-800 mb-4">Objectives Summary</h3>
                                        <div class="space-y-0">
                                            ${statsHtml}
                                        </div>
                                    </div>
                                    <!-- Completion Rate -->
                                    <div>
                                        ${completionRateCard ? completionRateCard.outerHTML : '<div class="card p-6"><p class="text-gray-600">No completion rate data</p></div>'}
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        modalBody.innerHTML = content;
                    } else {
                        modalBody.innerHTML = '<div class="text-center py-8"><p class="text-red-600">Failed to load details</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = '<div class="text-center py-8"><p class="text-red-600">Error loading details</p></div>';
                });
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.remove('show');
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target === modal) {
                closeDetailsModal();
            }
        }
    </script>
</body>
</html>
