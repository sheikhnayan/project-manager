<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Project Management</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Date-fns -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/date-fns/2.30.0/date-fns.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>

    <style>
.chart-container {
  display: flex;
  align-items: flex-start; /* 👈 FIXED alignment */
  font-family: Arial, sans-serif;
  gap: 2rem;
  margin-bottom: 2rem;
}

.donut-chart {
  width: 200px;
  height: 200px;
  border-radius: 50%;
  position: relative;
  background: conic-gradient(gray 100%); /* default */
}

.donut-center {
  position: absolute;
  width: 100px;
  height: 100px;
  background: white;
  border-radius: 50%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.legend {
  list-style: none;
  padding: 0;
  margin: 0;
}

.legend li {
  display: flex;
  align-items: center;
  margin-bottom: 0.5rem;
  font-size: 14px;
  position: relative;
  padding-left: 1.5rem;
}

.legend li::before {
  content: '';
  width: 1rem;
  height: 1rem;
  position: absolute;
  left: 0;
  top: 2px;
  border-radius: 2px;
  background-color: currentColor;
}

th {
	background-color: #000;
	color: #fff;
}

/* Select the first <td> of the last <tr> in any table */
table tr:last-child td:first-child {
    /* Your styles here */
    border-bottom-left-radius: 4px;
}

table tr:last-child td:last-child {
    /* Your styles here */
    border-bottom-right-radius: 4px;
}

    </style>

    <!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-white">
    @include('front.nav')

    <main class="py-6">
        <div class="mx-auto p-4 overflow-hidden rounded-lg shadow border" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
             <div class="content" style="display: flex; margin-bottom: 40px;">
                <div style="margin-top: 6px; display: inline-flex;">
                    <h5 style="font-size: 20px; font-weight: 600; margin-left: 7px;">Report</h5>
                </div>
                <div class="flex items-center gap-3 mb-4 mt-2" style="display: inline-flex; margin-left: 3rem;">
                    <select id="range-select" class="block appearance-none bg-white border border-gray-300 hover:border-gray-500 px-4 pr-4 rounded shadow leading-tight focus:outline-none focus:shadow-outline" style="padding-top: 0.4rem; padding-bottom: 0.4rem;;">
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                    <button id="prev-week" class="text-gray-600 hover:text-black">
                        <i class="fas fa-chevron-left" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem;"></i>
                    </button>
                    <span id="date-range" class="text-gray-600 font-semibold" style="width: 185px; font-size: 0.875rem; color: #000; text-align: center;">This Week</span>
                    <button id="next-week" class="text-gray-600 hover:text-black">
                        <i class="fas fa-chevron-right" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem;"></i>
                    </button>
                    <button id="custom-range-btn" class="border border-gray-300 rounded px-2 py-1 ml-2 hidden" style="width: 220px; background: #fff; text-align: left;">
                        <span id="custom-range-label">Select range</span>
                    </button>
                </div>
            </div>
            <div id="fetch">
                @include('front.report', ['data' => $data, 'task' => $task, 'hours' => $hours])
            </div>
        </div>
    </main>

<script>
// Initialize Lucide icons
lucide.createIcons();
</script>

<script>




document.addEventListener('DOMContentLoaded', function () {
    const dateRangeElement = document.getElementById('date-range');
    const prevWeekBtn = document.getElementById('prev-week');
    const nextWeekBtn = document.getElementById('next-week');
    const rangeSelect = document.getElementById('range-select');
    const customRangeBtn = document.getElementById('custom-range-btn');
    const customRangeLabel = document.getElementById('custom-range-label');
    let currentDate = new Date();
    let currentMode = 'week'; // week, month, custom
    let customRange = [null, null];

    function getStartOfWeek(date) {
        // Clone the date to avoid mutating the original
        const d = new Date(date);
        const day = d.getDay(); // 0 (Sun) - 6 (Sat)
        // Calculate difference to Monday (1)
        const diff = (day === 0 ? -6 : 1 - day);
        d.setDate(d.getDate() + diff);
        d.setHours(0,0,0,0);
        return d;
    }

    function getStartOfMonth(date) {
        return new Date(date.getFullYear(), date.getMonth(), 1);
    }

    function getEndOfMonth(date) {
        return new Date(date.getFullYear(), date.getMonth() + 1, 0);
    }

    function formatDate(date) {
        return date.toISOString().slice(0, 10);
    }

    function formatShortDate(date) {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    function formatDateRange(date) {
        if (currentMode === 'week') {
            const startDate = getStartOfWeek(date);
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6);
            return `${formatShortDate(startDate)} - ${formatShortDate(endDate)}`;
        } else if (currentMode === 'month') {
            const startDate = getStartOfMonth(date);
            const endDate = getEndOfMonth(date);
            return `${formatShortDate(startDate)} - ${formatShortDate(endDate)}`;
        } else if (currentMode === 'custom' && customRange[0] && customRange[1]) {
            return `${formatShortDate(customRange[0])} - ${formatShortDate(customRange[1])}`;
        } else {
            return '';
        }
    }

    function updateDateRange() {
        dateRangeElement.textContent = formatDateRange(currentDate);

        let startDate, endDate;
        if (currentMode === 'week') {
            startDate = formatDate(getStartOfWeek(currentDate));
            endDate = formatDate(new Date(getStartOfWeek(currentDate).getTime() + 6 * 24 * 60 * 60 * 1000));
        } else if (currentMode === 'month') {
            startDate = formatDate(getStartOfMonth(currentDate));
            endDate = formatDate(getEndOfMonth(currentDate));
        } else if (currentMode === 'custom' && customRange[0] && customRange[1]) {
            startDate = formatDate(customRange[0]);
            endDate = formatDate(customRange[1]);
        } else {
            return;
        }

        // Reload the same route with parameters
        const url = new URL(window.location.href.split('?')[0]);
        url.searchParams.set('start_date', startDate);
        url.searchParams.set('end_date', endDate);
        // window.location.href = url.toString();
        $('#fetch').load(url.toString(), function() {
            setTimeout(() => {
                initProgressRings();
            }, 10);
        });
    }

    function generateColor(index, total) {
        const hue = Math.floor((360 / total) * index); // evenly spaced hues
        return `hsl(${hue}, 70%, 60%)`;
    }

    function initProgressRings() {
        // Initialize progress rings here if needed
        document.querySelectorAll('.donut-chart').forEach(chart => {
            const chartId = chart.dataset.id;
            const legendItems = document.querySelectorAll(`.legend[data-chart="${chartId}"] li`);

            let currentPercent = 0;
            let gradientParts = [];

            legendItems.forEach((item, index) => {
                const percent = parseFloat(item.dataset.percent);
                const color = generateColor(index, legendItems.length);

                // Store color in inline style
                item.style.color = color;

                // For the pie chart
                const start = currentPercent;
                const end = currentPercent + percent;
                gradientParts.push(`${color} ${start}% ${end}%`);
                currentPercent = end;
            });

            chart.style.background = `conic-gradient(${gradientParts.join(', ')})`;
        });
    }

    prevWeekBtn.addEventListener('click', function () {
        if (currentMode === 'week') {
            currentDate = getStartOfWeek(currentDate);
            currentDate.setDate(currentDate.getDate() - 7);
        } else if (currentMode === 'month') {
            currentDate = getStartOfMonth(currentDate);
            currentDate.setMonth(currentDate.getMonth() - 1);
        }
        updateDateRange();
    });

    nextWeekBtn.addEventListener('click', function () {
        if (currentMode === 'week') {
            currentDate = getStartOfWeek(currentDate);
            currentDate.setDate(currentDate.getDate() + 7);
        } else if (currentMode === 'month') {
            currentDate = getStartOfMonth(currentDate);
            currentDate.setMonth(currentDate.getMonth() + 1);
        }
        updateDateRange();
    });

    rangeSelect.addEventListener('change', function () {
        currentMode = this.value;
        if (currentMode === 'custom') {
            customRangeBtn.classList.remove('hidden');
        } else {
            customRangeBtn.classList.add('hidden');
            updateDateRange();
        }
    });

    // Show picker on button click
    customRangeBtn.addEventListener('click', function() {
        fp.open();
    });

    // Flatpickr instance
    let fp = flatpickr(customRangeBtn, {
        mode: "range",
        dateFormat: "Y-m-d",
        onClose: function(selectedDates, dateStr) {
            if (selectedDates.length === 2) {
                customRange = selectedDates;
                customRangeLabel.textContent = `${formatShortDate(selectedDates[0])} - ${formatShortDate(selectedDates[1])}`;
                customRangeBtn.classList.add('hidden'); // Hide after selection
                updateDateRange();
            }
        }
    });

    // Initialize
    updateDateRange();
});
</script>

</body>
</html>
