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

    /* Progress bar styling for better cross-browser compatibility */
    progress.rounded-full {
        -webkit-appearance: none;
        appearance: none;
        border-radius: 9999px !important;
        height: 0.625rem;
        overflow: hidden;
    }

    /* WebKit browsers (Chrome, Safari, newer Edge) */
    progress.rounded-full::-webkit-progress-bar {
        background-color: #e5e7eb;
        border-radius: 9999px;
    }

    progress.rounded-full::-webkit-progress-value {
        border-radius: 9999px;
        transition: width 0.3s ease;
    }

    /* Firefox */
    progress.rounded-full::-moz-progress-bar {
        border-radius: 9999px;
    }

    /* Microsoft Edge Legacy support */
    progress.rounded-full::-ms-fill {
        border-radius: 9999px;
        border: none;
    }

    progress.rounded-full::-ms-progress-bar {
        background-color: #e5e7eb;
        border-radius: 9999px;
        border: none;
    }
</style>
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
                <button id="cancelEditTaskButton" type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                <button id="saveTaskButton" type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="confirmationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header" id="confirmationModalHeader">Confirm Action</div>
        <div class="modal-body">
            <p id="confirmationModalText">Are you sure you want to do this action?</p>
        </div>
        <div class="modal-footer">
            <button id="cancelModalButton" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
            <button id="confirmModalButton" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Confirm</button>
        </div>
    </div>
</div>

<div class="content grid grid-cols-5 gap-4" style="align-items: stretch;">
    <div style="height: 100%; display: flex; flex-direction: column;">
        <div style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15); padding-top: 0px; border-radius: 8px; margin-right: 0px; padding: 12px; padding-bottom: 20px; flex: 1;">
          <div class="p-4" style="padding-top: 1rem; padding-bottom: 0px;">
              <h6 style="font-size: 20px; font-weight: bold;">Planned Budget</h6>
              {{-- <p>Includes all billable and non-billable works</p> --}}
              <div style="display: flex">
                  @php
                      $totalBudget = $data->tasks->sum('budget_total');
                      $b = $totalBudget < 1 ? 1 : $totalBudget;
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
                  style="background: red; font-size: 13px; margin-right: 0px; margin: 0px; margin-top: 1rem;"
                  @else
                  style="background: green; font-size: 13px; margin-right: 0px; margin: 0px; margin-top: 1rem;"
                  @endif
                  >{{ round($pe) }}%</span>
                  <span class="circle" style="background: orange; font-size: 13px; margin-right: 0px; margin: 0px; margin-top: 1rem; margin-left: 1rem;">{{ $data->expected_profit }}%</span>
              </div>
              <div class="form-group" style="margin-top: 1rem; font-size: 12px;">
                  <div>
                      <div class="heading">
                          <h6 class="mt-4 mb-3" style="float: left; width: 50%">All Phase</h6>
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
                              {{ formatCurrency($spent) }} OF {{ formatCurrency($data->tasks->sum('budget_total')) }}</h6>
                      </div>
                      <div class="progress-bar__wrapper">
                          @php
                              $budget = $data->tasks->sum('budget_total') < 1 ? 1 : $data->tasks->sum('budget_total');
                              $percentage = ($spent / $budget) * 100;
                          @endphp
  
                          @if ($percentage > 100)
  
                          <style>
                              .progress-{{ $data->id }}::-webkit-progress-value {
                                  background-color: red !important; /* Color of the progress value */
                                  border-radius: 10px;
                              }
  
                              .progress-{{ $data->id }}::-moz-progress-bar {
                                  background-color: red !important; /* Color of the progress value for Firefox */
                              }
                          </style>
                          @else
  
                          <style>
                              .progress-{{ $data->id }}::-webkit-progress-value {
                                  background-color: #22c55e !important; /* Green color for the progress value */
                                  border-radius: 10px;
                              }
  
                              .progress-{{ $data->id }}::-moz-progress-bar {
                                  background-color: #22c55e !important; /* Green color for the progress value for Firefox */
                              }
                          </style>
                          @endif
  
                          <progress class="rounded-full h-2.5 progress-{{ $data->id }}" id="progress-bar" style="width:100%; @if($percentage > 100) accent-color: red; @else accent-color: #22c55e; @endif" value="{{ $percentage }}" max="100"></progress>
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
                      <h6 class="mt-4 mb-3" style="float: right;"> {{ formatCurrency($spent) }} OF {{ formatCurrency($p->budget_total) }}</h6>
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
                              background-color: #22c55e; /* Green color for the progress value */
                              border-radius: 10px;
                          }
  
                          .progress-{{ $key }}::-moz-progress-bar {
                              background-color: #22c55e; /* Green color for the progress value for Firefox */
                          }
                      </style>
                      @endif
                      <progress class="rounded-full h-2.5 progress-{{ $key }}" id="progress-bar" style="accent-color:#22c55e; width:100%" value="{{ $percentage }}" max="100"></progress>
                  </div>
                  @endforeach
              </div>
            </div>  
        </div>
    </div>
    <div style="height: 100%; display: flex; flex-direction: column;">
        <div style="border: 1px solid #D1D5DB; padding-top: 0; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15); padding-top: 0px; border-radius: 8px; margin-right: 0px; padding: 12px; padding-bottom: 20px; flex: 1;">
            <div class="p-4" style="padding-top: 1rem; padding-bottom: 0px;">
                <h6 style="font-size: 20px; font-weight: bold;;">Actual Spend</h6>
                {{-- <p>Includes all billable and non-billable works</p> --}}
                <div style="display: flex">
                    @php
                        $actualSpent = 0;
                        foreach ($data->timeEntries as  $value) {
                            $rate = $value->user->hourly_rate;
                            $actualSpent += $rate*$value->hours;
                        }
                        $totalBudget = $data->tasks->sum('budget_total');
                        $b = $totalBudget < 1 ? 1 : $totalBudget;
                        $p = $b - $actualSpent;
                        $pe = ($p / $b)*100;
                    @endphp
                    <span class="circle"
                    @if($pe <  $data->expected_profit)
                    style="background: red; font-size: 13px; margin-right: 0px; margin: 0px; margin-top: 1rem;"
                    @else
                    style="background: green; font-size: 13px; margin-right: 0px; margin: 0px; margin-top: 1rem;"
                    @endif
                    >{{ round($pe) }}%</span>
                    <span class="circle" style="background: orange; font-size: 13px; margin-right: 0px; margin: 0px; margin-top: 1rem; margin-left: 1rem;">{{ $data->expected_profit }}%</span>
                </div>
                <div class="form-group" style="margin-top: 1rem; font-size: 12px;">
                    <div>
                        <div class="heading">
                            <h6 class="mt-4 mb-3" style="float: left; width: 50%">All Phase</h6>
                            <h6 class="mt-4 mb-3" style="float: right;">
                                {{ formatCurrency($actualSpent) }} OF {{ formatCurrency($totalBudget) }}</h6>
                        </div>
                        <div class="progress-bar__wrapper">
                            @php
                                $budget = $totalBudget < 1 ? 1 : $totalBudget;
                                $percentage = ($actualSpent / $budget) * 100;
                            @endphp

                            @if ($percentage > 100)
                            <style>
                                .actual-progresss-{{ $data->id }}::-webkit-progress-value {
                                    background-color: red; /* Color of the progress value */
                                    border-radius: 10px;
                                }

                                .actual-progresss-{{ $data->id }}::-moz-progress-bar {
                                    background-color: red; /* Color of the progress value for Firefox */
                                }
                            </style>
                            @else
                            <style>
                                .actual-progresss-{{ $data->id }}::-webkit-progress-value {
                                    background-color: #22c55e; /* Green color for the progress value */
                                    border-radius: 10px;
                                }

                                .actual-progresss-{{ $data->id }}::-moz-progress-bar {
                                    background-color: #22c55e; /* Green color for the progress value for Firefox */
                                }
                            </style>
                            @endif

                            <progress class="rounded-full h-2.5 actual-progresss-{{ $data->id }}" id="actual-progress-bar" style="accent-color:#22c55e; width:100%" value="{{ $percentage }}" max="100"></progress>
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
                            $actualTaskSpent = 0;
                            foreach ($data->timeEntries as  $value) {
                                if ($value->task_id == $p->id) {
                                    $rate = $value->user->hourly_rate;
                                    $actualTaskSpent += $rate*$value->hours;
                                }
                            }
                        @endphp
                        <h6 class="mt-4 mb-3" style="float: right;"> {{ formatCurrency($actualTaskSpent) }} OF {{ formatCurrency($p->budget_total) }}</h6>
                    </div>
                    <div class="progress-bar__wrapper">
                        @php
                            $budget = $p->budget_total < 1 ? 1 : $p->budget_total;
                            $percentage = ($actualTaskSpent / $budget) * 100;
                        @endphp
                        @if ($percentage > 100)
                        <style>
                            .actual-progress-{{ $key }}::-webkit-progress-value {
                                background-color: red; /* Color of the progress value */
                                border-radius: 10px;
                            }

                            .actual-progress-{{ $key }}::-moz-progress-bar {
                                background-color: red; /* Color of the progress value for Firefox */
                            }
                        </style>
                        @else
                        <style>
                            .actual-progress-{{ $key }}::-webkit-progress-value {
                                background-color: #22c55e; /* Green color for the progress value */
                                border-radius: 10px;
                            }

                            .actual-progress-{{ $key }}::-moz-progress-bar {
                                background-color: #22c55e; /* Green color for the progress value for Firefox */
                            }
                        </style>
                        @endif
                        <progress class="rounded-full h-2.5 actual-progress-{{ $key }}" id="actual-progress-bar" style="accent-color:#22c55e; width:100%" value="{{ $percentage }}" max="100"></progress>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div style="height: 100%; display: flex; flex-direction: column;">
        <div style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15); padding-top: 0px; border-radius: 8px; margin-right: 0px; padding: 12px; padding-bottom: 20px; flex: 1;">
            <div class="p-4" style="padding-top: 0px; padding-bottom: 0px; padding-top: 1rem;">
                <h6 style="font-size: 20px; font-weight: bold;">Hours Logged</h6>
                <div style="display: flex; align-items: center; margin-top: 1rem;">
                    <select id="employeeSelector" style="padding: 8px 12px; border: 1px solid #D1D5DB; border-radius: 6px; background: white; font-size: 14px;">
                        <option value="all">All Employees</option>
                        @foreach ($data->members as $member)
                            <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-top: 2rem; font-size: 12px;">
                    <div>
                        <div class="heading">
                            <h6 class="mt-4 mb-3" style="float: left; width: 63%">All Phase</h6>
                            <h6 class="mt-4 mb-3" style="float: right;" id="employeeAllPhaseHours">
                                @php
                                    $totalHours = 0;
                                    foreach ($data->timeEntries as $entry) {
                                        $totalHours += $entry->hours;
                                    }
                                @endphp
                                {{ $totalHours }} Hours
                            </h6>
                        </div>
                        <div class="progress-bar__wrapper">
                            @php
                                // Get total estimated hours for all tasks
                                $totalEstimatedHours = 0;
                                foreach ($data->estimatedtimeEntries as $entry) {
                                    $totalEstimatedHours += $entry->hours;
                                }
                                $maxHours = max($totalEstimatedHours, 1); // Use estimated hours as maximum
                                $percentage = ($totalHours / $maxHours) * 100;
                            @endphp

                            @if ($percentage > 100)
                            <style>
                                .employee-progress-all::-webkit-progress-value {
                                    background-color: red; /* Color of the progress value */
                                    border-radius: 10px;
                                }

                                .employee-progress-all::-moz-progress-bar {
                                    background-color: red; /* Color of the progress value for Firefox */
                                }
                            </style>
                            @else
                            <style>
                                .employee-progress-all::-webkit-progress-value {
                                    background-color: #22c55e; /* Green color for the progress value */
                                    border-radius: 10px;
                                }

                                .employee-progress-all::-moz-progress-bar {
                                    background-color: #22c55e; /* Green color for the progress value for Firefox */
                                }
                            </style>
                            @endif

                            <progress class="rounded-full h-2.5 employee-progress-all" id="employee-all-progress" style="accent-color:#22c55e; width:100%" value="{{ min($percentage, 100) }}" max="100"></progress>
                        </div>
                    </div>

                    <div id="employeeTaskBreakdown">
                        @foreach ($data->tasks as $key => $task)
                        <div class="heading employee-task-item" data-task-id="{{ $task->id }}"
                        @if ($key == 0)
                            style="margin-top: 40px;"
                        @endif
                        >
                            <h6 class="mt-4 mb-3" style="float: left">{{ $task->name }}</h6>
                            @php
                                $taskHours = 0;
                                foreach ($data->timeEntries as $entry) {
                                    if ($entry->task_id == $task->id) {
                                        $taskHours += $entry->hours;
                                    }
                                }
                            @endphp
                            <h6 class="mt-4 mb-3 employee-task-hours" style="float: right;">{{ $taskHours }} Hours</h6>
                        </div>
                        <div class="progress-bar__wrapper">
                            @php
                                // Get estimated hours for this task
                                $estimatedTaskHours = 0;
                                foreach ($data->estimatedtimeEntries as $entry) {
                                    if ($entry->task_id == $task->id) {
                                        $estimatedTaskHours += $entry->hours;
                                    }
                                }
                                $maxTaskHours = max($estimatedTaskHours, 1); // Use estimated hours as maximum
                                $taskPercentage = ($taskHours / $maxTaskHours) * 100;
                            @endphp
                            @if ($taskPercentage > 100)
                            <style>
                                .employee-task-progress-{{ $key }}::-webkit-progress-value {
                                    background-color: red; /* Color of the progress value */
                                    border-radius: 10px;
                                }

                                .employee-task-progress-{{ $key }}::-moz-progress-bar {
                                    background-color: red; /* Color of the progress value for Firefox */
                                }
                            </style>
                            @else
                            <style>
                                .employee-task-progress-{{ $key }}::-webkit-progress-value {
                                    background-color: #22c55e; /* Green color for the progress value */
                                    border-radius: 10px;
                                }

                                .employee-task-progress-{{ $key }}::-moz-progress-bar {
                                    background-color: #22c55e; /* Green color for the progress value for Firefox */
                                }
                            </style>
                            @endif
                            <progress class="rounded-full h-2.5 employee-task-progress-{{ $key }} employee-task-progress" data-task-id="{{ $task->id }}" style="accent-color:#22c55e; width:100%" value="{{ min($taskPercentage, 100) }}" max="100"></progress>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-2" style="height: 100%; display: flex; flex-direction: column;">
        <div style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15); padding-top: 0px; border-radius: 8px; margin-right: 16px; padding: 12px; padding-bottom: 20px; flex: 1;">
            <div class="p-4" style="padding-top: 12px; padding-bottom: 0px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h6 style="font-size: 20px; font-weight: bold;">Project Burn Chart</h6>
                    <select id="taskSelector" style="padding: 8px 12px; border: 1px solid #D1D5DB; border-radius: 6px; background: white; font-size: 14px;">
                        <option value="all">All Tasks</option>
                        @foreach ($data->tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Chart Container -->
                <div style="height: 400px; position: relative; margin-top: 2rem;">
                    <canvas id="burnChart" width="100%" height="400"></canvas>
                </div>
                
                <!-- Legend and Summary -->
                <div style="margin-top: 1.5rem; display: flex; justify-content: space-between;">
                    <div style="display: flex; gap: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 20px; height: 2px; background-color: #dadada;"></div>
                            <span style="font-size: 12px; color: #6b7280;">Total Scope (Estimated)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 20px; height: 2px; background-color: #000;"></div>
                            <span style="font-size: 12px; color: #6b7280;">Work Completed (Actual)</span>
                        </div>
                    </div>
                    <div id="chartSummary" style="font-size: 12px; color: #6b7280;">
                        <!-- Summary will be populated by JavaScript -->
                    </div>
                </div>
            </div>
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
                                <button id="cancelAddTaskButton" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                                <button id="saveTaskButton" type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Task Modal -->
                <div id="addMemberModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">Add to Team</div>
                        <div class="modal-body">
                            <form method="POST" action="/projects/member-store/{{ $data->id }}" id="addMemberForm">
                                @csrf
                                <div class="mb-4">
                                    {{-- <label for="taskName" class="block text-sm font-medium text-gray-700">Task Name</label> --}}
                                    <select name="user_id" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" required>
                                        @php
                                            $members = $data->members()->select('user_id')->get()->pluck('user_id')->toArray(); // Extract 'id' values as an array
                                            $new = DB::table('users')->whereNotIn('id', $members)->get();
                                        @endphp
                                        @foreach ($new as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="cancelButtonMember" type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                                <button id="saveTaskButton" type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
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
        
        // Check if user is currently hidden (has fa-eye class) or visible (has fa-eye-slash class)
        const isCurrentlyHidden = $currentElement.hasClass('fa-eye');
        
        if (isCurrentlyHidden) {
            // User is hidden, so we're showing them
            $('#confirmationModalHeader').text('Confirm Showing Employee');
            $('#confirmationModalText').text('Are you sure you want to show this employee?');
        } else {
            // User is visible, so we're hiding them
            $('#confirmationModalHeader').text('Confirm Hiding Employee');
            $('#confirmationModalText').text('Are you sure you want to hide this employee?');
        }
        
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
        
        // Convert date format to DD/MM/YYYY for daterangepicker
        let taskDateRange = $(this).data('task-date');
        $('#startDateedit').val(taskDateRange);
        
        $('#budget_totaledit').val($(this).data('task-budget'));
        // Change modal header for edit
        $('#editTaskModal .modal-header').text('Edit Task');
        // Show modal
        $('#editTaskModal').fadeIn();
    });

    // Cancel button closes modal and resets form
    $('#cancelEditTaskButton').on('click', function(e) {
        e.preventDefault();
        $('#editTaskModal').fadeOut();
        $('#addTaskForm')[0].reset();
        $('#editTaskId').val('');
        $('#editTaskModal .modal-header').text('Add Task');

        return false;
    });

    // Cancel button for Add Task modal
    $('#cancelAddTaskButton').on('click', function(e) {
        e.preventDefault();
        $('#addTaskModal').fadeOut();
        $('#addTaskForm')[0].reset();
        
        return false;
    });

    // Cancel button for Add Member modal
    $('#cancelButtonMember').on('click', function(e) {
        e.preventDefault();
        $('#addMemberModal').fadeOut();
        $('#addMemberForm')[0].reset();
        
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

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    // Prepare task data for charts
    const taskData = {
        @foreach ($data->tasks as $task)
        {{ $task->id }}: {
            id: {{ $task->id }},
            name: "{{ $task->name }}",
            start: new Date('{{ $task->start_date }}'),
            end: new Date('{{ $task->end_date }}'),
            estimated: @php
                $estimatedCost = 0;
                foreach ($data->estimatedtimeEntries as $entry) {
                    if ($entry->task_id == $task->id) {
                        $estimatedCost += $entry->user->hourly_rate * $entry->hours;
                    }
                }
                echo $estimatedCost;
            @endphp,
            actual: @php
                $actualCost = 0;
                foreach ($data->timeEntries as $entry) {
                    if ($entry->task_id == $task->id) {
                        $actualCost += $entry->user->hourly_rate * $entry->hours;
                    }
                }
                echo $actualCost;
            @endphp
        },
        @endforeach
    };

    // Array of all task dates for easier reference
    const taskDates = [
        @foreach ($data->tasks as $task)
        {
            id: {{ $task->id }},
            start: new Date('{{ $task->start_date }}'),
            end: new Date('{{ $task->end_date }}'),
            cost: @php
                $taskActualCost = 0;
                foreach ($data->timeEntries as $entry) {
                    if ($entry->task_id == $task->id) {
                        $taskActualCost += $entry->user->hourly_rate * $entry->hours;
                    }
                }
                echo $taskActualCost;
            @endphp
        },
        @endforeach
    ];

    // All tasks summary
    const allTasksData = {
        name: "All Tasks",
        estimated: @php
            $totalEstimated = 0;
            foreach ($data->estimatedtimeEntries as $entry) {
                $totalEstimated += $entry->user->hourly_rate * $entry->hours;
            }
            echo $totalEstimated;
        @endphp,
        actual: @php
            $totalActual = 0;
            foreach ($data->timeEntries as $entry) {
                $totalActual += $entry->user->hourly_rate * $entry->hours;
            }
            echo $totalActual;
        @endphp
    };

    let burnChart;
    const ctx = document.getElementById('burnChart').getContext('2d');
    
    // Get currency symbol from PHP
    const currencySymbol = @json(formatCurrency(0));
    const symbolOnly = currencySymbol.replace('0', '').trim();

    function updateChart(selectedTaskId) {
        let currentData;
        
        if (selectedTaskId === 'all') {
            currentData = allTasksData;
        } else {
            currentData = taskData[selectedTaskId];
        }

        // Update summary
        const variance = currentData.actual - currentData.estimated;
        const variancePercent = currentData.estimated > 0 ? ((variance / currentData.estimated) * 100).toFixed(1) : 0;
        const status = variance > 0 ? 'Over Budget' : variance < 0 ? 'Under Budget' : 'On Budget';
        const statusColor = variance > 0 ? '#000' : variance < 0 ? '#22c55e' : '#dadada';
        
        document.getElementById('chartSummary').innerHTML = `
            <div style="text-align: right;">
                <div>Variance: <span style="color: ${statusColor}; font-weight: bold;">${symbolOnly}${Math.abs(variance).toLocaleString()} (${Math.abs(variancePercent)}%)</span></div>
                <div style="margin-top: 4px; color: ${statusColor}; font-weight: bold;">${status}</div>
            </div>
        `;

        // Destroy existing chart if it exists
        if (burnChart) {
            burnChart.destroy();
        }

        // Create new chart
        burnChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: generateTimeLabels(selectedTaskId),
                datasets: [
                    {
                        label: 'Total Scope (Estimated)',
                        data: generateEstimatedScopeData(currentData.estimated, selectedTaskId),
                        borderColor: '#dadada',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Work Completed (Actual)',
                        data: generateActualProgressData(currentData.actual, selectedTaskId),
                        borderColor: '#000',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    title: {
                        display: true,
                        text: currentData.name + ' - Burnup Chart',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Project Timeline'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: `Cost (${symbolOnly})`
                        },
                        ticks: {
                            callback: function(value) {
                                return symbolOnly + value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + symbolOnly + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Generate time labels for the chart (project timeline)
    function generateTimeLabels(selectedTaskId = 'all') {
        let earliestDate, latestDate;
        
        if (selectedTaskId === 'all') {
            // For "All Tasks", use the entire project span based on all task dates
            const taskDates = [
                @foreach ($data->tasks as $task)
                {
                    start: new Date('{{ $task->start_date }}'),
                    end: new Date('{{ $task->end_date }}')
                },
                @endforeach
            ];
            
            if (taskDates.length === 0) {
                // Fallback to project dates if no tasks
                earliestDate = new Date('{{ $data->start_date }}');
                latestDate = new Date('{{ $data->end_date }}');
            } else {
                // Find the earliest start date and latest end date across all tasks
                earliestDate = taskDates[0].start;
                latestDate = taskDates[0].end;
                
                taskDates.forEach(task => {
                    if (task.start < earliestDate) earliestDate = task.start;
                    if (task.end > latestDate) latestDate = task.end;
                });
            }
        } else {
            // For specific task, use only that task's date range
            const selectedTask = taskDates.find(task => task.id == selectedTaskId);
            if (selectedTask) {
                earliestDate = selectedTask.start;
                latestDate = selectedTask.end;
            } else {
                // Fallback to project dates if task not found
                earliestDate = new Date('{{ $data->start_date }}');
                latestDate = new Date('{{ $data->end_date }}');
            }
        }
        
        const labels = [];
        
        // Calculate total days for the date range
        const totalDays = Math.ceil((latestDate - earliestDate) / (1000 * 60 * 60 * 24));
        
        // Determine appropriate interval based on total duration
        let interval;
        if (totalDays <= 14) {
            interval = 1; // Daily for short periods
        } else if (totalDays <= 60) {
            interval = 3; // Every 3 days for medium periods
        } else {
            interval = 7; // Weekly for longer periods
        }
        
        // Generate labels with calculated interval
        const current = new Date(earliestDate);
        while (current <= latestDate) {
            labels.push(current.toLocaleDateString('en-GB', { 
                day: '2-digit', 
                month: '2-digit' 
            }));
            current.setDate(current.getDate() + interval);
        }
        
        // Ensure we always include the end date
        const endDateLabel = latestDate.toLocaleDateString('en-GB', { 
            day: '2-digit', 
            month: '2-digit' 
        });
        if (labels[labels.length - 1] !== endDateLabel) {
            labels.push(endDateLabel);
        }
        
        return labels;
    }

    // Generate estimated scope data (flat line showing total estimated cost)
    function generateEstimatedScopeData(estimatedTotal, selectedTaskId = 'all') {
        const labels = generateTimeLabels(selectedTaskId);
        return labels.map(() => estimatedTotal);
    }

    // Generate actual progress data (cumulative actual spending over time)
    function generateActualProgressData(actualTotal, selectedTaskId = 'all') {
        const labels = generateTimeLabels(selectedTaskId);
        const data = [];
        
        let earliestDate, latestDate;
        let relevantTasks;
        
        if (selectedTaskId === 'all') {
            // For "All Tasks", use all task data
            relevantTasks = taskDates;
            
            if (taskDates.length === 0) {
                earliestDate = new Date('{{ $data->start_date }}');
                latestDate = new Date('{{ $data->end_date }}');
            } else {
                earliestDate = taskDates[0].start;
                latestDate = taskDates[0].end;
                
                taskDates.forEach(task => {
                    if (task.start < earliestDate) earliestDate = task.start;
                    if (task.end > latestDate) latestDate = task.end;
                });
            }
        } else {
            // For specific task, use only that task's data
            const selectedTask = taskDates.find(task => task.id == selectedTaskId);
            if (selectedTask) {
                relevantTasks = [selectedTask];
                earliestDate = selectedTask.start;
                latestDate = selectedTask.end;
            } else {
                // Fallback
                relevantTasks = [];
                earliestDate = new Date('{{ $data->start_date }}');
                latestDate = new Date('{{ $data->end_date }}');
            }
        }
        
        // Calculate total days for interval calculation
        const totalDays = Math.ceil((latestDate - earliestDate) / (1000 * 60 * 60 * 24));
        let interval;
        if (totalDays <= 14) {
            interval = 1; // Daily
        } else if (totalDays <= 60) {
            interval = 3; // Every 3 days
        } else {
            interval = 7; // Weekly
        }
        
        // Generate cumulative spending based on task completion timeline
        for (let i = 0; i < labels.length; i++) {
            const currentDate = new Date(earliestDate);
            currentDate.setDate(currentDate.getDate() + (i * interval));
            
            let cumulativeCost = 0;
            
            // Calculate cumulative cost based on tasks that should be completed by this date
            relevantTasks.forEach(task => {
                if (currentDate >= task.end) {
                    // Task is completed, add full cost
                    cumulativeCost += task.cost;
                } else if (currentDate >= task.start && currentDate < task.end) {
                    // Task is in progress, add proportional cost
                    const taskDuration = task.end - task.start;
                    if (taskDuration > 0) {
                        const elapsed = currentDate - task.start;
                        const progress = elapsed / taskDuration;
                        cumulativeCost += task.cost * Math.min(Math.max(progress, 0), 1);
                    }
                }
                // If currentDate < task.start, task hasn't started yet, add 0
            });
            
            data.push(Math.min(cumulativeCost, actualTotal));
        }
        
        return data;
    }

    // Initialize with "All Tasks"
    updateChart('all');

    // Handle dropdown change
    document.getElementById('taskSelector').addEventListener('change', function() {
        updateChart(this.value);
    });

    // Employee Hours Tracking functionality
    const employeeTimeData = {
        @foreach ($data->members as $member)
        {{ $member->user->id }}: {
            id: {{ $member->user->id }},
            name: "{{ $member->user->name }}",
            totalHours: @php
                $userTotalHours = 0;
                foreach ($data->timeEntries as $entry) {
                    if ($entry->user_id == $member->user->id) {
                        $userTotalHours += $entry->hours;
                    }
                }
                echo $userTotalHours;
            @endphp,
            totalEstimatedHours: @php
                $userTotalEstimatedHours = 0;
                foreach ($data->estimatedtimeEntries as $entry) {
                    if ($entry->user_id == $member->user->id) {
                        $userTotalEstimatedHours += $entry->hours;
                    }
                }
                echo $userTotalEstimatedHours;
            @endphp,
            taskHours: {
                @foreach ($data->tasks as $task)
                {{ $task->id }}: @php
                    $userTaskHours = 0;
                    foreach ($data->timeEntries as $entry) {
                        if ($entry->user_id == $member->user->id && $entry->task_id == $task->id) {
                            $userTaskHours += $entry->hours;
                        }
                    }
                    echo $userTaskHours;
                @endphp,
                @endforeach
            },
            taskEstimatedHours: {
                @foreach ($data->tasks as $task)
                {{ $task->id }}: @php
                    $userTaskEstimatedHours = 0;
                    foreach ($data->estimatedtimeEntries as $entry) {
                        if ($entry->user_id == $member->user->id && $entry->task_id == $task->id) {
                            $userTaskEstimatedHours += $entry->hours;
                        }
                    }
                    echo $userTaskEstimatedHours;
                @endphp,
                @endforeach
            }
        },
        @endforeach
    };

    // All employees data
    const allEmployeesData = {
        totalHours: @php
            $allTotalHours = 0;
            foreach ($data->timeEntries as $entry) {
                $allTotalHours += $entry->hours;
            }
            echo $allTotalHours;
        @endphp,
        totalEstimatedHours: @php
            $allTotalEstimatedHours = 0;
            foreach ($data->estimatedtimeEntries as $entry) {
                $allTotalEstimatedHours += $entry->hours;
            }
            echo $allTotalEstimatedHours;
        @endphp,
        taskHours: {
            @foreach ($data->tasks as $task)
            {{ $task->id }}: @php
                $allTaskHours = 0;
                foreach ($data->timeEntries as $entry) {
                    if ($entry->task_id == $task->id) {
                        $allTaskHours += $entry->hours;
                    }
                }
                echo $allTaskHours;
            @endphp,
            @endforeach
        },
        taskEstimatedHours: {
            @foreach ($data->tasks as $task)
            {{ $task->id }}: @php
                $allTaskEstimatedHours = 0;
                foreach ($data->estimatedtimeEntries as $entry) {
                    if ($entry->task_id == $task->id) {
                        $allTaskEstimatedHours += $entry->hours;
                    }
                }
                echo $allTaskEstimatedHours;
            @endphp,
            @endforeach
        }
    };

    function updateEmployeeHours(selectedEmployeeId) {
        let currentEmployeeData;
        
        if (selectedEmployeeId === 'all') {
            currentEmployeeData = allEmployeesData;
        } else {
            currentEmployeeData = employeeTimeData[selectedEmployeeId];
        }

        // Update total hours display
        const estimatedHours = currentEmployeeData.totalEstimatedHours || 0;
        document.getElementById('employeeAllPhaseHours').textContent = currentEmployeeData.totalHours + ' / ' + estimatedHours + ' Hours';
        
        // Update total hours progress bar
        const maxHours = estimatedHours || 1; // Use estimated hours as maximum, prevent division by zero
        const totalPercentage = (currentEmployeeData.totalHours / maxHours) * 100;
        document.getElementById('employee-all-progress').value = Math.min(totalPercentage, 100);
        
        // Update each task's hours and progress bar
        document.querySelectorAll('.employee-task-item').forEach(function(taskItem) {
            const taskId = taskItem.getAttribute('data-task-id');
            const taskHours = currentEmployeeData.taskHours[taskId] || 0;
            const taskEstimatedHours = currentEmployeeData.taskEstimatedHours[taskId] || 0;
            
            // Update hours display
            const hoursElement = taskItem.querySelector('.employee-task-hours');
            hoursElement.textContent = taskHours + ' / ' + taskEstimatedHours + ' Hours';
            
            // Update progress bar
            const progressBar = document.querySelector('.employee-task-progress[data-task-id="' + taskId + '"]');
            if (progressBar) {
                const maxTaskHours = taskEstimatedHours || 1; // Use estimated hours as maximum, prevent division by zero
                const taskPercentage = (taskHours / maxTaskHours) * 100;
                progressBar.value = Math.min(taskPercentage, 100);
            }
        });
    }

    // Initialize with "All Employees"
    updateEmployeeHours('all');

    // Handle employee selector change
    document.getElementById('employeeSelector').addEventListener('change', function() {
        updateEmployeeHours(this.value);
    });
});
</script>
