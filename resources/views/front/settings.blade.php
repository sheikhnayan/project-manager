<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Setting - Project Management</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
        <div class="mb-4">
            <h2 class=" font-bold text-gray-900" style="font-size: 20px;">Settings</h2>
            <p class="text-gray-600 mt-1" style="float: left;">Configure your application settings, remember to save for these to take effect.</p>
            <div class="flex justify-end">
                <button style="margin-right: 1.6rem;" type="button" onclick="document.getElementById('settingsForm').submit()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Save Settings
                </button>
            </div>
        </div>

        <form id="settingsForm" method="POST" action="/settings" enctype="multipart/form-data" class="space-y-6">
            @csrf
            {{-- @method('PUT') --}}

            <!-- Time Format Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Time & Date Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Time Format -->
                    <div>
                        <label for="time_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Time Format
                        </label>
                        <select name="time_format" id="time_format" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black">
                            <option value="24-hour" {{ old('time_format', $data->time_format ?? '24-hour') == '24-hour' ? 'selected' : '' }}>
                                24-hour (14:30)
                            </option>
                            <option value="12-hour" {{ old('time_format', $data->time_format ?? '24-hour') == '12-hour' ? 'selected' : '' }}>
                                12-hour (2:30 PM)
                            </option>
                        </select>
                    </div>

                    <!-- Date Format -->
                    <div>
                        <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Date Format
                        </label>
                        <select name="date_format" id="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black">
                            <option value="Y-m-d" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>
                                YYYY-MM-DD (2025-08-07)
                            </option>
                            <option value="m/d/Y" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>
                                MM/DD/YYYY (08/07/2025)
                            </option>
                            <option value="d/m/Y" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>
                                DD/MM/YYYY (07/08/2025)
                            </option>
                            <option value="d-m-Y" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'd-m-Y' ? 'selected' : '' }}>
                                DD-MM-YYYY (07-08-2025)
                            </option>
                            <option value="M j, Y" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'M j, Y' ? 'selected' : '' }}>
                                Aug 7, 2025
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Currency & Working Hours Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Business Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                            Currency
                        </label>
                        <select name="currency" id="currency" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black">
                            <option value="USD" {{ old('currency', $data->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD - US Dollar ($)</option>
                            <option value="EUR" {{ old('currency', $data->currency ?? 'USD') == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                            <option value="GBP" {{ old('currency', $data->currency ?? 'USD') == 'GBP' ? 'selected' : '' }}>GBP - British Pound (£)</option>
                            <option value="JPY" {{ old('currency', $data->currency ?? 'USD') == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen (¥)</option>
                            <option value="AUD" {{ old('currency', $data->currency ?? 'USD') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar (A$)</option>
                            <option value="CAD" {{ old('currency', $data->currency ?? 'USD') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar (C$)</option>
                            <option value="CHF" {{ old('currency', $data->currency ?? 'USD') == 'CHF' ? 'selected' : '' }}>CHF - Swiss Franc (Fr)</option>
                            <option value="CNY" {{ old('currency', $data->currency ?? 'USD') == 'CNY' ? 'selected' : '' }}>CNY - Chinese Yuan (¥)</option>
                            <option value="INR" {{ old('currency', $data->currency ?? 'USD') == 'INR' ? 'selected' : '' }}>INR - Indian Rupee (₹)</option>
                            <option value="NZD" {{ old('currency', $data->currency ?? 'USD') == 'NZD' ? 'selected' : '' }}>NZD - New Zealand Dollar (NZ$)</option>
                            <option value="SGD" {{ old('currency', $data->currency ?? 'USD') == 'SGD' ? 'selected' : '' }}>SGD - Singapore Dollar (S$)</option>
                            <option value="HKD" {{ old('currency', $data->currency ?? 'USD') == 'HKD' ? 'selected' : '' }}>HKD - Hong Kong Dollar (HK$)</option>
                            <option value="SEK" {{ old('currency', $data->currency ?? 'USD') == 'SEK' ? 'selected' : '' }}>SEK - Swedish Krona (kr)</option>
                            <option value="NOK" {{ old('currency', $data->currency ?? 'USD') == 'NOK' ? 'selected' : '' }}>NOK - Norwegian Krone (kr)</option>
                            <option value="DKK" {{ old('currency', $data->currency ?? 'USD') == 'DKK' ? 'selected' : '' }}>DKK - Danish Krone (kr)</option>
                            <option value="KRW" {{ old('currency', $data->currency ?? 'USD') == 'KRW' ? 'selected' : '' }}>KRW - South Korean Won (₩)</option>
                            <option value="ZAR" {{ old('currency', $data->currency ?? 'USD') == 'ZAR' ? 'selected' : '' }}>ZAR - South African Rand (R)</option>
                            <option value="BRL" {{ old('currency', $data->currency ?? 'USD') == 'BRL' ? 'selected' : '' }}>BRL - Brazilian Real (R$)</option>
                            <option value="MXN" {{ old('currency', $data->currency ?? 'USD') == 'MXN' ? 'selected' : '' }}>MXN - Mexican Peso ($)</option>
                            <option value="AED" {{ old('currency', $data->currency ?? 'USD') == 'AED' ? 'selected' : '' }}>AED - UAE Dirham (د.إ)</option>
                            <option value="SAR" {{ old('currency', $data->currency ?? 'USD') == 'SAR' ? 'selected' : '' }}>SAR - Saudi Riyal (﷼)</option>
                            <option value="TRY" {{ old('currency', $data->currency ?? 'USD') == 'TRY' ? 'selected' : '' }}>TRY - Turkish Lira (₺)</option>
                            <option value="RUB" {{ old('currency', $data->currency ?? 'USD') == 'RUB' ? 'selected' : '' }}>RUB - Russian Ruble (₽)</option>
                            <option value="PLN" {{ old('currency', $data->currency ?? 'USD') == 'PLN' ? 'selected' : '' }}>PLN - Polish Zloty (zł)</option>
                            <option value="THB" {{ old('currency', $data->currency ?? 'USD') == 'THB' ? 'selected' : '' }}>THB - Thai Baht (฿)</option>
                            <option value="IDR" {{ old('currency', $data->currency ?? 'USD') == 'IDR' ? 'selected' : '' }}>IDR - Indonesian Rupiah (Rp)</option>
                            <option value="MYR" {{ old('currency', $data->currency ?? 'USD') == 'MYR' ? 'selected' : '' }}>MYR - Malaysian Ringgit (RM)</option>
                            <option value="PHP" {{ old('currency', $data->currency ?? 'USD') == 'PHP' ? 'selected' : '' }}>PHP - Philippine Peso (₱)</option>
                            <option value="VND" {{ old('currency', $data->currency ?? 'USD') == 'VND' ? 'selected' : '' }}>VND - Vietnamese Dong (₫)</option>
                            <option value="CZK" {{ old('currency', $data->currency ?? 'USD') == 'CZK' ? 'selected' : '' }}>CZK - Czech Koruna (Kč)</option>
                            <option value="HUF" {{ old('currency', $data->currency ?? 'USD') == 'HUF' ? 'selected' : '' }}>HUF - Hungarian Forint (Ft)</option>
                            <option value="ILS" {{ old('currency', $data->currency ?? 'USD') == 'ILS' ? 'selected' : '' }}>ILS - Israeli Shekel (₪)</option>
                            <option value="CLP" {{ old('currency', $data->currency ?? 'USD') == 'CLP' ? 'selected' : '' }}>CLP - Chilean Peso ($)</option>
                            <option value="COP" {{ old('currency', $data->currency ?? 'USD') == 'COP' ? 'selected' : '' }}>COP - Colombian Peso ($)</option>
                            <option value="ARS" {{ old('currency', $data->currency ?? 'USD') == 'ARS' ? 'selected' : '' }}>ARS - Argentine Peso ($)</option>
                            <option value="EGP" {{ old('currency', $data->currency ?? 'USD') == 'EGP' ? 'selected' : '' }}>EGP - Egyptian Pound (£)</option>
                            <option value="PKR" {{ old('currency', $data->currency ?? 'USD') == 'PKR' ? 'selected' : '' }}>PKR - Pakistani Rupee (₨)</option>
                            <option value="BDT" {{ old('currency', $data->currency ?? 'USD') == 'BDT' ? 'selected' : '' }}>BDT - Bangladeshi Taka (৳)</option>
                            <option value="NGN" {{ old('currency', $data->currency ?? 'USD') == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira (₦)</option>
                            <option value="KES" {{ old('currency', $data->currency ?? 'USD') == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling (KSh)</option>
                            <option value="UAH" {{ old('currency', $data->currency ?? 'USD') == 'UAH' ? 'selected' : '' }}>UAH - Ukrainian Hryvnia (₴)</option>
                            <option value="RON" {{ old('currency', $data->currency ?? 'USD') == 'RON' ? 'selected' : '' }}>RON - Romanian Leu (lei)</option>
                            <option value="BGN" {{ old('currency', $data->currency ?? 'USD') == 'BGN' ? 'selected' : '' }}>BGN - Bulgarian Lev (лв)</option>
                            <option value="HRK" {{ old('currency', $data->currency ?? 'USD') == 'HRK' ? 'selected' : '' }}>HRK - Croatian Kuna (kn)</option>
                            <option value="ISK" {{ old('currency', $data->currency ?? 'USD') == 'ISK' ? 'selected' : '' }}>ISK - Icelandic Krona (kr)</option>
                            <option value="QAR" {{ old('currency', $data->currency ?? 'USD') == 'QAR' ? 'selected' : '' }}>QAR - Qatari Riyal (﷼)</option>
                            <option value="KWD" {{ old('currency', $data->currency ?? 'USD') == 'KWD' ? 'selected' : '' }}>KWD - Kuwaiti Dinar (د.ك)</option>
                            <option value="BHD" {{ old('currency', $data->currency ?? 'USD') == 'BHD' ? 'selected' : '' }}>BHD - Bahraini Dinar (د.ب)</option>
                            <option value="OMR" {{ old('currency', $data->currency ?? 'USD') == 'OMR' ? 'selected' : '' }}>OMR - Omani Rial (﷼)</option>
                            <option value="JOD" {{ old('currency', $data->currency ?? 'USD') == 'JOD' ? 'selected' : '' }}>JOD - Jordanian Dinar (د.ا)</option>
                            <option value="LBP" {{ old('currency', $data->currency ?? 'USD') == 'LBP' ? 'selected' : '' }}>LBP - Lebanese Pound (ل.ل)</option>
                            <option value="TWD" {{ old('currency', $data->currency ?? 'USD') == 'TWD' ? 'selected' : '' }}>TWD - Taiwan Dollar (NT$)</option>
                            <option value="PEN" {{ old('currency', $data->currency ?? 'USD') == 'PEN' ? 'selected' : '' }}>PEN - Peruvian Sol (S/)</option>
                            <option value="UYU" {{ old('currency', $data->currency ?? 'USD') == 'UYU' ? 'selected' : '' }}>UYU - Uruguayan Peso ($U)</option>
                            <option value="VES" {{ old('currency', $data->currency ?? 'USD') == 'VES' ? 'selected' : '' }}>VES - Venezuelan Bolivar (Bs.)</option>
                        </select>
                    </div>

                    <!-- Working Hours -->
                    <div>
                        <label for="working_hour" class="block text-sm font-medium text-gray-700 mb-2">
                            Daily Working Hours
                        </label>
                        <div class="relative">
                            <input style="height: 38px;"
                                   type="number" 
                                   name="working_hour" 
                                   id="working_hour" 
                                   min="1" 
                                   max="24" 
                                   step="0.5"
                                   value="{{ old('working_hour', $data->working_hour ?? '8') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="8">
                            <span class="absolute right-3 top-2 text-gray-500 text-sm">hours</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Standard working hours per day</p>
                    </div>
                </div>
            </div>

            <!-- Logo Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Branding</h2>
                
                <!-- Company Name -->
                <div class="mb-6">
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Company Name
                    </label>
                    <input type="text" 
                           name="company_name" 
                           id="company_name" 
                           value="{{ old('company_name', $data->company_name ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                           placeholder="Enter your company name">
                    <p class="text-xs text-gray-500 mt-1">This will be displayed in timesheets and other areas</p>
                </div>
                
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Company Logo
                    </label>
                    <div class="flex items-center space-x-4">
                        <!-- Current Logo Preview -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 border-2 border-gray-300 border-dashed rounded-lg flex items-center justify-center bg-gray-50">
                                @if(isset($data->logo) && $data->logo && $data->logo != '8')
                                    <img src="{{ asset('storage/' . $data->logo) }}" alt="Current Logo" class="w-full h-full object-contain rounded-lg">
                                @else
                                    <i class="fas fa-image text-gray-400 text-xl"></i>
                                @endif
                            </div>
                        </div>
                        
                        <!-- File Input -->
                        <div class="flex-1">
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-black hover:file:bg-gray-100">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB. Recommended size: 200x200px</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Presets Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200" x-data="{
                presets: {{ json_encode($data->task_presets ?? []) }},
                showAddPreset: false,
                editingPreset: null,
                currentPresetIndex: 0,
                newPreset: {
                    title: '',
                    tasks: []
                },
                saving: false,
                saveMessage: '',
                init() {
                    this.$nextTick(() => {
                        this.initSortable();
                    });
                },
                nextPreset() {
                    if (this.currentPresetIndex < this.presets.length - 1) {
                        this.currentPresetIndex++;
                    }
                },
                prevPreset() {
                    if (this.currentPresetIndex > 0) {
                        this.currentPresetIndex--;
                    }
                },
                async savePresets() {
                    this.saving = true;
                    this.saveMessage = '';
                    
                    try {
                        const response = await fetch('/settings/presets', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                task_presets: this.presets
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            this.saveMessage = 'Preset saved successfully!';
                            setTimeout(() => this.saveMessage = '', 3000);
                        } else {
                            throw new Error(result.message || 'Failed to save preset');
                        }
                    } catch (error) {
                        console.error('Error saving preset:', error);
                        this.saveMessage = 'Error: ' + error.message;
                        setTimeout(() => this.saveMessage = '', 5000);
                    } finally {
                        this.saving = false;
                    }
                },
                initSortable() {
                    // Re-initialize sortable when tasks change
                    this.$watch('showAddPreset', () => {
                        if (this.showAddPreset) {
                            this.$nextTick(() => {
                                const taskContainer = document.querySelector('.task-sortable');
                                if (taskContainer) {
                                    // Destroy previous instance if exists
                                    if (taskContainer.sortableInstance) {
                                        taskContainer.sortableInstance.destroy();
                                    }
                                    
                                    // Create new sortable instance
                                    taskContainer.sortableInstance = Sortable.create(taskContainer, {
                                        animation: 150,
                                        ghostClass: 'bg-gray-100',
                                        chosenClass: 'bg-gray-50',
                                        dragClass: 'opacity-50',
                                        handle: '.cursor-move',
                                        onEnd: (evt) => {
                                            // Reorder the tasks array
                                            const tasks = this.newPreset.tasks;
                                            const movedTask = tasks[evt.oldIndex];
                                            tasks.splice(evt.oldIndex, 1);
                                            tasks.splice(evt.newIndex, 0, movedTask);
                                            
                                            // Update positions
                                            this.updateTaskPositions();
                                        }
                                    });
                                }
                            });
                        } else {
                            // Cleanup when modal closes
                            const taskContainer = document.querySelector('.task-sortable');
                            if (taskContainer && taskContainer.sortableInstance) {
                                taskContainer.sortableInstance.destroy();
                                taskContainer.sortableInstance = null;
                            }
                        }
                    });
                },
                updateTaskPositions() {
                    // Update positions after drag and drop
                    this.newPreset.tasks.forEach((task, index) => {
                        task.position = index + 1;
                    });
                }
            }">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-semibold text-gray-900">Task List Presets</h2>
                        
                        <!-- Navigation Arrows -->
                        <div x-show="presets.length > 0" class="flex items-center gap-2">
                            <button type="button" 
                                    @click="prevPreset()"
                                    :disabled="currentPresetIndex === 0"
                                    :class="currentPresetIndex === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                                    class="p-1 rounded transition-colors">
                                <i class="fas fa-chevron-left text-black"></i>
                            </button>
                            <span class="text-sm text-gray-600" x-text="`${currentPresetIndex + 1} / ${presets.length}`"></span>
                            <button type="button" 
                                    @click="nextPreset()"
                                    :disabled="currentPresetIndex === presets.length - 1"
                                    :class="currentPresetIndex === presets.length - 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                                    class="p-1 rounded transition-colors">
                                <i class="fas fa-chevron-right text-black"></i>
                            </button>
                        </div>
                        
                        <p x-show="saveMessage" 
                           :class="saveMessage.includes('Error') ? 'text-red-600' : 'text-green-600'" 
                           class="text-sm"
                           x-text="saveMessage"></p>
                    </div>
                    <button type="button" 
                            @click="showAddPreset = true; newPreset = {title: '', tasks: []}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        <i class="fas fa-plus mr-2"></i>
                        Add Preset
                    </button>
                </div>

                <!-- Single Preset Display with Navigation -->
                <div x-show="presets.length > 0" class="mb-6">
                    <template x-for="(preset, presetIndex) in presets" :key="presetIndex">
                        <div x-show="presetIndex === currentPresetIndex" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform translate-x-4"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform -translate-x-4"
                             class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900" x-text="preset.title"></h3>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="button" 
                                            @click.stop="editingPreset = presetIndex; newPreset = JSON.parse(JSON.stringify(preset)); showAddPreset = true"
                                            class="text-black hover:text-gray-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                            @click.stop="if(confirm('Are you sure you want to delete this preset?')) { 
                                                presets.splice(presetIndex, 1); 
                                                if (currentPresetIndex >= presets.length) currentPresetIndex = Math.max(0, presets.length - 1);
                                                savePresets(); 
                                            }"
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Task List Display -->
                            <div class="space-y-2">
                                <template x-for="(task, taskIndex) in preset.tasks" :key="taskIndex">
                                    <div class="flex items-center justify-between bg-white px-3 py-2 rounded border">
                                        <span class="text-sm" x-text="`${task.position || (taskIndex + 1)}. ${task.name}`"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Empty State -->
                <div x-show="presets.length === 0" class="text-center py-8 text-gray-500">
                    No presets available. Click "Add Preset" to create one.
                </div>

                <!-- Add/Edit Preset Modal -->
                <div x-show="showAddPreset" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                     style="display: none;">
                    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] flex flex-col">
                        <div class="flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900" 
                                    x-text="editingPreset !== null ? 'Edit Task Preset' : 'Add New Task Preset'"></h3>
                                <button @click.stop="showAddPreset = false; editingPreset = null" 
                                        type="button"
                                        class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="space-y-4 flex-1 flex flex-col overflow-hidden">
                                <!-- Country/Title Input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preset Heading</label>
                                    <input type="text" 
                                           x-model="newPreset.title"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                           placeholder="i.e. enter the steps that suits your workflow">
                                </div>

                                <!-- Tasks List -->
                                <div class="flex-1 flex flex-col overflow-hidden">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Tasks</label>
                                        <button type="button" 
                                                @click="newPreset.tasks.push({name: ''})"
                                                class="text-sm text-black hover:text-gray-800">
                                            <i class="fas fa-plus mr-1"></i>Add Task
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-2 flex-1 overflow-y-auto task-sortable pr-2" style="scrollbar-width: thin;">
                                        <template x-for="(task, taskIndex) in newPreset.tasks" :key="taskIndex">
                                            <div class="flex items-center space-x-2 px-2 py-1.5 border border-gray-200 rounded bg-gray-50 hover:bg-gray-100 transition-colors">
                                                <!-- Drag Handle -->
                                                <div class="cursor-move text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0">
                                                    <i class="fas fa-grip-vertical text-xs"></i>
                                                </div>
                                                
                                                <!-- Task Name -->
                                                <div class="flex-1 min-w-0">
                                                    <input type="text" 
                                                           x-model="task.name"
                                                           class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-black focus:border-black"
                                                           placeholder="Task name">
                                                </div>
                                                
                                                <!-- Remove Button -->
                                                <button type="button" 
                                                        @click.stop="newPreset.tasks.splice(taskIndex, 1); updateTaskPositions()"
                                                        class="text-red-600 hover:text-red-800 flex-shrink-0">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Actions -->
                            <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t">
                                <button type="button" 
                                        @click.stop="showAddPreset = false; editingPreset = null"
                                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" 
                                        @click.stop="
                                            if (editingPreset !== null) {
                                                updateTaskPositions();
                                                presets[editingPreset] = JSON.parse(JSON.stringify(newPreset));
                                            } else {
                                                updateTaskPositions();
                                                presets.push(JSON.parse(JSON.stringify(newPreset)));
                                            }
                                            savePresets();
                                            showAddPreset = false; 
                                            editingPreset = null;
                                        "
                                        :disabled="saving"
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!saving" x-text="editingPreset !== null ? 'Update' : 'Save'"></span>
                                    <span x-show="saving">Saving...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden input to store presets data -->
                <input type="hidden" name="task_presets" :value="JSON.stringify(presets)">
            </div>

            <!-- Role Management Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200" x-data="{
                roles: {{ json_encode($roles) }},
                editRole(role) {
                    window.roleManager.editRole(role);
                },
                deleteRole(roleId) {
                    window.roleManager.deleteRole(roleId);
                },
                init() {
                    this.$nextTick(() => {
                        this.initRoleSortable();
                    });
                },
                initRoleSortable() {
                    const $tbody = $('.roles-sortable');
                    
                    // Destroy existing sortable if it exists
                    if ($tbody.hasClass('ui-sortable')) {
                        $tbody.sortable('destroy');
                    }
                    
                    // Initialize jQuery UI sortable
                    $tbody.sortable({
                        handle: '.drag-handle',
                        axis: 'y',
                        cursor: 'move',
                        opacity: 0.7,
                        placeholder: 'bg-blue-50',
                        helper: function(e, tr) {
                            var $originals = tr.children();
                            var $helper = tr.clone();
                            $helper.children().each(function(index) {
                                $(this).width($originals.eq(index).width());
                            });
                            return $helper;
                        },
                        update: (event, ui) => {
                            // Get the new order from DOM
                            const newRoles = [];
                            $tbody.find('tr').each((index, row) => {
                                const roleId = $(row).data('role-id');
                                const role = this.roles.find(r => r.id == roleId);
                                if (role) {
                                    newRoles.push(role);
                                }
                            });
                            
                            // Update Alpine data
                            this.roles = newRoles;
                        }
                    });
                }
            }" x-init="window.roleManager = window.roleManager || {};">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Role Management</h2>
                {{-- <p class="text-gray-600 mb-6">Create and manage user roles with specific permissions</p> --}}

                <!-- Add Role Button -->
                <button @click="$dispatch('open-role-modal')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" style="background-color: #000;">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Role
                </button>
            </div>

                <!-- Roles Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 text-sm tracking-wider border-b w-12"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 text-sm tracking-wider border-b" style="color: #000; font-size: 0.85rem">Role Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 text-sm tracking-wider border-b" style="color: #000; font-size: 0.85rem">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 text-sm tracking-wider border-b" style="color: #000; font-size: 0.85rem">Permissions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 text-sm tracking-wider border-b" style="color: #000; font-size: 0.85rem">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 text-sm tracking-wider border-b" style="color: #000; font-size: 0.85rem">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 roles-sortable">
                            <template x-for="(role, index) in roles" :key="'role-' + role.id + '-' + index">
                                <tr class="hover:bg-gray-50 transition-colors" :data-role-id="role.id">
                                    <td class="px-3 py-4 border-b">
                                        <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 transition-colors">
                                            <i class="fas fa-grip-vertical"></i>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b" x-text="role.display_name"></td>
                                    <td class="px-6 py-4 text-sm text-gray-500 border-b" x-text="role.description"></td>
                                    <td class="px-6 py-4 text-sm text-gray-500 border-b">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800" x-text="role.permissions.length + ' permissions'" style="background: rgba(120, 145, 160, 0.5); color: #000;"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border-b">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                              :class="role.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                              x-text="role.is_active ? 'Active' : 'Inactive'" style="background: rgb(220, 183, 151); color: #000;"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b">
                                        <button @click.stop="editRole(role)" type="button" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit" style="color: #000"></i>
                                        </button>
                                        <button @click.stop="deleteRole(role.id)" type="button" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button style="margin-right: 1.6rem;" type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Save Settings
                </button>
            </div>
        </form>

        <!-- Role Management Modals (Outside main form) -->
        <div x-data="{
            showAddRoleModal: false,
            showEditRoleModal: false,
            editingRole: {
                id: null,
                display_name: '',
                description: '',
                permissions: []
            },
            roles: {{ json_encode($roles) }},
            permissions: {{ json_encode($permissions) }},
            permissionGroups: {{ json_encode($permissionGroups) }},
            newRole: {
                name: '',
                display_name: '',
                description: '',
                permissions: []
            },
            resetNewRole() {
                this.newRole = {
                    name: '',
                    display_name: '',
                    description: '',
                    permissions: []
                };
            },
            togglePermission(permissionId) {
                const index = this.newRole.permissions.indexOf(permissionId);
                if (index > -1) {
                    this.newRole.permissions.splice(index, 1);
                } else {
                    this.newRole.permissions.push(permissionId);
                }
            },
            editRole(role) {
                this.editingRole = { 
                    id: role.id,
                    display_name: role.display_name || '',
                    description: role.description || '',
                    permissions: role.permissions ? role.permissions.map(p => p.id) : []
                };
                this.showEditRoleModal = true;
            },
            resetEditingRole() {
                this.editingRole = {
                    id: null,
                    display_name: '',
                    description: '',
                    permissions: []
                };
            },
            async saveRole() {
                try {
                    console.log('Saving role:', this.newRole);
                    
                    this.newRole.name = this.newRole.display_name;
                    
                    console.log('Testing simple route...');
                    const testResponse = await fetch('/test-roles', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            test: 'data',
                            name: this.newRole.display_name
                        })
                    });
                    
                    const testResult = await testResponse.json();
                    console.log('Test route response:', testResult);
                    
                    const response = await fetch('/roles', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: this.newRole.display_name,
                            display_name: this.newRole.display_name,
                            description: this.newRole.description,
                            permissions: this.newRole.permissions
                        })
                    });
                    
                    console.log('Response status:', response.status);
                    console.log('Response ok:', response.ok);
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Response error text:', errorText);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    console.log('Response data:', result);
                    
                    if (result.success) {
                        this.roles.push(result.role);
                        this.resetNewRole();
                        this.showAddRoleModal = false;
                        alert('Role created successfully!');
                        location.reload();
                    } else {
                        alert('Error creating role: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error creating role:', error);
                    alert('Error creating role: ' + error.message);
                }
            },
            async updateRole() {
                try {
                    const response = await fetch(`/roles/${this.editingRole.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            display_name: this.editingRole.display_name,
                            description: this.editingRole.description,
                            permissions: this.editingRole.permissions
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        const index = this.roles.findIndex(r => r.id === this.editingRole.id);
                        if (index > -1) {
                            this.roles[index] = result.role;
                        }
                        this.showEditRoleModal = false;
                        this.resetEditingRole();
                        alert('Role updated successfully!');
                        location.reload();
                    } else {
                        alert('Error updating role: ' + result.message);
                    }
                } catch (error) {
                    alert('Error updating role: ' + error.message);
                }
            },
            async deleteRole(roleId) {
                if (!confirm('Are you sure you want to delete this role?')) return;
                
                try {
                    const response = await fetch(`/roles/${roleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.roles = this.roles.filter(r => r.id !== roleId);
                        alert('Role deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting role: ' + result.message);
                    }
                } catch (error) {
                    alert('Error deleting role: ' + error.message);
                }
            }
        }" 
        @open-role-modal.window="showAddRoleModal = true"
        @edit-role.window="editRole($event.detail)"
        @delete-role.window="deleteRole($event.detail)"
        x-init="
            window.roleManager = {
                editRole: (role) => $dispatch('edit-role', role),
                deleteRole: (roleId) => $dispatch('delete-role', roleId)
            };
        ">

            <!-- Add Role Modal -->
            <div x-show="showAddRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Role</h3>
                        
                        <div class="space-y-4">
                            <!-- Role Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role Name</label>
                                <input type="text" x-model="newRole.display_name" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea x-model="newRole.description" rows="3" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"></textarea>
                            </div>

                            <!-- Permissions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                                <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3">
                                    <template x-for="(groupPermissions, groupName) in permissionGroups" :key="groupName">
                                        <div class="mb-4">
                                            <h4 class="font-medium text-gray-900 mb-2 capitalize" x-text="groupName.replace('_', ' ')"></h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <template x-for="permission in groupPermissions" :key="permission.id">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" 
                                                               :value="permission.id" 
                                                               @change="togglePermission(permission.id)"
                                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="ml-2 text-sm text-gray-700" x-text="permission.display_name"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button @click.stop="showAddRoleModal = false; resetNewRole()" type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                            <button @click.stop="console.log('Create Role button clicked'); saveRole()" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Role</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Role Modal -->
            <div x-show="showEditRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Role</h3>
                        
                        <div class="space-y-4">
                            <!-- Role Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role Name</label>
                                <input type="text" x-model="editingRole.display_name" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea x-model="editingRole.description" rows="3" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"></textarea>
                            </div>

                            <!-- Permissions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                                <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3">
                                    <template x-for="(groupPermissions, groupName) in permissionGroups" :key="groupName">
                                        <div class="mb-4">
                                            <h4 class="font-medium text-gray-900 mb-2 capitalize" x-text="groupName.replace('_', ' ')"></h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <template x-for="permission in groupPermissions" :key="permission.id">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" 
                                                               :value="permission.id" 
                                                               :checked="editingRole && editingRole.permissions.includes(permission.id)"
                                                               @change="
                                                                   const index = editingRole.permissions.indexOf(permission.id);
                                                                   if (index > -1) {
                                                                       editingRole.permissions.splice(index, 1);
                                                                   } else {
                                                                       editingRole.permissions.push(permission.id);
                                                                   }
                                                               "
                                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="ml-2 text-sm text-gray-700" x-text="permission.display_name"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button @click.stop="showEditRoleModal = false; resetEditingRole()" type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                            <button @click.stop="updateRole()" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Role</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sortable.js for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <style>
        .cursor-move {
            cursor: move;
        }
        
        .cursor-move:hover {
            cursor: grab;
        }
        
        .cursor-move:active {
            cursor: grabbing;
        }
        
        .task-sortable .sortable-ghost {
            opacity: 0.4;
        }
        
        .task-sortable .sortable-chosen {
            background-color: rgba(0, 0, 0, 0.1) !important;
        }
        
        /* Roles table sortable styles */
        .roles-sortable tr {
            transition: background-color 0.2s ease;
        }
        
        .roles-sortable .sortable-ghost {
            opacity: 0.4;
        }
        
        .roles-sortable .sortable-chosen {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }
        
        .roles-sortable .sortable-drag {
            opacity: 0.8;
        }
        
        .task-sortable .sortable-drag {
            opacity: 0.6;
        }
        
        /* Custom scrollbar for modal */
        .task-sortable::-webkit-scrollbar {
            width: 6px;
        }
        
        .task-sortable::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .task-sortable::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .task-sortable::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    
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
</body>
</html>
