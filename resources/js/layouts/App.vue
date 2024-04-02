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

export default {
    props: ['items'],
    mounted() {
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
        })

    },
    components: {
        FullCalendar // make the <FullCalendar> tag available
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
                initialView: 'timeGridWeek',
                
                droppable: true,
                editable: true,
                selectable: true,
                allDaySlot: false,
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
                // eventClick: this.handleEventClick,
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
            }
        }
    },
    methods: {
        // add new event
        hadleDateSelect: function (selectionInfo) {
            console.log('date selected! ')
            console.log(selectionInfo)
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
        }
    }
}
</script>

<style>
.calendar {
    height: 460px;
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