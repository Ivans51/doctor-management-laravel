@extends('layouts.home')

@section('content')
    <div class="container">
        <div id="calendar"></div>
    </div>
@endsection

@push('scripts-bottom')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // initialize calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function (info, successCallback, failureCallback) {
                    // get the start and end dates of the current month
                    const start = info.startStr;
                    const end = info.endStr;

                    $.ajax({
                        url: '{{ route('schedule-timing-doctor') }}',
                        type: 'POST',
                        data: {
                            start: start,
                            end: end,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            const schedules = data.data.map(function (schedule) {
                                return {
                                    title: `${schedule.appointment.patient.name} - ${schedule.start_time} - ${schedule.end_time}`,
                                    start: schedule.date,
                                    id: JSON.stringify(schedule)
                                }
                            })
                            successCallback(schedules);
                        },
                        error: function () {
                            failureCallback();
                        }
                    });
                },
                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                    if (info.event.title) {
                        alert('Event: ' + info.event.title);
                    }
                },
            });
            calendar.render();
        });
    </script>
@endpush
