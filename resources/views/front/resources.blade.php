<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resources - Project Management</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinite Scrollable Gantt Chart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>

        .sss .task-header{
            padding: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin:auto;
            margin-top: 10rem;
        }

        .modal-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .modal-footer button {
            margin-left: 10px;
        }
        .draggable {
            cursor: move;
            background-color: #4A5568 !important;
            border-radius: 5px !important;
            border: 1px solid #fff !important;
        }

        .draggable[data-task="task1"] {
            margin-top: 3px !important;
        }

        .draggable span{
            font-size: 12px;
            display: block;
        }

        .calendar-day{
            width: 24px !important;
            height: 20px !important;
            text-align: center;
            cursor: grab;
        }

        :focus-visible {
            outline: none !important;
        }

        .second-input .calendar-day{
            height: 30px !important;
            font-size: 10px;
            padding: 0px !important;
        }

        .draggable[data-task="task1"] {
            margin-top: 3px !important;
        }

        .second-input{
            display: flex;
            cursor: grab;
        }

        .draggable:first-of-type{
            top: 0 !important;
            margin-top: 0px !important;
        }
        .calendar-container, .gantt-bar-container {
            display: flex;
            white-space: nowrap;
            position: relative;
        }
        .month-container {
            display: inline-block;
        }
        .month-header {
            display: block;
            width: 100%;
            margin: 0;
            padding: 5px 0;
            font-size: 14px;
            background-color: #000 !important;
            border-right: 1px solid #fff;
            color: white;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            /* Remove any margin-bottom or padding-bottom if present */
        }
        .calendar-day {
            display: inline-block;
            vertical-align: top;
            border: 1px solid #ccc;
            padding-top: 1px;
            font-size: 10px;
            box-sizing: border-box;
            margin: 0; /* Remove any margin that could cause a gap */
            border-top: unset;
        }
        .gantt-bar-container {
            position: relative;
        }
        .scroll-container {
            overflow-x: scroll;
            width: 100%;
            cursor: grab;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            scrollbar-width: none; /* For Firefox */
            -ms-overflow-style: none; /* For Internet Explorer and Edge */
        }

        .scroll-container::-webkit-scrollbar {
            display: none; /* For Chrome, Safari, and Opera */
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            background-color: #4a5568;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .content {
            margin-top: 50px; /* Adjust based on header height */
            display: flex;
        }
        .task-list {
            width: 600px;
            background-color: #f7fafc;
            border-right: 1px solid #ccc;
            border-radius: 4px;
        }
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-weight: bold;
            background-color: #000;
            color: white;
            padding: 10px;
            height: 52px;
        }
        .task-item {
            padding: 10px;
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 0px;
            border-bottom: 1px solid #eee;
            margin-left: 0px;
            background: #fff;
            height: 30px;
            padding-right: 0px;
        }

        .task-item:last-child{
            border-bottom-left-radius: 4px;
        }

        .task-item img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .holiday{
            background: #eee;
        }

        /* Add styles for resizable handles */
        .ui-resizable-handle {
            background: #fff;
            border: 1px solid #fff;
            z-index: 90;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20%;
        }
        .ui-resizable-e {
            right: 3px;
            float: right;
            width: 2px;
            height: 67%;
            cursor: e-resize;
            position: absolute;
            top: 4px;
            opacity: 1 !important;
            transition: opacity 0.2s;
        }
        .ui-resizable-w {
            left: 3px;
            width: 2px;
            height: 67%;
            cursor: e-resize;
            position: absolute;
            top: 4px;
            opacity: 1 !important;
            transition: opacity 0.2s;
            /* display: none; */
        }

        .ui-resizable-w:hover{
            display: block !important;
            opacity: 1 !important;
        },

        .ui-resizable-handle::before {
            content: ''; /* FontAwesome arrow icon */
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: #fff;
        }
        .ui-resizable-w::before {
            transform: rotate(180deg);
        }

        .ui-draggable-handle{
            background-color: #fff;
            padding-bottom: 0px;
            padding-top: 0px;
            border-radius: 5px;
        }

        .ui-draggable-handle-after{
            content: '';
            width: 100%;
            height: 5px;
            background-color: #fff;
            color: #fff;
            display: block;
        }

        .today-line {
            position: absolute;
            top: 47px;
            bottom: 0;
            width: 2px;
            background-color: #D9534F; /* Red color for the today line */
            z-index: 100; /* Ensure it appears above other elements */
        }

        .today-line::before {
            content: '';
            position: absolute;
            top: -4px;
            left: -2px;
            width: 6px;
            height: 6px;
            background-color: #37352f;
            border-radius: 10px;
        }

        /* Highlight holidays in the Gantt chart */
        .holiday-highlight {
            position: absolute;
            top: 0;
            bottom: 0;
            background-color: #f7f7f7; /* Light red background for holidays */
            z-index: -1; /* Ensure it doesn't overlap the Gantt bars */
        }

        progress{
            background: #dadada;
        }

        #addTaskButton{
            cursor: pointer;
        }

        .archied{
            display: none;
        }

        /*circle-progess*/

        .circle-progess .progress-container {
            position: relative;
            width: 100%; /* take full width of parent */
            max-width: 100px; /* optional: limit max size */
            aspect-ratio: 1/1; /* maintain circle (1:1) */
            margin: 20px;
            margin-top: 0px;
            margin-left: 0px;
            }

        .circle-progess .progress-ring {
        transform: rotate(-90deg);
        width: 100%;
        height: 100%;
        }

        .circle-progess .progress-text {
            font-size: 2.2vw; /* responsive font size */
            font-weight: bold;
            color: #333;
            line-height: 1;
        }

        .circle-progess .custom-label {
            font-size: 0.4vw; /* responsive font size */
            /* margin-top: 5px; */
            color: #555;
        }

        .circle-progess svg {
            width: 100%;
            height: 100%;
        }

        .sss .relative.mt-4 {
            margin-top: 0px !important;
        }

        .modal input{
            border:1px solid #e5e7eb;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        .circle {
            margin: 1rem;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            align-content: center;
            /* padding: 0.6rem; */
            margin-top: 0px;
            color: #fff;
            text-align: center;
        }

        .content{
            margin-top: 0px !important;
        }

        .mains{
            border: 1px solid #D1D5DB;
            border-radius: 4px;
        }
    </style>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>

    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Date-fns -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/date-fns/2.30.0/date-fns.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>
</head>
<body class="bg-gray-50" x-data="{
    showAddUserModal: false
}">
    @include('front.nav')
    <div class="mx-auto p-4 shadow rounded-lg border" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
        <div class="content" style="display: block; margin-bottom: 40px;">
            <div style="float: left; margin-top: 6px;">
                <h5 style="font-size: 20px; font-weight: 600; margin-left: 7px;">Team Members</h5>
            </div>
            <div class="flex items-center " style="float: right;">
                        <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px;">
                            <img src="{{ asset('house.png') }}" style="border: 1px solid #000;padding: 0.6rem 0.8rem;border-radius: 4px;border-color: #eee;">
                        </button>
                        {{-- <div style="border: 1px solid #eee; border-radius: 8px; padding: 5px 3px; margin-right: 8px;">
                            <a href="/projects" class="toggle-btn active">Daily</a>
                            <a href="/projects/weekly" class="toggle-btn">Weekly</a>
                        </div> --}}
                        <style>
                            .toggle-btn {
                                background: #000;
                                color: #fff;
                                border: 2px solid #fff;
                                border-radius: 8px;
                                padding: 4px 18px;
                                font-size: 15px;
                                font-weight: 500;
                                margin-right: 0;
                                transition: background 0.2s, color 0.2s;
                            }
                            .toggle-btn:not(.active) {
                                background: transparent;
                                color: #000;
                            }
                            .toggle-btn.active {
                                background: #000;
                                color: #fff;
                                border: 2px solid #fff;
                            }
                            .toggle-btn:focus {
                                outline: none;
                            }
                        </style>
                        <script>
                            // Toggle active class on click
                            $('#toggleDaily, #toggleWeekly').on('click', function() {
                                $('.toggle-btn').removeClass('active');
                                $(this).addClass('active');
                                // Add your view switching logic here if needed
                            });
                        </script>

                        <a href="#" class="bg-black text-white px-4 py-2 rounded" style="font-size: 13px; padding:0.4rem 1rem;" @click="showAddUserModal = true">+  Add Member</a>
            </div>
        </div>
        <div class="content" style="margin-top: 10px !important; border: 1px solid #D1D5DB; border-radius: 4px;">
                <div class="task-list" style="border-right: 0px; padding-right: 0px; padding-top: 0px">
                    <div class="task-header" style="margin-bottom: 0px; padding: 10px; padding-right: 0px;">
                        <span style="width: 50%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;" id="sortProject">
                            Team Member
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortProjectIcon" alt="">
                        </span>
                        <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Cost</span>
                        <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Hours</span>
                        {{-- <span style="font-size: 12px; width: 10%; padding-top: 17px; padding-bottom: 17px; text-align: center; border-right: 1px solid #eee;"> <i class="fas fa-eye show-user" data-type="show"></i> </span> --}}
                        {{-- <span class="text-center font-size: 12px; add-task" style="width: 10%;" id="addMemberButton"><i class="fas fa-plus"></i></span> --}}
                    </div>
                    <input type="hidden" id="task_count" value="{{ count($data) }}">
                    <div class="not-archived">
                        @foreach ($data as $item)
                            @if ($item->archieve == 0)
                                <div class="task-item data-id-{{ $item->id }}" data-task="task{{ $item->task_id }}" style="position: unset">
                                    <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px;">
                                        <img src="{{ $item->profile_image_url ? asset('storage/'.$item->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}">
                                        {{ $item->name }}
                                    </span>
                                        @php
                                            $es = DB::table('estimated_time_entries')->where('user_id',$item->id)->sum('hours');
                                        @endphp
                                    <span style="width: 25%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-cost-{{ $item->id }}">{{ number_format($item->hourly_rate*$es) }}</span>
                                    <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-hour-{{ $item->id }}">
                                        {{ number_format($es) }}
                                    </span>
                                    {{-- @if ($item->archieve == 0)
                                    <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye-slash hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                    @else
                                    <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                    @endif --}}
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="archied">
                        @foreach ($data as $item)
                            <div class="task-item data-id-{{ $item->id }}" data-task="task{{ $item->task_id }}" style="position: unset">
                                <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px;"><img src="{{ $item->profile_image_url ? asset('storage/'.$item->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}"> {{ $item->name }}</span>
                                    @php
                                        $es = DB::table('estimated_time_entries')->where('user_id',$item->id)->sum('hours');
                                    @endphp
                                <span style="width: 25%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-cost-{{ $item->id }}">{{ $item->hourly_rate*$es }}</span>
                                <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-hour-{{ $item->id }}">
                                    {{ $es }}
                                </span>
                                {{-- @if ($item->archieve == 0)
                                <span {{ $item->archieve }} style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye-slash hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                @else
                                <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                @endif --}}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="scroll-container sss">
                    <div class="relative mt-4">
                        <div class="calendar-container second-calender">
                            <!-- JavaScript will populate the months and dates here -->
                        </div>
                        <div class="not-archived" style="margin-top: -4px">
                            @foreach ($data as $item)
                            @if ($item->archieve == 0)
                                <div class="second-input data-id-{{ $item->id }}" data-task-id="{{ $item->task_id }}" data-user-id="{{ $item->id }}"></div>
                            @endif
                            @endforeach
                        </div>
                        <div class="archied" style="margin-top: -4px">
                            @foreach ($data as $item)
                                <div class="second-input data-id-{{ $item->id }}" data-task-id="{{ $item->task_id }}" data-user-id="{{ $item->id }}"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

    <!-- Add User Modal -->
    <div x-show="showAddUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" style="display: none;" x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
            <h2 class="text-2xl font-bold mb-4">Add User</h2>
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="add-user-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="add-user-name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>
                <div class="mb-4">
                    <label for="add-user-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="add-user-email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>
                <div class="mb-4">
                    <label for="add-user-role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="add-user-role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                        <option value="admin">Admin</option>
                        <option value="project_manager">Project Manager</option>
                        <option value="manager">Manager</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="add-user-hourly-rate" class="block text-sm font-medium text-gray-700">Hourly Rate</label>
                    <input type="number" id="add-user-hourly-rate" name="hourly_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>
                <div x-data="{
                        imageUrl: '',
                        cropper: null,
                        showCropper: false,
                        croppedBlob: null,
                        handleFileChange(event) {
                            const file = event.target.files[0];
                            if (file && file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = e => {
                                    this.imageUrl = e.target.result;
                                    this.showCropper = true;
                                    this.$nextTick(() => {
                                        const image = this.$refs.cropperImage;
                                        this.cropper = new Cropper(image, {
                                            aspectRatio: 1,
                                            viewMode: 1,
                                        });
                                    });
                                };
                                reader.readAsDataURL(file);
                            }
                        },
                        cropImage() {
                            if (this.cropper) {
                                this.cropper.getCroppedCanvas().toBlob(blob => {
                                    this.croppedBlob = blob;
                                    // Show preview
                                    this.imageUrl = URL.createObjectURL(blob);
                                    this.showCropper = false;
                                    // Set blob to hidden input for form submit
                                    const fileInput = document.getElementById('add-user-profile-picture-cropped');
                                    const dt = new DataTransfer();
                                    dt.items.add(new File([blob], 'profile_picture.png', {type: 'image/png'}));
                                    fileInput.files = dt.files;
                                });
                            }
                        }
                    }" class="mb-4">
                    <label for="add-user-profile-picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                    <input type="file" id="add-user-profile-picture" name="profile_picture_raw" accept="image/*"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                        @change="handleFileChange">
                    <!-- Hidden input for cropped image -->
                    <input type="file" id="add-user-profile-picture-cropped" name="profile_picture" style="display:none;">
                    <!-- Preview -->
                    <template x-if="imageUrl && !showCropper">
                        <img :src="imageUrl" alt="Preview" class="mt-2 w-24 h-24 rounded-full object-cover border">
                    </template>
                    <!-- Cropper Modal -->
                    <div x-show="showCropper" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                        <div class="bg-white p-4 rounded shadow-lg">
                            <img :src="imageUrl" x-ref="cropperImage" class="max-w-xs max-h-80">
                            <div class="mt-4 flex justify-end gap-2">
                                <button type="button" class="bg-gray-300 px-3 py-1 rounded" @click="showCropper=false; cropper.destroy();">Cancel</button>
                                <button type="button" class="bg-black text-white px-3 py-1 rounded" @click="cropImage">Crop & Use</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showAddUserModal = false" class="bg-gray-300 text-black px-4 py-2 rounded-md mr-2">Cancel</button>
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded-md">Add</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        $(function () {
            const calendarContainer = $('.calendar-container');
            const ganttBarContainer = $('.gantt-bar-container');
            const scrollContainer = $('.scroll-container');
            const st = $('#st_date').val();
            const en = $('#en_date').val();
            const startDate = new Date('2025-01-01');
            const endDate = new Date('2026-01-01');
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let isWeeklyView = false; // Default to daily view

            function renderCalendar() {
                calendarContainer.empty(); // Clear the calendar container
                let currentMonth = startDate.getMonth(); // Get the starting month index (0–11)
                let monthContainer = $('<div class="month-container"></div>');
                monthContainer.append(`<div class="month-header">${monthNames[currentMonth]}</div>`); // Use monthNames[currentMonth]

                inp = ``;

                // Base color for the fade effect
                const baseColor = [74, 85, 104]; // RGB for #4a5568 (dark gray-blue)
                const fadeStep = 2; // Amount to lighten the color for each month

                let fadeIndex = 0; // Initialize fade index

                for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                    const day = d.getDate().toString().padStart(2, '0'); // Pad day with leading zero
                    const month = (d.getMonth() + 1).toString().padStart(2, '0'); // Pad month with leading zero
                    const year = d.getFullYear();
                    const dayOfWeek = d.getDay();
                    const dateString = `${day}`;
                    const dayClass = (dayOfWeek === 0 || dayOfWeek === 6) ? 'calendar-day holiday' : 'calendar-day';

                    if (d.getMonth() !== currentMonth) {
                        // Apply fading color to the current month header
                        const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                            ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                            ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                        monthContainer.find('.month-header').css('background-color', fadeColor);

                        calendarContainer.append(monthContainer);
                        currentMonth = d.getMonth(); // Update currentMonth to the new month
                        monthContainer = $('<div class="month-container"></div>');
                        monthContainer.append(`<div class="month-header">${monthNames[currentMonth]}</div>`); // Use monthNames[currentMonth]

                        fadeIndex++; // Increment fade index for the next month
                    }

                    monthContainer.append(`<div class="calendar-day ${dayClass}" data-date="${year}-${month}-${day}">${dateString}</div>`);

                    inn = `<input readonly type="text" class="${dayClass} calendar-day inputss" onchange="convertTimeInput(this)" data-date="${year}-${month}-${day}">`;

                    inp += inn;
                }

                // Apply fading color to the last month header
                const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                    ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                    ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                monthContainer.find('.month-header').css('background-color', fadeColor);

                calendarContainer.append(monthContainer);

                $('.second-input').append(inp);
            }

            // Initial render
            renderCalendar();
            // alignGanttBars();
            // $(window).resize(alignGanttBars);
        });
    </script>

    {{-- <script src="{{asset('js/projects.js')}}"></script> --}}
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Settings dropdown functionality
        function toggleSettings(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('settingsDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('settingsDropdown');
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    </script>


<script>
    $(document).ready(function () {
        const addTaskButton = $('#addTaskButton');
        const addTaskModal = $('#addTaskModal');
        const cancelButton = $('#cancelButton');
        const saveTaskButton = $('#saveTaskButton');
        const taskList = $('.task-list');

        const addMemberButton = $('#addMemberButton');
        const addMemberModal = $('#addMemberModal');
        const cancelButtonMember = $('#cancelButtonMember');

        // Open the modal
        addTaskButton.on('click', function () {
            addTaskModal.fadeIn();
        });

        // Close the modal
        cancelButton.on('click', function () {
            addTaskModal.fadeOut();
        });

        // Open the modal
        addMemberButton.on('click', function () {
            addMemberModal.fadeIn();
        });

        // Close the modal
        cancelButtonMember.on('click', function () {
            addMemberModal.fadeOut();

            return;
        });


        $('.calendar-day').each(function () {
            const $this = $(this);

            $this.attr('data-task-id', $(this).parent().data('task-id'));

            $this.attr('data-user-id', $(this).parent().data('user-id'));
        });
    });
</script>


<script>
$(document).ready(function () {
    // Enable drag-to-scroll for .calendar-container.second-calender and .second-input
    $('.scroll-container').each(function () {
        const scrollContainer = this;
        let isDragging = false;
        let startX;
        let scrollLeft;

        // Helper function to start drag
        function startDrag(e) {
            if (e.button !== 0) return; // Only left mouse button
            isDragging = true;
            startX = e.pageX;
            scrollLeft = scrollContainer.scrollLeft;
            scrollContainer.style.cursor = 'grabbing';
        }

        // Helper function to handle drag
        function duringDrag(e) {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX;
            const walk = x - startX;
            scrollContainer.scrollLeft = scrollLeft - walk;
        }

        // Helper function to end drag
        function endDrag() {
            isDragging = false;
            scrollContainer.style.cursor = 'grab';
        }

        // Attach events to both .calendar-container.second-calender and .second-input inside this scroll-container
        $(scrollContainer).find('.calendar-container.second-calender, .second-input, inputss').on('mousedown', startDrag);
        $(scrollContainer).on('mousemove', duringDrag);
        $(scrollContainer).on('mouseup mouseleave', endDrag);
    });
});
</script>

<script>
    // Function to convert decimal time to hours and minutes
    function convertToHoursAndMinutes(decimalTime) {
        const hours = Math.floor(decimalTime); // Get the whole number part as hours
        const minutes = Math.round((decimalTime - hours) * 60); // Get the fractional part as minutes
        return `${hours} hours ${minutes} minutes`;
    }

    // Function to convert decimal time input
        async function convertTimeInput(inputElement) {
            const decimalTime = parseFloat(inputElement.value); // Get the input value as a float

            const date = inputElement.getAttribute('data-date'); // Get the date from the data attribute
            const user_id = inputElement.getAttribute('data-user-id'); // Get the task ID from the data attribute
            const data = inputElement.value; // Get the value from the input element
            const project_id = $('#project_id').val(); // Get the value from the input element

            if (isNaN(decimalTime) || decimalTime == 0) {
                inputElement.value = '';

                var project = $('#project_id').val();

                const data = 0;


                try {
                    // Send the data to the server
                    const response = await fetch('/estimated-time-tracking/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                        },
                        body: JSON.stringify({ user_id, date, data, project_id }),
                    });

                    if (response.ok) {
                        console.log('Data saved successfully.');

                        const responseData = await response.json(); // parse the JSON

                        $('.user-hour-'+user_id).html(responseData.data.total);

                        $('.user-cost-'+user_id).html(responseData.data.cost);

                        $('#fetch').load('/projects/reload-data/' + project_id, function() {
                            setTimeout(() => {
                                initProgressRings();
                            }, 10);
                        });
                    } else {
                        console.error('Failed to save data:', response.statusText);
                    }
                } catch (error) {
                    console.error('Error saving data:', error);
                }

                return;
            }

            if (decimalTime < 1 || decimalTime > 8) {
                alert('Value is Invalid.');
                inputElement.value = ''; // Clear the input field
                return; // Exit the function
            }

            if (!isNaN(decimalTime)) {
                const hours = Math.floor(decimalTime); // Get the whole number part as hours
                const minutes = Math.round((decimalTime - hours) * 60); // Get the fractional part as minutes
                inputElement.value = `${hours}:${minutes}`; // Display the converted time in the input field
            } else {
                inputElement.value = ''; // Clear the display if the input is invalid
            }



            try {
                // Send the data to the server
                const response = await fetch('/estimated-time-tracking/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                    },
                    body: JSON.stringify({ user_id, date, data, project_id }),
                });

                if (response.ok) {
                    console.log('Data saved successfully.');

                    const responseData = await response.json(); // parse the JSON

                    $('.user-hour-'+user_id).html(responseData.data.total);

                    $('.user-cost-'+user_id).html(responseData.data.cost);

                    $('#fetch').load('/projects/reload-data/' + project_id, function() {
                            setTimeout(() => {
                                initProgressRings();
                            }, 10);
                        });
                } else {
                    console.error('Failed to save data:', response.statusText);
                }
            } catch (error) {
                console.error('Error saving data:', error);
            }
        }

    document.addEventListener('DOMContentLoaded', function () {
        // Any additional DOMContentLoaded logic can go here
    });
</script>

<script>
    async function populateTimeTrackingData() {
        try {
            // Fetch the saved data from the server
            const response = await fetch('/estimated-time-tracking/get', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                },
            });

            if (!response.ok) {
                console.error('Failed to fetch time tracking data:', response.statusText);
                return;
            }

            const data = await response.json(); // Parse the JSON response


            // Iterate over the data and populate the input fields
            data.forEach(item => {
                const { task_id, user_id, date, time } = item;


                // Find the input field with the matching task_id and date
                const inputFields = document.querySelectorAll('.inputss');

                inputFields.forEach(inputField => {
                    const inputDate = inputField.getAttribute('data-date');
                    const userId = inputField.getAttribute('data-user-id');

                    if (inputDate === date && userId == user_id) {
                        if (time !== '0:00') {
                            inputField.value = time; // Populate the input field with the saved time
                        }
                    }
                });


                // if (inputField) {
                //     inputField.value = time; // Populate the input field with the saved time
                // }
            });
        } catch (error) {
            console.error('Error fetching time tracking data:', error);
        }
    }

    // Call the function after the DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        populateTimeTrackingData();
    });
</script>



<script>
    $('.show-user').on('click', function(){
        type = $(this).data('type');

        if (type == 'show') {
            $('.not-archived').hide();
            $('.archied').show();
            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
        } else {
            $('.not-archived').show();
            $('.archied').hide();
            $(this).removeClass('fa-eye-slash');
            $(this).addClass('fa-eye');
        }

        $(this).data('type', type == 'show' ? 'hide' : 'show');
    })
</script>

<script>
    // Function to create 1 progress ring
    // Function to create 1 progress ring
    function createProgressRing(container, value, customText) {
      container.innerHTML = '';

      const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      svg.setAttribute('viewBox', '0 0 250 250'); // Important for responsiveness
      svg.classList.add('progress-ring');


      const textWrapper = document.createElement('div');
      textWrapper.style.position = 'absolute';
      textWrapper.style.top = '50%';
      textWrapper.style.left = '50%';
      textWrapper.style.transform = 'translate(-50%, -50%)';
      textWrapper.style.textAlign = 'center';

      const percentText = document.createElement('div');
      percentText.classList.add('progress-text');
      percentText.textContent = null;

      const customLabel = document.createElement('div');
      customLabel.classList.add('custom-label');
      customLabel.textContent = customText || '';

      textWrapper.appendChild(percentText);
      textWrapper.appendChild(customLabel);

      container.appendChild(svg);
      container.appendChild(textWrapper);

      const fullCircles = Math.floor(value / 100);
      const remainingPercent = value % 100;
      const baseRadius = 70;
      const gap = 12;
      const strokeWidth = 10;

      let animationQueue = [];
      const normalColors = ['#4a5568'];
      const overLimitColor = '#e74c3c'; // Red if over 100%

      const isOverLimit = value > 100;

      for (let i = 0; i < fullCircles; i++) {
        animationQueue.push({
          radius: baseRadius - i * gap,
          percent: 100,
          color: isOverLimit ? overLimitColor : normalColors[i % normalColors.length],
          strokeWidth: strokeWidth,
        });
      }

      if (remainingPercent > 0) {
        animationQueue.push({
          radius: baseRadius - fullCircles * gap,
          percent: remainingPercent,
          color: isOverLimit ? overLimitColor : normalColors[fullCircles % normalColors.length],
          strokeWidth: strokeWidth,
        });
      }

      animateNextRing(svg, percentText, animationQueue, 0);
    }

    // Animate one ring
    function animateNextRing(svg, percentText, queue, index) {
      if (index >= queue.length) return;

      const { radius, percent, color, strokeWidth } = queue[index];
      const circumference = 2 * Math.PI * radius;

      const circleBackground = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
      circleBackground.setAttribute('r', radius);
      circleBackground.setAttribute('cx', 125);
      circleBackground.setAttribute('cy', 125);
      circleBackground.setAttribute('fill', 'transparent');
      circleBackground.setAttribute('stroke', '#eee');
      circleBackground.setAttribute('stroke-width', strokeWidth);

      const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
      circle.setAttribute('r', radius);
      circle.setAttribute('cx', 125);
      circle.setAttribute('cy', 125);
      circle.setAttribute('fill', 'transparent');
      circle.setAttribute('stroke', color);
      circle.setAttribute('stroke-width', strokeWidth);
      circle.setAttribute('stroke-dasharray', `${circumference} ${circumference}`);
      circle.setAttribute('stroke-dashoffset', circumference);

      svg.appendChild(circleBackground);
      svg.appendChild(circle);

      let start = null;
      const duration = 800;

      function animate(time) {
        if (!start) start = time;
        const elapsed = time - start;
        const progress = Math.min(elapsed / duration, 1);
        const offset = circumference - (percent / 100) * circumference * progress;

        circle.setAttribute('stroke-dashoffset', offset);

        let completedPercent = 0;
        for (let j = 0; j < index; j++) {
          completedPercent += queue[j].percent;
        }
        const currentProgress = completedPercent + (percent * progress);
        // percentText.textContent = `${Math.floor(currentProgress)}%`;

        if (progress < 1) {
          requestAnimationFrame(animate);
        } else {
          animateNextRing(svg, percentText, queue, index + 1);
        }
      }

      requestAnimationFrame(animate);
    }

    // Main code: Read all .progress-container elements
    document.addEventListener('DOMContentLoaded', function() {
        initProgressRings();
    });

</script>

<script>
    $(function() {
      $('input[name="date"]').daterangepicker({
        opens: 'left'
      }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
      });
    });
    </script>

    <script>
        function initProgressRings() {
            const containers = document.querySelectorAll('.progress-container');

            containers.forEach(container => {
                const value = parseFloat(container.getAttribute('data-value'));
                const customText = container.getAttribute('data-text') || '';
                if (!isNaN(value)) {
                    createProgressRing(container, value, customText);
                }
            });
        }
    </script>

    <script>
        function highlightToday() {
                const today = new Date();

                const startDate = new Date('2025-01-01'); // Adjust this to your Gantt chart's start date
                // startDate.setDate(startDate.getDate() - 30);
                const dayWidth = $(".calendar-day").outerWidth();
                const coun = $('#task_count').val();

                // Calculate the number of days from the start date to today
                const daysFromStart = Math.floor((today - startDate) / (1000 * 60 * 60 * 24));

                // Calculate the left position for the today line
                const todayPosition = daysFromStart * dayWidth;


                // Add the today line to the Gantt chart
                const todayLine = $('<div class="today-line"></div>');
                todayLine.css({
                    left: todayPosition + 'px',
                    height: coun * 30 + 5 + 'px'
                });

                $('.not-archived').append(todayLine);
            }

            // Call the function after the DOM is ready
            $(document).ready(function () {
                highlightToday();
            });
    </script>

<script>
    $(document).ready(function() {
    $('#home').on('click', function() {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;

    // Find the calendar-day for today
    const $todayCell = $(`.calendar-day[data-date="${todayStr}"]`);
    if ($todayCell.length) {
        const scrollContainer = $('.scroll-container');
        const cellLeft = $todayCell.position().left;
        scrollContainer.animate({
            scrollLeft: cellLeft - scrollContainer.width()/2 + $todayCell.outerWidth()/2
        }, 400);
    }
});
});
</script>


<script>
$(document).ready(function() {
    let asc = true;
    $('#sortProject').on('click', function() {
        let items = $('.task-list .task-item').get();
        items.sort(function(a, b) {
            let keyA = $(a).find('span').first().text().trim().toLowerCase();
            let keyB = $(b).find('span').first().text().trim().toLowerCase();
            if (asc) {
                return keyA.localeCompare(keyB);
            } else {
                return keyB.localeCompare(keyA);
            }
        });
        $.each(items, function(i, item) {
            $('.task-list').append(item);
        });
        asc = !asc;
        $('#sortProjectIcon').toggleClass('fa-sort-alpha-down fa-sort-alpha-up');
    });
});
</script>


</body>
</html>
