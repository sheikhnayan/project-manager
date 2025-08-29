<div class="content mt-4">
    <div class="grid grid-cols-10 gap-4">
        <div class="col-span-3" style="margin: 16px;">
            <h6>Project</h6>
            <div class="chart-container">
                <div class="donut-chart" data-id="chart1">
                    <div class="donut-center"></div>
                </div>
                <ul class="legend" data-chart="chart1">
                    @php
                        $total_hours = $hours->sum('hours');
                    @endphp
                    @foreach ($data as $item)
                        @if (!isset($item->is_archived) || $item->is_archived == 0)
                        @php
                        $times = DB::table('time_entries')
                            ->where('project_id', $item->id)
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum('hours');
                            $percentage = $total_hours > 0 ? ($times / $total_hours) * 100 : 0;
                        @endphp
                        @if($times > 0)
                        <li  data-percent="{{ $percentage }}">{{ $item->project_number }}_{{ $item->name }} - {{ $times }} ({{ round($percentage, 1) }}%)</li>
                        @endif
                        @endif
                    @endforeach
                    {{-- <li data-color="#f67019" data-percent="35">Project B - 35%</li>
                    <li data-color="#f53794" data-percent="25">Project C - 25%</li> --}}
                </ul>
            </div>
        </div>

        <div class="col-span-3" style="margin: 16px;">
            <h6>Cost</h6>
            <div class="chart-container">
                <div class="donut-chart" data-id="chart3">
                    <div class="donut-center"></div>
                </div>
                <ul class="legend" data-chart="chart3">
                    @php
                        $total_cost = 0;
                        foreach($hours as $hour) {
                            $total_cost += $hour->user->hourly_rate * $hour->hours;
                        }
                    @endphp
                    @foreach ($data as $item)
                        @if (!isset($item->is_archived) || $item->is_archived == 0)
                        @php
                        $costs = 0;

                        foreach($hours as $hour) {
                            if ($hour->project_id == $item->id) {
                                $costs += $hour->user->hourly_rate * $hour->hours;
                            }
                        }

                            $percentage = $total_cost > 0 ? ($costs / $total_cost) * 100 : 0;
                        @endphp
                        @if($costs > 0)
                        <li  data-percent="{{ $percentage }}">{{ $item->project_number }}_{{ $item->name }} - {{ $costs }} ({{ round($percentage, 1) }}%)</li>
                        @endif
                        @endif
                    @endforeach
                    {{-- <li data-color="#00a950" data-percent="50">R&D - 50%</li>
                    <li data-color="#58595b" data-percent="30">Marketing - 30%</li>
                    <li data-color="#8549ba" data-percent="20">Operations - 20%</li> --}}
                </ul>
            </div>

        </div>

        <div class="col-span-4" style="margin: 16px;">
            <h6>Task</h6>
            <div class="chart-container">
                <div class="donut-chart" data-id="chart2">
                    <div class="donut-center"></div>
                </div>
                <ul class="legend" data-chart="chart2">
                    @foreach ($task as $item)
                    @if ($hours->contains('task_id', $item->id))
                        @php
                        $times = DB::table('time_entries')
                            ->where('task_id', $item->id)
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum('hours');
                            $percentage = $total_hours > 0 ? ($times / $total_hours) * 100 : 0;
                        @endphp

                        <li  data-percent="{{ $percentage }}">{{ $item->name }} - {{ $times }} ({{ round($percentage, 1) }}%)</li>
                    @endif
                        @endforeach
                    {{-- <li data-color="#36a2eb" data-percent="60">Completed - 60%</li>
                    <li data-color="#ffcd56" data-percent="30">In Progress - 30%</li>
                    <li data-color="#ff6384" data-percent="10">Pending - 10%</li> --}}
                </ul>
            </div>

        </div>

        
    </div>
</div>
<div class="content mt-4">
    <div class="grid grid-cols-10 gap-4">
        <div class="col-span-10" style="margin: 16px;">
            <div class="overflow-x-auto" style="border-radius: 4px;">
                                <table class="w-full border border-gray-300" style="border-radius: 4px; overflow: hidden; table-layout: fixed;">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border border-gray-300 text-left cursor-pointer" style="border-top-left-radius: 4px; width: 50%;" onclick="sortTable(0)">
                                Task
                                <span class="sort-indicator" data-column="0">▲▼</span>
                            </th>
                            <th class="px-4 py-2 border border-gray-300 text-left cursor-pointer" style="width: 25%;" onclick="sortTable(1)">
                                Hours
                                <span class="sort-indicator" data-column="1">▲▼</span>
                            </th>
                            <th class="px-4 py-2 border border-gray-300 text-left cursor-pointer" style="border-top-right-radius: 4px; width: 25%;" onclick="sortTable(2)">
                                Cost
                                <span class="sort-indicator" data-column="2">▲▼</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($data as $hour)
                            @if (!isset($hour->is_archived) || $hour->is_archived == 0)
                            <tr>
                                <td class="px-4 py-2 border border-gray-300"> <a href="/reports/{{ $hour->id }}">{{ $hour->name }}</a></td>
                                <td class="px-4 py-2 border border-gray-300">
                                    @php
                                        $ho = DB::table('time_entries')
                                            ->where('project_id', $hour->id)
                                            ->whereBetween('created_at', [$startDate, $endDate])
                                            ->sum('hours');
                                    @endphp
                                    {{ round($ho) }}
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    @php
                                        $hoo = DB::table('time_entries')
                                            ->where('project_id', $hour->id)
                                            ->whereBetween('created_at', [$startDate, $endDate])
                                            ->get();
                                        $cost = 0;
                                        foreach ($hoo as $h) {
                                            $user = DB::table('users')
                                                ->where('id', $h->user_id)
                                                ->first();
                                            $cost += $user->hourly_rate * $h->hours;
                                        }
                                    @endphp
                                    €{{ $cost }}
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
