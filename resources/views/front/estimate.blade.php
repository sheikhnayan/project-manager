<style>
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

    .heading h6{
        font-size: 14px;
        margin-bottom: 0px;
    }

    .end .heading h6{
        margin-bottom: 1rem;
        margin-top: 0px;
    }
</style>

<div id="editTaskModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Add Task</div>
        <div class="modal-body">
            <form method="POST" action="{{ route('projects.task.update',[$data->id]) }}" id="addTaskForm">
                @csrf
                <input type="hidden" id="editTaskId" name="task_id" value="">
                <div class="mb-4">
                    <label for="taskName" class="block text-sm font-medium text-gray-700">Task Name</label>
                    <input type="text" id="taskNameedit" value="" name="name" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" required>
                </div>
                <div class="mb-4">
                    <label for="startDate" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="text" id="startDateedit" name="date" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" required>
                </div>
                <div class="mb-4">
                    <label for="budget_total" class="block text-sm font-medium text-gray-700">Budget</label>
                    <input type="number" id="budget_totaledit" name="budget_total" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" value="0" required>
                </div>
            </div>
            <div class="modal-footer">
                <button id="cancelButton" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                <button id="saveTaskButton" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="confirmationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">Confirm Action</div>
        <div class="modal-body">
            <p>Are you sure you want to do this action?</p>
        </div>
        <div class="modal-footer">
            <button id="cancelModalButton" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
            <button id="confirmModalButton" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Confirm</button>
        </div>
    </div>
</div>

<div class="content grid grid-cols-4 gap-4">
    <div style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15); padding-top: 0px; border-radius: 8px; margin-right: 0px; padding: 12px; padding-bottom: 20px;">
        <div class="p-4" style="padding-top: 0px; padding-bottom: 0px; margin-top: -40px;">
            <h6 style="float: left; font-size: 20px; font-weight: bold;;">Estimated Project <br /> Costing</h6>
            {{-- <p>Includes all billable and non-billable works</p> --}}
            <div style="float: right; display: flex">
                @php
                    $b = $data->sum('budget_total') < 1 ? 1 : $data->sum('budget_total');
                    $spent = 0;

                    foreach ($data->estimatedtimeEntries as  $value) {
                        # code...
                        $rate = $value->user->hourly_rate;


                        $spent += $rate*$value->hours;
                    }
                    $p = $b - $spent;
                    $pe = ($p / $b)*100;
                @endphp
                <span class="circle"
                @if($pe <  $data->expected_profit)
                style="background: red; font-size: 13px; margin-right: 0px;"
                @else
                style="background: green; font-size: 13px; margin-right: 0px;"
                @endif
                >{{ round($pe) }}%</span>
                <span class="circle" style="background: orange; font-size: 13px; margin-right: 0px;">{{ $data->expected_profit }}%</span>
            </div>
            <div class="form-group" style="margin-top: 3rem; font-size: 12px;">
                <div>
                    <div class="heading">
                        <h6 class="mt-4 mb-3" style="float: left; width: 63%">All Phase</h6>
                        <h6 class="mt-4 mb-3" style="float: right;">
                            @php
                            // $time = DB::table('time_entries')->where('project_id',$data->id)->get();

                            $spent = 0;

                            foreach ($data->estimatedtimeEntries as  $value) {
                                # code...
                                $rate = $value->user->hourly_rate;


                                $spent += $rate*$value->hours;
                            }

                        @endphp
                            €{{ number_format($spent) }} OF €{{ number_format($data->budget_total) }}</h6>
                    </div>
                    <div class="progress-bar__wrapper">
                        @php
                            $budget = $data->budget_total < 1 ? 1 : $data->budget_total;
                            $percentage = ($spent / $budget) * 100;
                        @endphp

                        @if ($percentage > 100)

                        <style>
                            .progresss-{{ $data->id }}::-webkit-progress-value {
                                background-color: red; /* Color of the progress value */
                                border-radius: 10px;
                            }

                            .progresss-{{ $data->id }}::-moz-progress-bar {
                                background-color: red; /* Color of the progress value for Firefox */
                            }
                        </style>
                        @else

                        <style>
                            .progresss-{{ $data->id }}::-webkit-progress-value {
                                background-color: #4a5568; /* Color of the progress value */
                                border-radius: 10px;
                            }

                            .progresss-{{ $data->id }}::-moz-progress-bar {
                                background-color: #4a5568; /* Color of the progress value for Firefox */
                            }
                        </style>
                        @endif

                        <progress class="rounded-full h-2.5 progress-{{ $data->id }}" id="progress-bar" style="accent-color:#27e3cb; width:100%" value="{{ $percentage }}" max="100"></progress>
                    </div>
                    <div class="end">
                        <div class="heading" style="width: 175px;">
                            <h6 class="mt-4 mb-3" style="float: left">{{ \Carbon\Carbon::parse($data->start_date)->format('M d,Y')}}</h6>
                            <h6 class="mt-4 mb-3" style="float: right;"> - {{ \Carbon\Carbon::parse($data->end_date)->format('M d,Y') }}</h6>
                        </div>
                    </div>
                </div>

                @foreach ($data->tasks as $key => $p)
                <div class="heading"
                @if ($key == 0)
                    style="margin-top: 40px;"
                @endif
                >
                    <h6 class="mt-4 mb-3" style="float: left">{{ $p->name }}</h6>
                    @php
                        // $time = DB::table('time_entries')->where('project_id',$data->id)->where('task_id',$p->id)->get();

                        $spent = 0;

                        foreach ($data->estimatedtimeEntries as  $value) {
                            # code...
                            // $rate = DB::table('teams')->where('id',$value->team_id)->first();
                            if ($value->task_id == $p->id) {
                                # code...
                                $rate = $value->user->hourly_rate;


                                $spent += $rate*$value->hours;
                            }
                        }

                    @endphp
                    <h6 class="mt-4 mb-3" style="float: right;"> €{{ number_format($spent) }} OF €{{ number_format($p->budget_total) }}</h6>
                </div>
                <div class="progress-bar__wrapper">
                    @php
                    // dd($spent);
                        $budget = $p->budget_total < 1 ? 1 : $p->budget_total;
                        $percentage = ($spent / $budget) * 100;
                    @endphp
                    @if ($percentage > 100)

                    <style>
                        .progress-{{ $key }}::-webkit-progress-value {
                            background-color: red; /* Color of the progress value */
                            border-radius: 10px;
                        }

                        .progress-{{ $key }}::-moz-progress-bar {
                            background-color: red; /* Color of the progress value for Firefox */
                        }
                    </style>
                    @else

                    <style>
                        .progress-{{ $key }}::-webkit-progress-value {
                            background-color: #4a5568; /* Color of the progress value */
                            border-radius: 10px;
                        }

                        .progress-{{ $key }}::-moz-progress-bar {
                            background-color: #4a5568; /* Color of the progress value for Firefox */
                        }
                    </style>
                    @endif
                    <progress class="rounded-full h-2.5 progress-{{ $key }}" id="progress-bar" style="accent-color:#27e3cb; width:100%" value="{{ $percentage }}" max="100"></progress>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-span-3 grid grid-cols-subgrid gap-4">
        <div class="shadow-md rounded-lg p-4 col-start-1 col-end-5" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15); padding-top: 0px; border-radius: 8px; margin-right: 18px; padding: 12px; padding-bottom: 20px;">
            <div class="end">
                <h6 style="font-size: 20px; font-weight: bold; padding-left: 9px; padding-top: 8px; padding-bottom: 15px;">Executed Project Details</h6>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($data->tasks as $i => $item)
                    <div class="grid grid-cols-4 gap-4 shadow-md rounded-lg col-md-6 p-4" style="border: 1px solid #D1D5DB; border-radius: 8px; padding-top: 10px; padding-bottom:10px; height: 82px;">
                        <div class="col-span-3" style="font-size: 14px;">
                            <div class="pro">
                                <div class="heading">
                                    @php

                                    $spentt = 0;

                                        foreach ($data->timeEntries as  $value) {
                                            # code...
                                            // $tm = DB::table('teams')->where('id',$i)->first();
                                            if ($value->task_id == $item->id) {
                                                # code...
                                                $rate = $value->user->hourly_rate;


                                                $spentt += $rate*$value->hours;
                                            }

                                        }

                                    @endphp
                                    <h6 class="mt-4 mb-3" style="float: left; font-size: 14px; margin-bottom: 0px;">{{ $item->name }}</h6>
                                    <h6 class="mt-4 mb-3" style="float: right; margin-bottom: 0px;"> €{{ number_format($spentt) }} OF €{{ number_format($item->budget_total) }}</h6>
                                </div>
                                <div class="progress-bar__wrapper">
                                    @php
                                        $budget = $item->budget_total < 1 ? 1 : $item->budget_total;
                                        $percentage = ($spentt / $budget) * 100;
                                    @endphp

                                    @if ($percentage > 100)

                                    <style>
                                        .progresss-{{ $i }}::-webkit-progress-value {
                                            background-color: red; /* Color of the progress value */
                                            border-radius: 10px;
                                        }

                                        .progresss-{{ $i }}::-moz-progress-bar {
                                            background-color: red; /* Color of the progress value for Firefox */
                                        }
                                    </style>
                                    @else

                                    <style>
                                        .progresss-{{ $i }}::-webkit-progress-value {
                                            background-color: #4a5568; /* Color of the progress value */
                                            border-radius: 10px;
                                        }

                                        .progresss-{{ $i }}::-moz-progress-bar {
                                            background-color: #4a5568; /* Color of the progress value for Firefox */
                                        }
                                    </style>
                                    @endif
                                    <progress class="rounded-full h-2.5 progresss-{{ $i }}" id="progress-bar" style="accent-color:#27e3cb; width:100%" value="{{ $percentage }}" max="100"></progress>
                                </div>
                                <div class="end">
                                    <div class="heading" style="width: 175px;">
                                        <h6 class="mt-4 mb-3" style="float: left">{{ \Carbon\Carbon::parse($data->start_date)->format('M d,Y')}}</h6>
                                        <h6 class="mt-4 mb-3" style="float: right;"> - {{ \Carbon\Carbon::parse($data->end_date)->format('M d,Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="circle-progess" style="margin-top: -20px; float: left;">
                                @php
                                    foreach ($data->estimatedtimeEntries as  $value) {
                                        # code...
                                        // $rate = DB::table('teams')->where('id',$value->team_id)->first();

                                        $spent = 0;

                                        if ($value->task_id == $item->id) {
                                            # code...
                                            $rate = $value->user->hourly_rate;


                                            $spent += $rate*$value->hours;
                                        }
                                    }
                                        $budget = $item->budget_total < 1 ? 1 : $item->budget_total;
                                        $percentage = ($spent / $budget) * 100;
                                @endphp
                                <div class="progress-container" data-value="{{ $percentage == 0 ? 1 : $percentage }}" data-text="Completed" style="margin-right: 0px;"></div>
                            </div>
                            @php
                                    $b = $item->budget_total < 1 ? 1 : $item->budget_total;
                                    $spentt = 0;

                                        foreach ($data->timeEntries as  $value) {
                                            # code...
                                            // $tm = DB::table('teams')->where('id',$i)->first();
                                            if ($value->task_id == $item->id) {
                                                # code...
                                                $rate = $value->user->hourly_rate;


                                                $spentt += $rate*$value->hours;
                                            }

                                        }
                                    $p = $b - $spentt;
                                    $pe = ($p / $b)*100;
                                @endphp
                                <span class="circle"
                                @if($pe <  $data->expected_profit)
                                style="background: red; margin-left: 1.4rem; display: block; font-size: 13px; margin-top: -96px; float: right;margin-right: 0px; margin-left: 0px;"
                                @else
                                style="background: green; margin-left: 1.4rem; display: block; font-size: 13px; margin-top: -96px; float: right;margin-right: 0px; margin-left: 0px;"
                                @endif
                                >{{ round($pe) }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- <p>Duration</p>
                <p>{{ \Carbon\Carbon::parse($data->start_date)->format('M d,Y') }} - {{ \Carbon\Carbon::parse($data->end_date)->format('M d,Y') }}</p> --}}
            </div>
            {{-- <div class="fl mt-4">
                <p style="float: left">Total project fees</p>
                <p style="float: right; font-weight: bold;">${{ $data->budget }}</p>
            </div> --}}
        </div>
    </div>
</div>

<!-- Add Task Modal -->
                <div id="addTaskModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">Add Task</div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('projects.task.store',[$data->id]) }}" id="addTaskForm">
                                @csrf
                                <div class="mb-4" style="position: relative;">
                                    <label for="taskName" class="block text-sm font-medium text-gray-700">Task Name</label>
                                    <input type="text" id="taskName" name="name" autocomplete="off" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" required>
                                    <ul id="taskNameSuggestions" class="absolute z-10 bg-white border border-gray-300 rounded-md mt-1 w-full shadow-lg hidden"></ul>
                                </div>
                                <div class="mb-4">
                                    <label for="startDate" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="text" id="startDate" name="date" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" required>
                                </div>
                                <div class="mb-4">
                                    <label for="budget_total" class="block text-sm font-medium text-gray-700">Budget</label>
                                    <input type="number" id="budget_total" name="budget_total" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" value="0" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="cancelButton" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                                <button id="saveTaskButton" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Task Modal -->
                <div id="addMemberModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">Add Member</div>
                        <div class="modal-body">
                            <form method="POST" action="/projects/member-store/{{ $data->id }}" id="addMemberForm">
                                @csrf
                                <div class="mb-4">
                                    <label for="taskName" class="block text-sm font-medium text-gray-700">Task Name</label>
                                    <select name="user_id" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" required>
                                        @php
                                            $members = $data->members()->select('user_id')->get()->pluck('user_id')->toArray(); // Extract 'id' values as an array
                                            $new = DB::table('users')->where('role', 'employee')->whereNotIn('id', $members)->get();
                                        @endphp
                                        @foreach ($new as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="cancelButtonMember" type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                                <button id="saveTaskButton" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                            </div>
                        </form>
                    </div>
                </div>


<script>
    $(document).ready(function () {
    let userIdToHide = null; // Store the user ID temporarily
    let $currentElement = null; // Store the current element temporarily

    // Open the modal when the hide-user button is clicked
    $('.hide-user').on('click', function () {
        userIdToHide = $(this).data('id'); // Get the user ID
        $currentElement = $(this); // Store the current element
        $('#confirmationModal').fadeIn(); // Show the modal
    });

    // Close the modal when the cancel button is clicked
    $('#cancelModalButton').on('click', function () {
        $('#confirmationModal').fadeOut(); // Hide the modal
        userIdToHide = null; // Reset the user ID
        $currentElement = null; // Reset the current element
    });

    // Confirm the action when the confirm button is clicked
    $('#confirmModalButton').on('click', function () {
        if (userIdToHide) {
            $.ajax({
                url: '/projects/hide-user',
                type: 'POST',
                data: {
                    id: userIdToHide,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        $currentElement.removeClass('fa-eye');
                        $currentElement.addClass('fa-eye-slash');
                        $(`.data-id-${userIdToHide}`).removeClass('not-archived');
                        $(`.data-id-${userIdToHide}`).addClass('archied');
                        window.location.reload();
                    }
                }
            });
        }
        $('#confirmationModal').fadeOut(); // Hide the modal
    });
});
</script>

<script>
    $('.budget_total').on('change', function(){
        var id = $(this).data('task-id');

        var value = $(this).val();

        $.ajax({
                url: '/projects/change-budget',
                type: 'POST',
                data: {
                    id: id,
                    value: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        window.location.reload();
                    }
                }
            });
    })
</script>

<script>
    $(document).ready(function () {
    $(document).on('click', '.inputss', function () {
        $(this).select(); // Select all text in the input field
    });
});
</script>

<script>
$(document).ready(function() {
    // Open modal and fill data
    $('.open-edit-task-modal').on('click', function() {
        console.log($(this).data('task-date'));
        // Fill form fields with task data
        $('#editTaskId').val($(this).data('task-id'));
        $('#taskNameedit').val($(this).data('task-name'));
        $('#startDateedit').val($(this).data('task-date'));
        $('#budget_totaledit').val($(this).data('task-budget'));
        // Change modal header for edit
        $('#editTaskModal .modal-header').text('Edit Task');
        // Show modal
        $('#editTaskModal').fadeIn();
    });

    // Cancel button closes modal and resets form
    $('#cancelButton').on('click', function(e) {
        e.preventDefault();
        $('#editTaskModal').fadeOut();
        $('#addTaskForm')[0].reset();
        $('#editTaskId').val('');
        $('#editTaskModal .modal-header').text('Add Task');

        return false;
    });

    // Optional: Close modal when clicking outside modal-content
    $('#editTaskModal').on('click', function(e) {
        if ($(e.target).is('#editTaskModal')) {
            $('#editTaskModal').fadeOut();
            $('#addTaskForm')[0].reset();
            $('#editTaskId').val('');
            $('#editTaskModal .modal-header').text('Add Task');
        }
    });
});
</script>

<script>
$(document).ready(function() {
    // List of suggested task names
    const taskNames = [
        '01_BD & Contracts',
        '02_Competition / Pitch design',
        '03_Concept Design',
        '04_Preliminary Design',
        '05_Planning Submission Stage',
        '06_Detail Design Stage',
        '07_Tender process',
        '08_Author supervision',
        '10_Extra Work'
    ];

    // Show suggestions on keyup
    $('#taskName').on('keyup focus', function() {
        const val = $(this).val().toLowerCase();
        const $suggestions = $('#taskNameSuggestions');
        $suggestions.empty();

        if (val.length === 0) {
            $suggestions.hide();
            return;
        }

        const matches = taskNames.filter(name => name.toLowerCase().includes(val));
        if (matches.length === 0) {
            $suggestions.hide();
            return;
        }

        matches.forEach(name => {
            $suggestions.append(`<li class="px-3 py-2 cursor-pointer hover:bg-gray-100">${name}</li>`);
        });
        $suggestions.show();
    });

    // Fill input when suggestion is clicked
    $('#taskNameSuggestions').on('click', 'li', function() {
        $('#taskName').val($(this).text());
        $('#taskNameSuggestions').hide();
    });

    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#taskName').length && !$(e.target).closest('#taskNameSuggestions').length) {
            $('#taskNameSuggestions').hide();
        }
    });
});
</script>
