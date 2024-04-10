<template>
    <div>
        <full-calendar ref="calendar" class="calendar" :options="calendarOptions" />
        <div class="modal fade" tabindex="-1" role="dialog" id="remove-event-modal">
            <div class="modal-dialog" role="document">
                <input type="hidden" id="selectedItemId" />
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Event</h5>
                    </div>
                    <div class="modal-body">
                        <p>Do you want to delete this event ?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="onDeleteEventClicked">Yes</button>
                        <button type="button" class="btn btn-secondary"
                            onclick="$('#remove-event-modal').modal('hide')">No</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="add-event-modal">
            <div class="modal-dialog" role="document">
                <input type="hidden" id="selectedItemId" />
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">From {{ formatDate(selectedEventDate.start) }} To {{
            formatDate(selectedEventDate.end) }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="types" class="form-control-label ">Type</label>
                                    <select class="form-control" v-model="selectedType"
                                        @change="() => selectedItem = defaultSelectedItem">
                                        <option>{{ defaultSelectedType }}</option>
                                        <option v-bind:value="'meal'">Meal</option>
                                        <option v-bind:value="'exercise'">Exercise</option>

                                    </select>
                                    <div class="invalid-feedback">
                                        Please select an option.
                                    </div>
                                </div>
                            </div>
                            <div v-if="selectedType == 'meal'" class="col-md-6" id="meal-container">
                                <div class="form-group">
                                    <label for="meals" class="form-control-label ">Meal</label>
                                    <select class="form-control" v-model="selectedItem">
                                        <option>{{ defaultSelectedItem }}</option>
                                        <option v-for="(meal, index) in meals" :key="index" v-bind:value="meal.id">{{
            meal.name }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select an option.
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="selectedType == 'exercise'" class="col-md-6" id="exercise-container">
                                <div class="form-group">
                                    <label for="exercises" class="form-control-label">Exercise</label>
                                    <select class="form-control" v-model="selectedItem">
                                        <option data-id="0">{{ defaultSelectedItem }}</option>
                                        <option v-for="(exercise, index) in exercises" :key="index"
                                            v-bind:value="exercise.id">{{ exercise.name }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select an option.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button :disabled="selectedType == defaultSelectedType || selectedItem == defaultSelectedItem"
                            type="button" class="btn btn-primary" @click="addNewTimelineItem">Add</button>
                        <button type="button" class="btn btn-secondary" @click="cancelAddModal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import timeGridPlugin from '@fullcalendar/timegrid'
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling
import 'tippy.js/animations/scale.css';
import listPlugin from '@fullcalendar/list';
export default {
    props: ['items', 'meals', 'exercises', 'timeline_id'],
    beforeMount() {
        this.items.forEach((item) => {
            let title = `${item.item.name}`;
            this.calendarOptions.events.push({
                title: title,
                start: item.event_date_start,
                end: item.event_date_end,
                status: item.item.status,
                item_id: item.id,
                item_type: item.item.type.name,
                type: item.item_type.split("\\").pop(),
            });
        });
    },
    components: {
        FullCalendar // make the <FullCalendar> tag available
    },
    data() {
        return {
            calendarOptions: {

                plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin],
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                views: {
                    timeGridFourDay: {
                        type: 'timeGrid',
                        duration: { days: 4 },
                        buttonText: '4 day'
                    }
                },
                droppable: true,
                editable: true,
                selectable: true,
                // allDaySlot: false,
                slotEventOverlap: false,
                eventStartEditable: true,
                eventResizableFromStart: true,
                nowIndicator: true,
                navLinks: true,
                eventTimeFormat: { // like '14:30:00'
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                businessHours: {
                    daysOfWeek: [0, 1, 2, 3, 4], // Monday - Thursday
                    startTime: '08:00', // a start time (10am in this example)
                    endTime: '22:00', // an end time (6pm in this example)
                },
                eventDrop: this.handleEventDrop,
                eventResize: this.handleEventResize,
                eventClick: this.handleEventClick,
                events: [],
                select: this.hadleDateSelect,
                eventClassNames: (arg) => [arg.event.extendedProps.status,
                arg.event.extendedProps.type
                ],
                eventDidMount: function (info) {
                    var tooltip = new tippy(info.el, {
                        content: `Type : ${info.event.extendedProps.item_type}`,
                        placement: 'top',
                        trigger: 'click',
                        animation: 'scale',
                    });
                    $(".bx-message-square-x").on('click', function (el) {
                        console.log($(this).attr('data-id'));
                        let id = $(this).attr('data-id')
                        $("#selectedItemId").val(id)
                        tooltip.disable()

                        $("#remove-event-modal").modal('show')
                        $('.modal-backdrop').remove();
                        setTimeout(() => {
                            tooltip.enable()
                        }, 1000);
                    })
                },
                eventContent: function (info) {
                    console.log(info);
                    // Customize the event HTML here
                    return {
                        html:
                            `<div class="fc-event-main">
                                <div class="fc-event-main-frame">
                                    <div class="fc-event-time">
                                    <i class='mx-1 bx bx-message-square-x' data-id="${info.event.extendedProps.item_id}"
                                    ></i>
                                    ${info.timeText}    
                                    </div>
                                    <div class="fc-event-title-container">
                                        ${info.event.title}
                                        <div class="fc-event-title fc-sticky">
                                        </div>
                                    </div>
                                </div>
                            </div>`
                    };
                },
            },
            selectedEventDate: {},
            defaultSelectedType: "Please Select a Type",
            defaultSelectedItem: "Please Select an Item",

            selectedType: "Please Select a Type",
            selectedItem: "Please Select an Item",
        }
    },
    methods: {
        // add new event
        hadleDateSelect: function (selectionInfo) {
            if (['timeGridWeek', 'timeGridDay'].includes(selectionInfo.view.type)) {
                console.log('date selected! ')
                console.log(selectionInfo)
                this.selectedEventDate = selectionInfo;
                $("#add-event-modal").modal('show')
                $('.modal-backdrop').remove();
            } else {
                console.log(selectionInfo);
            }
        },
        // update event
        handleEventDrop: function (eventDropInfo) {
            let delta = eventDropInfo.delta;
            let item_id = eventDropInfo.event._def.extendedProps.item_id;
            axios.post("/coach/timelines.items/update", {
                id: item_id,
                delta: delta,
                method: 'drop'
            })
        },
        handleEventResize: function (info) {
            let start = new Date(info.event.start).toLocaleString('en-US', { timeZone: 'Asia/Damascus' });
            let end = new Date(info.event.end).toLocaleString('en-US', { timeZone: 'Asia/Damascus' });
            let item_id = info.event._def.extendedProps.item_id;
            axios.post("/coach/timelines.items/update", {
                id: item_id,
                event_date_start: start,
                event_date_end: end,
                method: 'resize'
            })
        },
        handleEventClick: function (mouseEnterInfo) {
            console.log(mouseEnterInfo);
        },
        onDeleteEventClicked: async function () {
            let id = $("#selectedItemId").val();
            let response = await axios.post('/coach/timelines.items/delete', {
                id: id
            })
            $("#remove-event-modal").modal('hide')
            if (response.status == 200) {
                this.$refs.calendar.getApi().getEvents().forEach((event) => {
                    if (event.extendedProps.item_id == id) {
                        event.remove()
                    }
                })
            }
        },
        formatDate(date) {
            if (date == null || date == undefined)
                return '';
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // January is 0
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}`;
        },
        addNewTimelineItem() {
            axios.post('/coach/timelines.items/new', {
                timeline_id: this.timeline_id,
                item_id: this.selectedItem,
                item_type: this.selectedType,
                dates: {
                    start: this.selectedEventDate.start.getTime(),
                    end: this.selectedEventDate.end.getTime(),
                },
            },).then((response) => {
                // Handle the success response
                console.log('Form submitted successfully');
                $('#add-event-modal').modal('hide');
                console.log(response);
                this.selectedItem = this.defaultSelectedItem;
                this.selectedType = this.defaultSelectedType;
                // $('#store-new-timeline-item-form').get(0).reset();
                let data = response.data;
                let code = data.code;
                if (code == 200) {
                    let item = data.item;
                    this.calendarOptions.events.push({
                        title: item.item.name,
                        start: item.event_date_start,
                        end: item.event_date_end,
                        status: item.item.status,
                        item_id: item.id,
                        item_type: item.item.type.name,
                        type: item.item_type.split("\\").pop(),
                    });
                }
            }).catch(console.error);
        },
        cancelAddModal() {
            $('#add-event-modal').modal('hide');
            this.selectedItem = this.defaultSelectedItem;
            this.selectedType = this.defaultSelectedType;
        }
    }
}
</script>

<style>
.calendar {
    height: 460px;
}

.fc-event-time,
.fc-event-title-container {
    color: white !important;
}

.fc-event-time {
    margin-bottom: 1px;
    white-space: nowrap;
}

.fc-event-title-container {
    flex-grow: 1;
    flex-shrink: 1;
    min-height: 0px;
}

.fc-event-main {
    padding: 1px 1px 0px;
}

.pending {
    background-color: orange;
    border: 0px;
}

.approved {
    background-color: #1c751c;
    border: 0px;
}

.approved.Exercise {
    background-color: #291c75;
    border: 0px;
}

.approved.Meal {
    background-color: #1c7541;
    border: 0px;
}

.declined {
    background-color: #a70000;
    border: 0px;
}
</style>