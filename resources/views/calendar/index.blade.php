<x-app-layout>
    <x-slot name="title">Calendar</x-slot>

    <!-- Calendar Page -->
    <div class="py-6 px-4 sm:ml-64 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="calendarApp()">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Calendar
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Manage your events and appointments</p>
                </div>
                
                <!-- Calendar Controls -->
                <div class="flex flex-wrap items-center gap-3">
                    <!-- View Controls -->
                    <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button @click="changeView('dayGridMonth')" 
                                :class="currentView === 'dayGridMonth' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                                class="px-3 py-1 text-sm font-medium rounded-md transition-all duration-200">
                            Month
                        </button>
                        <button @click="changeView('timeGridWeek')" 
                                :class="currentView === 'timeGridWeek' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                                class="px-3 py-1 text-sm font-medium rounded-md transition-all duration-200">
                            Week
                        </button>
                        <button @click="changeView('timeGridDay')" 
                                :class="currentView === 'timeGridDay' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                                class="px-3 py-1 text-sm font-medium rounded-md transition-all duration-200">
                            Day
                        </button>
                        <button @click="changeView('listWeek')" 
                                :class="currentView === 'listWeek' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                                class="px-3 py-1 text-sm font-medium rounded-md transition-all duration-200">
                            List
                        </button>
                    </div>

                    <!-- Filter -->
                    <select x-model="statusFilter" @change="applyFilters()" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Events</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <!-- Add Event Button -->
                    <button @click="openEventModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Event
                    </button>
                </div>
            </div>
        </div>

        <!-- Calendar Widget -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div id="calendar" class="p-4"></div>
        </div>

        <!-- Event Modal -->
        <div x-show="showEventModal" 
             x-cloak
             @click.away="closeEventModal()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            
            <div @click.stop 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="modalTitle"></h3>
                    <button @click="closeEventModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="saveEvent()" class="p-6 space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                        <input type="text" 
                               x-model="eventForm.title" 
                               required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="Event title">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea x-model="eventForm.description" 
                                  rows="3"
                                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                                  placeholder="Event description (optional)"></textarea>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date & Time *</label>
                            <input type="datetime-local" 
                                   x-model="eventForm.start" 
                                   required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date & Time *</label>
                            <input type="datetime-local" 
                                   x-model="eventForm.end" 
                                   required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- All Day Toggle -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               x-model="eventForm.all_day" 
                               id="all-day"
                               class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                        <label for="all-day" class="ml-2 text-sm text-gray-700 dark:text-gray-300">All Day Event</label>
                    </div>

                    <!-- Type & Color -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type *</label>
                            <select x-model="eventForm.type" 
                                    required
                                    @change="updateColor()"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="meeting">Meeting</option>
                                <option value="task">Task</option>
                                <option value="appointment">Appointment</option>
                                <option value="reminder">Reminder</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
                            <input type="color" 
                                   x-model="eventForm.color"
                                   class="w-full h-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                        </div>
                    </div>

                    <!-- Status (for editing) -->
                    <div x-show="isEditing">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select x-model="eventForm.status" 
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                @click="closeEventModal()" 
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 mb-2 sm:mb-0">
                            Cancel
                        </button>
                        
                        <button x-show="isEditing" 
                                type="button"
                                @click="deleteEvent()" 
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200 mb-2 sm:mb-0 sm:mr-2">
                            Delete
                        </button>
                        
                        <button type="submit" 
                                :disabled="loading"
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <span x-show="!loading" x-text="isEditing ? 'Update Event' : 'Create Event'"></span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Event Details Tooltip (shown on hover) -->
        <div id="event-tooltip" 
             class="absolute z-50 invisible bg-gray-900 text-white text-sm rounded-lg shadow-lg p-3 max-w-xs"
             style="pointer-events: none;">
            <div id="tooltip-content"></div>
            <div class="tooltip-arrow"></div>
        </div>
    </div>

    <!-- Include FullCalendar CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <style>
        /* Custom Calendar Styles */
        .fc {
            font-family: inherit;
        }
        
        .fc-theme-standard .fc-popover {
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .fc-event {
            border: none;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
        }
        
        .fc-event:hover {
            filter: brightness(110%);
        }
        
        .fc-toolbar {
            margin-bottom: 1rem;
        }
        
        .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: inherit;
        }
        
        .fc-button-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        
        .fc-button-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        
        /* Dark mode styles */
        .dark .fc-theme-standard td,
        .dark .fc-theme-standard th {
            border-color: #374151;
        }
        
        .dark .fc-theme-standard .fc-scrollgrid {
            border-color: #374151;
        }
        
        .dark .fc-col-header-cell-cushion {
            color: #d1d5db;
        }
        
        .dark .fc-daygrid-day-number {
            color: #9ca3af;
        }
        
        .dark .fc-day-today {
            background-color: rgba(79, 70, 229, 0.1) !important;
        }
        
        /* Tooltip styles */
        .tooltip-arrow {
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #111827;
        }
    </style>

    <script>
        function calendarApp() {
            return {
                calendar: null,
                currentView: 'dayGridMonth',
                statusFilter: '',
                showEventModal: false,
                isEditing: false,
                loading: false,
                
                eventForm: {
                    id: null,
                    title: '',
                    description: '',
                    start: '',
                    end: '',
                    type: 'meeting',
                    status: 'pending',
                    color: '#4f46e5',
                    all_day: false
                },

                get modalTitle() {
                    return this.isEditing ? 'Edit Event' : 'Add New Event';
                },

                init() {
                    this.initializeCalendar();
                },

                initializeCalendar() {
                    const calendarEl = document.getElementById('calendar');
                    
                    this.calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: ''
                        },
                        events: {
                            url: '{{ route("calendar.events") }}',
                            failure: (error) => {
                                console.error('Failed to load events:', error);
                                this.showToast('Failed to load events', 'error');
                            }
                        },
                        editable: true,
                        selectable: true,
                        selectMirror: true,
                        dayMaxEvents: true,
                        weekends: true,
                        height: 'auto',
                        
                        // Event interactions
                        select: (info) => {
                            this.openEventModal(info.start, info.end);
                        },
                        
                        eventClick: (info) => {
                            this.editEvent(info.event);
                        },
                        
                        eventDrop: (info) => {
                            this.moveEvent(info.event);
                        },
                        
                        eventResize: (info) => {
                            this.moveEvent(info.event);
                        },
                        
                        // Event rendering
                        eventDidMount: (info) => {
                            // Add tooltip
                            info.el.addEventListener('mouseenter', (e) => {
                                this.showTooltip(e, info.event);
                            });
                            
                            info.el.addEventListener('mouseleave', () => {
                                this.hideTooltip();
                            });
                        }
                    });
                    
                    this.calendar.render();
                },

                changeView(viewName) {
                    this.currentView = viewName;
                    this.calendar.changeView(viewName);
                },

                applyFilters() {
                    const currentEvents = this.calendar.getEvents();
                    
                    currentEvents.forEach(event => {
                        const status = event.extendedProps.status;
                        const shouldShow = !this.statusFilter || status === this.statusFilter;
                        event.setProp('display', shouldShow ? 'auto' : 'none');
                    });
                },

                openEventModal(start = null, end = null) {
                    this.isEditing = false;
                    this.eventForm = {
                        id: null,
                        title: '',
                        description: '',
                        start: start ? this.formatDateTime(start) : '',
                        end: end ? this.formatDateTime(end) : '',
                        type: 'meeting',
                        status: 'pending',
                        color: '#4f46e5',
                        all_day: false
                    };
                    this.showEventModal = true;
                },

                editEvent(event) {
                    this.isEditing = true;
                    this.eventForm = {
                        id: event.id,
                        title: event.title,
                        description: event.extendedProps.description || '',
                        start: this.formatDateTime(event.start),
                        end: this.formatDateTime(event.end),
                        type: event.extendedProps.type,
                        status: event.extendedProps.status,
                        color: event.backgroundColor,
                        all_day: event.allDay
                    };
                    this.showEventModal = true;
                },

                closeEventModal() {
                    this.showEventModal = false;
                    this.isEditing = false;
                    this.loading = false;
                },

                async saveEvent() {
                    this.loading = true;
                    
                    try {
                        const url = this.isEditing 
                            ? `{{ url('calendar/events') }}/${this.eventForm.id}`
                            : '{{ route("calendar.events.store") }}';
                            
                        const method = this.isEditing ? 'PUT' : 'POST';
                        
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.eventForm)
                        });

                        if (!response.ok) {
                            throw new Error('Failed to save event');
                        }

                        const data = await response.json();
                        
                        // Refresh calendar events
                        this.calendar.refetchEvents();
                        
                        this.showToast(
                            this.isEditing ? 'Event updated successfully' : 'Event created successfully',
                            'success'
                        );
                        
                        this.closeEventModal();
                        
                    } catch (error) {
                        console.error('Error saving event:', error);
                        this.showToast('Failed to save event', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async deleteEvent() {
                    if (!confirm('Are you sure you want to delete this event?')) {
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        const response = await fetch(`{{ url('calendar/events') }}/${this.eventForm.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Failed to delete event');
                        }

                        // Refresh calendar events
                        this.calendar.refetchEvents();
                        
                        this.showToast('Event deleted successfully', 'success');
                        this.closeEventModal();
                        
                    } catch (error) {
                        console.error('Error deleting event:', error);
                        this.showToast('Failed to delete event', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async moveEvent(event) {
                    try {
                        const response = await fetch(`{{ url('calendar/events') }}/${event.id}/move`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                start: event.start.toISOString(),
                                end: event.end.toISOString()
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Failed to move event');
                        }

                        this.showToast('Event moved successfully', 'success');
                        
                    } catch (error) {
                        console.error('Error moving event:', error);
                        this.showToast('Failed to move event', 'error');
                        // Revert the event position
                        event.revert();
                    }
                },

                updateColor() {
                    const colorMap = {
                        'meeting': '#4f46e5',
                        'task': '#06b6d4', 
                        'appointment': '#10b981',
                        'reminder': '#f59e0b',
                        'other': '#6b7280'
                    };
                    
                    this.eventForm.color = colorMap[this.eventForm.type] || '#4f46e5';
                },

                formatDateTime(date) {
                    if (!date) return '';
                    
                    const d = new Date(date);
                    return d.toISOString().slice(0, 16);
                },

                showTooltip(e, event) {
                    const tooltip = document.getElementById('event-tooltip');
                    const content = document.getElementById('tooltip-content');
                    
                    const startTime = event.start.toLocaleString();
                    const endTime = event.end ? event.end.toLocaleString() : '';
                    const description = event.extendedProps.description || 'No description';
                    const creator = event.extendedProps.creator || 'Unknown';
                    const status = event.extendedProps.status || 'pending';
                    
                    content.innerHTML = `
                        <div class="font-semibold">${event.title}</div>
                        <div class="text-xs text-gray-300 mt-1">
                            <div>📅 ${startTime}${endTime ? ' - ' + endTime : ''}</div>
                            <div>👤 ${creator}</div>
                            <div>📊 ${status.charAt(0).toUpperCase() + status.slice(1)}</div>
                            ${description !== 'No description' ? `<div class="mt-2">${description}</div>` : ''}
                        </div>
                    `;
                    
                    tooltip.style.left = e.pageX + 'px';
                    tooltip.style.top = (e.pageY - tooltip.offsetHeight - 10) + 'px';
                    tooltip.classList.remove('invisible');
                },

                hideTooltip() {
                    const tooltip = document.getElementById('event-tooltip');
                    tooltip.classList.add('invisible');
                },

                showToast(message, type = 'success') {
                    if (window.showToast) {
                        window.showToast(message, type);
                    } else {
                        alert(message);
                    }
                }
            }
        }
    </script>
</x-app-layout>
