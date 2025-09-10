
<x-app-layout>
    <div class="bg-gray-50 min-h-screen" x-data="calendarApp()" x-init="init()">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">📅 Calendar</h1>
                        <p class="text-gray-600 mt-1">Manage your events and schedule</p>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Add Event Button -->
                        <button @click="openEventModal()"
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                            <i class="fas fa-plus mr-2"></i>
                            Add Event
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Navigation -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button @click="navigateCalendar('prev')" 
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </button>
                    <button @click="navigateCalendar('today')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                        Today
                    </button>
                    <button @click="navigateCalendar('next')" 
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-900 ml-4" x-text="currentTitle"></h2>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button @click="changeView('dayGridMonth')" 
                        :class="currentView === 'dayGridMonth' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-2 rounded-lg font-medium transition-colors">
                        Month
                    </button>
                    <button @click="changeView('timeGridWeek')" 
                        :class="currentView === 'timeGridWeek' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-2 rounded-lg font-medium transition-colors">
                        Week
                    </button>
                    <button @click="changeView('timeGridDay')" 
                        :class="currentView === 'timeGridDay' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-2 rounded-lg font-medium transition-colors">
                        Day
                    </button>
                </div>
            </div>
        </div>

        <!-- Calendar Container -->
        <div class="p-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Event Modal -->
        <div x-show="showEventModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="closeEventModal()"></div>
                
                <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900" x-text="modalTitle"></h3>
                        <button @click="closeEventModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Modal Content -->
                    <div class="px-6 py-6">
                        <form @submit.prevent="saveEvent()" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                                <input type="text" x-model="eventForm.title" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea x-model="eventForm.description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                    <input type="date" x-model="eventForm.start_date" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" x-model="eventForm.end_date" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                    <input type="time" x-model="eventForm.start_time"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                    <input type="time" x-model="eventForm.end_time"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                    <select x-model="eventForm.type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="meeting">Meeting</option>
                                        <option value="task">Task</option>
                                        <option value="event">Event</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select x-model="eventForm.status"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="pending">Pending</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                <div class="flex items-center space-x-2">
                                    <template x-for="color in eventColors" :key="color.value">
                                        <button type="button" @click="eventForm.color = color.value"
                                            :class="[color.class, eventForm.color === color.value ? 'ring-2 ring-gray-800' : '']"
                                            class="w-8 h-8 rounded-full"></button>
                                    </template>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                        <div>
                            <button x-show="isEditing" @click="deleteEvent()" type="button"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                Delete Event
                            </button>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button @click="closeEventModal()" type="button"
                                class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button @click="saveEvent()" type="button" :disabled="loading"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50">
                                <span x-show="!loading">Save Event</span>
                                <span x-show="loading">Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tooltip -->
        <div x-show="showTooltip" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95" 
             class="fixed z-50 pointer-events-none"
             :style="`left: ${tooltipPosition.x}px; top: ${tooltipPosition.y}px;`"
             style="display: none;">
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-3 max-w-xs">
                <div class="space-y-2">
                    <div class="flex items-start space-x-2">
                        <div class="w-3 h-3 rounded-full mt-1 flex-shrink-0" 
                             :style="`background-color: ${tooltipEvent?.color || '#3b82f6'}`"></div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900" x-text="tooltipEvent?.title || 'Untitled Event'"></h4>
                        </div>
                    </div>
                    
                    <div x-show="tooltipEvent?.description" class="text-xs text-gray-600" x-text="tooltipEvent?.description"></div>
                    
                    <div class="text-xs text-gray-500 space-y-1">
                        <div>Start: <span x-text="tooltipEvent?.start || 'Not set'"></span></div>
                        <div x-show="tooltipEvent?.end">End: <span x-text="tooltipEvent?.end"></span></div>
                    </div>
                    
                    <div class="text-xs text-gray-400 italic">Click to edit</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        function calendarApp() {
            return {
                // Core properties
                calendar: null,
                currentView: 'dayGridMonth',
                currentTitle: '',
                loading: false,

                // Modal states
                showEventModal: false,
                isEditing: false,
                modalTitle: 'Add New Event',

                // Tooltip properties
                showTooltip: false,
                tooltipEvent: null,
                tooltipPosition: { x: 0, y: 0 },

                // Event form
                eventForm: {
                    id: null,
                    title: '',
                    description: '',
                    start_date: '',
                    start_time: '',
                    end_date: '',
                    end_time: '',
                    type: 'other',
                    status: 'pending',
                    color: '#3b82f6'
                },

                // Color options
                eventColors: [
                    { value: '#3b82f6', class: 'bg-blue-500' },
                    { value: '#10b981', class: 'bg-green-500' },
                    { value: '#f59e0b', class: 'bg-yellow-500' },
                    { value: '#ef4444', class: 'bg-red-500' },
                    { value: '#8b5cf6', class: 'bg-purple-500' },
                    { value: '#06b6d4', class: 'bg-cyan-500' }
                ],

                async init() {
                    console.log('Calendar app initialized');
                    this.initCalendar();
                },

                initCalendar() {
                    const calendarEl = document.getElementById('calendar');
                    this.calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: false,
                        height: 'auto',
                        events: '{{ route("calendar.events") }}',
                        selectable: true,
                        selectMirror: true,
                        dayMaxEvents: true,
                        weekends: true,
                        editable: true,
                        eventResizableFromStart: true,
                        eventDurationEditable: true,
                        
                        select: (selectInfo) => {
                            this.openEventModal(selectInfo.startStr);
                        },
                        
                        eventClick: (clickInfo) => {
                            this.editEvent(clickInfo.event);
                        },
                        
                        eventMouseEnter: (info) => {
                            console.log('Mouse enter event:', info);
                            this.showEventTooltip(info);
                        },
                        
                        eventMouseLeave: () => {
                            console.log('Mouse leave event');
                            this.hideEventTooltip();
                        },
                        
                        datesSet: (dateInfo) => {
                            this.currentTitle = dateInfo.view.title;
                        }
                    });
                    
                    this.calendar.render();
                    this.currentTitle = this.calendar.view.title;
                    console.log('Calendar rendered successfully');
                },

                showEventTooltip(info) {
                    console.log('Showing tooltip for event:', info.event.title);
                    
                    const event = info.event;
                    
                    // Format dates
                    let startFormatted = 'Not set';
                    let endFormatted = '';
                    
                    if (event.start) {
                        startFormatted = event.start.toLocaleDateString('en-US', { 
                            weekday: 'short', 
                            month: 'short', 
                            day: 'numeric' 
                        });
                        
                        if (!event.allDay) {
                            startFormatted += ' ' + event.start.toLocaleTimeString('en-US', { 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });
                        }
                    }
                    
                    if (event.end) {
                        endFormatted = event.end.toLocaleDateString('en-US', { 
                            weekday: 'short', 
                            month: 'short', 
                            day: 'numeric' 
                        });
                        
                        if (!event.allDay) {
                            endFormatted += ' ' + event.end.toLocaleTimeString('en-US', { 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });
                        }
                    }
                    
                    this.tooltipEvent = {
                        title: event.title || 'Untitled Event',
                        description: event.extendedProps?.description || '',
                        start: startFormatted,
                        end: endFormatted,
                        color: event.backgroundColor || event.borderColor || '#3b82f6'
                    };

                    // Position tooltip closer to the event element
                    const eventRect = info.el.getBoundingClientRect();
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
                    
                    // Position tooltip to the right of the event, or left if not enough space
                    let x = eventRect.right + scrollLeft + 10;
                    let y = eventRect.top + scrollTop;
                    
                    // Check if tooltip would go off screen and adjust
                    if (x + 300 > window.innerWidth) {
                        x = eventRect.left + scrollLeft - 310; // Show on left side
                    }
                    
                    // Make sure tooltip doesn't go above viewport
                    if (y < scrollTop + 10) {
                        y = scrollTop + 10;
                    }
                    
                    this.tooltipPosition = { x, y };
                    
                    this.showTooltip = true;
                    console.log('Tooltip should be visible now');
                },

                hideEventTooltip() {
                    console.log('Hiding tooltip');
                    this.showTooltip = false;
                    this.tooltipEvent = null;
                },

                changeView(viewType) {
                    this.currentView = viewType;
                    this.calendar.changeView(viewType);
                },

                navigateCalendar(direction) {
                    if (direction === 'prev') {
                        this.calendar.prev();
                    } else if (direction === 'next') {
                        this.calendar.next();
                    } else if (direction === 'today') {
                        this.calendar.today();
                    }
                },

                openEventModal(date = null) {
                    this.isEditing = false;
                    this.modalTitle = 'Add New Event';
                    this.resetEventForm();
                    if (date) {
                        this.eventForm.start_date = date;
                        this.eventForm.end_date = date;
                    } else {
                        const today = new Date().toISOString().split('T')[0];
                        this.eventForm.start_date = today;
                        this.eventForm.end_date = today;
                    }
                    this.showEventModal = true;
                },

                async editEvent(event) {
                    try {
                        this.loading = true;
                        
                        // Fetch fresh event data from database
                        const response = await fetch(`{{ url('ims/calendar/events') }}/${event.id}`);
                        if (!response.ok) {
                            throw new Error('Failed to fetch event data');
                        }
                        
                        const eventData = await response.json();
                        
                        this.isEditing = true;
                        this.modalTitle = 'Edit Event';
                        
                        // Use fresh data from database with proper date handling
                        const startDate = new Date(eventData.start);
                        const endDate = eventData.end ? new Date(eventData.end) : null;
                        
                        this.eventForm = {
                            id: eventData.id,
                            title: eventData.title || '',
                            description: eventData.extendedProps?.description || '',
                            start_date: startDate.toISOString().split('T')[0],
                            start_time: eventData.allDay ? '' : startDate.toTimeString().substring(0, 5),
                            end_date: endDate ? endDate.toISOString().split('T')[0] : startDate.toISOString().split('T')[0],
                            end_time: (endDate && !eventData.allDay) ? endDate.toTimeString().substring(0, 5) : '',
                            type: eventData.extendedProps?.type || 'other',
                            status: eventData.extendedProps?.status || 'pending',
                            color: eventData.backgroundColor || '#3b82f6'
                        };
                        
                        this.showEventModal = true;
                        
                    } catch (error) {
                        console.error('Error fetching event data:', error);
                        this.showNotification('Failed to load event data. Please try again.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async saveEvent() {
                    try {
                        this.loading = true;
                        
                        const eventData = {
                            title: this.eventForm.title,
                            description: this.eventForm.description,
                            start: this.eventForm.start_date + (this.eventForm.start_time ? 'T' + this.eventForm.start_time + ':00' : 'T00:00:00'),
                            end: this.eventForm.end_date + (this.eventForm.end_time ? 'T' + this.eventForm.end_time + ':00' : 'T23:59:59'),
                            type: this.eventForm.type,
                            color: this.eventForm.color,
                            status: this.eventForm.status,
                            all_day: !this.eventForm.start_time && !this.eventForm.end_time
                        };

                        let response;
                        
                        if (this.isEditing) {
                            response = await fetch(`{{ url('ims/calendar/events') }}/${this.eventForm.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(eventData)
                            });
                        } else {
                            response = await fetch('{{ route("calendar.events.store") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(eventData)
                            });
                        }

                        if (response.ok) {
                            this.calendar.refetchEvents();
                            this.closeEventModal();
                            this.showNotification('Event saved successfully!', 'success');
                        } else {
                            throw new Error('Failed to save event');
                        }
                        
                    } catch (error) {
                        console.error('Error saving event:', error);
                        this.showNotification('Failed to save event. Please try again.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async deleteEvent() {
                    if (!confirm('Are you sure you want to delete this event?')) {
                        return;
                    }

                    try {
                        this.loading = true;
                        
                        const response = await fetch(`{{ url('ims/calendar/events') }}/${this.eventForm.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            this.calendar.refetchEvents();
                            this.closeEventModal();
                            this.showNotification('Event deleted successfully!', 'success');
                        } else {
                            throw new Error('Failed to delete event');
                        }
                        
                    } catch (error) {
                        console.error('Error deleting event:', error);
                        this.showNotification('Failed to delete event. Please try again.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                closeEventModal() {
                    this.showEventModal = false;
                    this.resetEventForm();
                },

                resetEventForm() {
                    this.eventForm = {
                        id: null,
                        title: '',
                        description: '',
                        start_date: '',
                        start_time: '',
                        end_date: '',
                        end_time: '',
                        type: 'other',
                        status: 'pending',
                        color: '#3b82f6'
                    };
                },

                showNotification(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                        type === 'success' ? 'bg-green-500 text-white' : 
                        type === 'error' ? 'bg-red-500 text-white' : 
                        'bg-blue-500 text-white'
                    }`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.classList.remove('translate-x-full');
                    }, 100);
                    
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => {
                            if (document.body.contains(notification)) {
                                document.body.removeChild(notification);
                            }
                        }, 300);
                    }, 3000);
                }
            }
        }
    </script>
</x-app-layout>
