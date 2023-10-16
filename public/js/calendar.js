// this.$selectedEvent.surname = undefined;
!function (l) {
    "use strict";


    async function getClientBookingById(id) {
        let url = 'getNoShow/' + id;

        try {
            let response = await fetch(url);
            if (response.ok) { // if HTTP-status is 200-299
                return await response.json();
            } else {
                // alert("HTTP-Error: " + response.status);
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function getClientBooking() {
        let url = 'getBookingsDash';
        try {
            let response = await fetch(url);

            if (response.ok) { // if HTTP-status is 200-299
                return await response.json();
            } else {
                // alert("HTTP-Error: " + response.status);
            }
        } catch (error) {
            console.log(error);
        }
    }

    function e() {
        this.$body = l("body"),
            this.$modal = l("#event-modal"),
            this.$btnEditEvent = l("#btn-edit-event"),
            this.$calendar = l("#calendar"),
            this.$formEvent = l("#form-event"),
            this.$btnNewEvent = l("#btn-new-event"),
            this.$btnDeleteEvent = l("#btn-delete-event"),
            this.$btnCancelEvent = l("#btn-cancel-event"),
            this.$btnCheck_outEvent = l("#btn-check_out-event"),
            this.$btnCheck_inEvent = l("#btn-check_in-event"),
            this.$btnNoshowEvent = l("#btn-not_show-event"),
            this.$btnSaveEvent = l("#btn-save-event"),
            this.$modalTitle = l("#modal-title"),
            this.$calendarObj = null,
            this.$selectedEvent = null,
            this.$newEventData = null

    }

    e.prototype.onEventClick = function (e) {


        this.$newEventData = null,
            this.$btnDeleteEvent.show(),
            this.$btnCancelEvent.show(),
            this.$btnCheck_inEvent.show(),
            this.$btnCheck_outEvent.show(),
            this.$btnNoshowEvent.show(),
            this.$btnEditEvent.show(),
            this.$btnSaveEvent.hide()
        this.$btnSaveEvent.hide()
    },
    e.prototype.onSelect = function (e) {
        this.$formEvent[0].reset(),
            this.$formEvent.removeClass("was-validated"),
            this.$selectedEvent = null,
            this.$newEventData = e,
            this.$btnDeleteEvent.hide(),
            this.$btnCancelEvent.hide(),
            this.$btnCheck_inEvent.hide(),
            this.$btnEditEvent.hide(),
            this.$btnNoshowEvent.hide(),
            this.$btnCheck_outEvent.hide(),
            this.$btnSaveEvent.show(),
            this.$modalTitle.text("Add New Booking")

        this.$modal.modal({backdrop: "static"})
        this.$calendarObj.unselect()

    },
        e.prototype.init = async function () {
            const e = new Date(l.now());
            new FullCalendarInteraction.Draggable(document.getElementById("external-events"), {
                itemSelector: ".external-event",
                eventData: function (e) {
                    return {
                        title: e.innerText,
                        className: l(e).data("class")
                    }
                }
            });

            let data = await getClientBooking();


            const p = data
                , a = this;

            a.$calendarObj = new FullCalendar.Calendar(a.$calendar[0], {
                plugins: [
                    "bootstrap",
                    "interaction",
                    "dayGrid",
                    "timeGrid",
                    "list"
                ],
                slotDuration: '00:30:00',
                themeSystem: "bootstrap",
                bootstrapFontAwesome: !1,
                buttonText: {
                    today: "Today",
                    month: "Month",
                    week: "Week",
                    day: "Day",
                    list: "List",
                    prev: "Prev",
                    next: "Next"
                },
                defaultView: "dayGridMonth",
                handleWindowResize: !0,
                height: l(window).height() - 200,
                header: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
                },
                events: p,
                editable: !0,
                droppable: !0,
                eventLimit: !0,
                allDay: !0,
                labels: true,
                selectable: true,
                selectHelper: true,
                // dateClick: function (date) {
                //     a.onSelect(date)
                // },
                selectAllow: function (info) {
                    return (info.start >= getDateWithoutTime(e));
                },
                eventClick: function (e) {
                    a.onEventClick(e)
                },

                select: function (d) {

                    a.onSelect(d)
                    a.$calendarObj.render(), a.$btnSaveEvent.on("click", function (e) {

                        a.$formEvent.on("submit", async function (e) {
                            e.preventDefault();


                            a.$selectedEvent && (a.$selectedEvent.remove(),
                                a.$selectedEvent = null)

                            a.$calendarObj.removeAllEvents();

                            // console.log(a.$calendarObj)

                            const t = a.$formEvent[0];
                            if (t.checkValidity()) {
                                if (a.$selectedEvent)
                                    a.$selectedEvent.setProp("title", l("#event-title").val())
                                else {

                                    let csrfToken = a.$formEvent.find('input[name=_token]').val();


                                    const n = {
                                        title: l("#event-title").val(),
                                        first_name: l("#event-name").val(),
                                        surname: l("#event-surname").val(),
                                        email: l("#event-email").val(),
                                        cell_number: l("#event-cell_number").val(),
                                        note: l("#event-note").val(),
                                        start: d.startStr,
                                        end: d.endStr,
                                        allDay: d.allDay,
                                        className: l("#event-category").val(),
                                        classPatient: l("#event-patient").val()
                                    };

                                    let id = l("#event-patient").val();
                                    let data = await getClientBookingById(id)

                                    if (data === 1) {
                                        Swal.fire({
                                            title: "Warning!!! This client did not show up for their last appointment ",
                                            text: "Do you want to continue with this booking ?",
                                            type: "warning",
                                            buttons: ["No", "Yes!"],
                                            showCancelButton: !0,
                                            cancelButtonColor: "#d33",
                                            confirmButtonColor: "#3085d6",
                                            confirmButtonText: "Yes"
                                        }).then(function (e) {

                                            if (e.value === true) {

                                                $.ajax({
                                                    method: 'Post',
                                                    url: 'booking_calender',
                                                    data: n,
                                                    dataType: 'json',
                                                    headers: {
                                                        'X-CSRF-TOKEN': csrfToken
                                                    },
                                                    success: function (success) {

                                                        let successMsgTitle = "Client booked Successfully";

                                                        let successHTML = '<button type="button" id="close-invalid-input-alert" ' +
                                                            'class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                                            '<h4><i class="icon fa fa-check"></i> ' + successMsgTitle + '</h4>';

                                                        successHTML += successMsgTitle;

                                                        a.$formEvent.find('#success-alert').addClass('alert alert-success alert-dismissible')
                                                            .fadeIn()
                                                            .html(successHTML);

                                                        a.$calendarObj.addEvent(n)

                                                        //auto close alert after 5 seconds
                                                        a.$formEvent.find("#success-alert").alert();
                                                        window.setTimeout(function () {
                                                            a.$formEvent.find("#success-alert").fadeOut('slow');
                                                        }, 5000);

                                                        window.setTimeout(function () {
                                                            window.location.reload()
                                                        }, 2000);


                                                    },
                                                    error: function (error) {
                                                        if (error.status === 422) {


                                                            const errors = error.responseJSON; //get the errors response data

                                                            a.$formEvent.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                                                            let errorsHTML = '<button type="button" id="close-invalid-input-alert" ' +
                                                                'class="close" aria-hidden="true">&times;</button>' +
                                                                '<h4><i class="icon fa fa-ban">' +
                                                                '</i> Invalid Input(s)!</h4><ul>';

                                                            $.each(errors, function (key, value) {
                                                                errorsHTML += '<li>' + value + '</li>'; //shows only the first error.
                                                                a.$formEvent.find('#' + key).closest('.form-group')
                                                                    .addClass('has-error'); //Add the has error class to form-groups with errors
                                                            });
                                                            errorsHTML += '</ul>';

                                                            a.$formEvent.find('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                                                .fadeIn()
                                                                .html(errorsHTML);


                                                            event.preventDefault();


                                                            //autoclose alert after 7 seconds
                                                            a.$formEvent.find("#invalid-input-alert").alert();
                                                            window.setTimeout(function () {
                                                                a.$formEvent.find("#invalid-input-alert").fadeOut('slow');
                                                            }, 7000);

                                                            //Close btn click
                                                            a.$formEvent.find('#close-invalid-input-alert').on('click', function () {
                                                                a.$formEvent.find("#invalid-input-alert").fadeOut('slow');
                                                            });
                                                        }
                                                    }
                                                });

                                                a.$btnSaveEvent.hide()
                                                a.$calendarObj.addEvent(n)

                                                window.setTimeout(function () {
                                                    window.location.reload()
                                                }, 2000);

                                            }

                                        })
                                    } else {
                                        $.ajax({
                                            method: 'Post',
                                            url: 'booking_calender',
                                            data: n,
                                            dataType: 'json',
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken
                                            },
                                            success: function (success) {

                                                let successMsgTitle = "Client booked Successfully";

                                                let successHTML = '<button type="button" id="close-invalid-input-alert" ' +
                                                    'class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                                    '<h4><i class="icon fa fa-check"></i> ' + successMsgTitle + '</h4>';

                                                successHTML += successMsgTitle;

                                                a.$formEvent.find('#success-alert').addClass('alert alert-success alert-dismissible')
                                                    .fadeIn()
                                                    .html(successHTML);

                                                a.$calendarObj.addEvent(n)

                                                //auto close alert after 5 seconds
                                                a.$formEvent.find("#success-alert").alert();
                                                window.setTimeout(function () {
                                                    a.$formEvent.find("#success-alert").fadeOut('slow');
                                                }, 5000);

                                                window.setTimeout(function () {
                                                    window.location.reload()
                                                }, 2000);


                                            },
                                            error: function (error) {
                                                if (error.status === 422) {


                                                    const errors = error.responseJSON; //get the errors response data

                                                    a.$formEvent.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                                                    let errorsHTML = '<button type="button" id="close-invalid-input-alert" ' +
                                                        'class="close" aria-hidden="true">&times;</button>' +
                                                        '<h4><i class="icon fa fa-ban">' +
                                                        '</i> Invalid Input(s)!</h4><ul>';

                                                    $.each(errors, function (key, value) {
                                                        errorsHTML += '<li>' + value + '</li>'; //shows only the first error.
                                                        a.$formEvent.find('#' + key).closest('.form-group')
                                                            .addClass('has-error'); //Add the has error class to form-groups with errors
                                                    });
                                                    errorsHTML += '</ul>';

                                                    a.$formEvent.find('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                                        .fadeIn()
                                                        .html(errorsHTML);


                                                    event.preventDefault();


                                                    //autoclose alert after 7 seconds
                                                    a.$formEvent.find("#invalid-input-alert").alert();
                                                    window.setTimeout(function () {
                                                        a.$formEvent.find("#invalid-input-alert").fadeOut('slow');
                                                    }, 7000);

                                                    //Close btn click
                                                    a.$formEvent.find('#close-invalid-input-alert').on('click', function () {
                                                        a.$formEvent.find("#invalid-input-alert").fadeOut('slow');
                                                    });
                                                }
                                            }
                                        });

                                        a.$btnSaveEvent.hide()
                                        a.$calendarObj.addEvent(n)
                                    }

                                    window.setTimeout(function () {
                                        a.$modal.modal("hide").fadeOut('slow');
                                    }, 5000);

                                    window.setTimeout(function () {
                                        window.location.reload()
                                    }, 2000);
                                }


                                // console.log(data);

                            } else e.stopPropagation(), t.classList.add("was-validated")
                        })
                    })
                }

            }), a.$calendarObj.render(), a.$btnEditEvent.on("click", function (e) {

                e.preventDefault();

                let start = a.$selectedEvent._instance.range.start
                let end = a.$selectedEvent._instance.range.end
                let AllDay = a.$selectedEvent._def.allDay;


                function getStrStart(esp) {

                    let str_start = JSON.stringify(start)

                    if (AllDay === true) {
                        return str_start.slice(1, -15)
                    } else {
                        return str_start.slice(1, -6)
                    }
                }


                let date = JSON.stringify(end)
                let str_End = moment(date).subtract(2, 'hours')

                //
                let csrfToken = a.$formEvent.find('input[name=_token]').val();

                const n = {
                    title: l("#event-title").val(),
                    first_name: l("#event-name").val(),
                    surname: l("#event-surname").val(),
                    email: l("#event-email").val(),
                    start: getStrStart(start),
                    end: str_End._i.slice(1, -6),
                    allDay: AllDay,
                    cell_number: l("#event-cell_number").val(),
                    Notes: l("#event-note").val(),
                    className: l("#event-category").val(),
                    classPatient: l("#event-patient").val(),
                    userId: a.$selectedEvent._def.publicId
                };


                // let id = a.$selectedEvent._def.publicId
                console.log(n)

                let url = 'createOrBook';

                let method = 'post';

                let msg = succMsg(n);


                postDataApi(url, n, msg, method)

                // console.log(n)
                a.$btnSaveEvent.hide()
                a.$calendarObj.addEvent(n)


                window.setTimeout(function () {
                    a.$modal.modal("hide").fadeOut('slow');
                }, 5000);

                window.setTimeout(function () {
                    window.location.reload()
                }, 2000);
            })

            /**
             *
             * @param dt
             * @returns {*}
             */
            function getDateWithoutTime(dt) {
                dt.setHours(0, 0, 0, 0);
                return dt;
            }

            function succMsg(n) {
                let successMsgTitle = "Client booked Successfully";

                let successHTML = '<button type="button" id="close-invalid-input-alert" ' +
                    'class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-check"></i> ' + successMsgTitle + '</h4>';

                successHTML += successMsgTitle;

                a.$formEvent.find('#success-alert').addClass('alert alert-success alert-dismissible')
                    .fadeIn()
                    .html(successHTML);

                a.$calendarObj.addEvent(n)

                //auto close alert after 5 seconds
                a.$formEvent.find("#success-alert").alert();
                window.setTimeout(function () {
                    a.$formEvent.find("#success-alert").fadeOut('slow');
                }, 5000);
            }

            function err(error) {
                if (error.status === 422) {

                    const errors = error.responseJSON; //get the errors response data

                    a.$formEvent.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                    let errorsHTML = '<button type="button" id="close-invalid-input-alert" ' +
                        'class="close" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-ban">' +
                        '</i> Invalid Input(s)!</h4><ul>';

                    $.each(errors, function (key, value) {
                        errorsHTML += '<li>' + value + '</li>'; //shows only the first error.
                        a.$formEvent.find('#' + key).closest('.form-group')
                            .addClass('has-error'); //Add the has error class to form-groups with errors
                    });
                    errorsHTML += '</ul>';

                    a.$formEvent.find('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                        .fadeIn()
                        .html(errorsHTML);


                    event.preventDefault();


                    //autoclose alert after 7 seconds
                    a.$formEvent.find("#invalid-input-alert").alert();
                    window.setTimeout(function () {
                        a.$formEvent.find("#invalid-input-alert").fadeOut('slow');
                    }, 7000);

                    //Close btn click
                    a.$formEvent.find('#close-invalid-input-alert').on('click', function () {
                        a.$formEvent.find("#invalid-input-alert").fadeOut('slow');
                    });
                }
            }

            /**
             * delete booking
             */
            l(a.$btnDeleteEvent.on("click", function (e) {

                let id = a.$selectedEvent._def.publicId

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this booking ",
                    type: "warning",
                    buttons: ["Cancel", "Yes!"],
                    showCancelButton: !0,
                    cancelButtonColor: "#d33",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes"
                }).then(function (t) {

                    if (t.value === true) {


                        let url = 'booking_calender/' + id;

                        const n = {
                            id: id,
                        };
                        let method = 'DELETE';
                        let msg = Swal.fire("Deleted!", "Your booking has been Deleted.", "success");

                        postDataApi(url, n, msg, method)


                        // remove event from calendar on success
                        a.$selectedEvent && (a.$selectedEvent.remove(),
                            a.$selectedEvent = null,
                            a.$modal.modal("hide"))

                        window.setTimeout(function () {
                            window.location.reload()
                        }, 2000);

                    }

                })

            }))

            /**
             * Cancel event
             */
            l(a.$btnCancelEvent.on("click", function (e) {

                let id = a.$selectedEvent._def.publicId

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to Cancel this booking ",
                    type: "warning",
                    buttons: ["Cancel", "Yes!"],
                    showCancelButton: !0,
                    cancelButtonColor: "#d33",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes"
                }).then(function (e) {

                    if (e.value === true) {

                        let url = 'cancel_booking/' + id;
                        let method = 'post';
                        const n = {
                            id: id,
                            className: 'bg-secondary',
                        };

                        let msg = Swal.fire("Cancelled!", "Your booking has been Cancelled.", "success");

                        postDataApi(url, n, msg, method)

                        // remove event from calendar on success
                        a.$selectedEvent && (a.$selectedEvent.remove(),
                            a.$selectedEvent = null,
                            a.$modal.modal("hide"))

                        window.setTimeout(function () {
                            window.location.reload()
                        }, 2000);

                    }

                })

            }))

            /**
             * check out
             */
            l(a.$btnCheck_inEvent.on("click", async function (e) {

                let id = a.$selectedEvent._def.publicId

                let url = 'checkin_booking/' + id;
                let method = 'post';
                let msg = 'post';
                const n = {
                    id: id,
                    className: 'bg-blue',
                };

                postDataApi(url, n, msg, method)

                a.$btnCancelEvent.hide();
                a.$btnDeleteEvent.hide();
                a.$btnNoshowEvent.hide();
                a.$btnCheck_inEvent.hide();
                a.$btnEditEvent.hide();


                window.setTimeout(function () {
                    window.location.reload()
                }, 2000);
            }))

            /**
             * check out
             */
            l(a.$btnCheck_outEvent.on("click", function (e) {

                let id = a.$selectedEvent._def.publicId

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to Check out this booking ",
                    type: "warning",
                    buttons: ["Cancel", "Yes!"],
                    showCancelButton: !0,
                    cancelButtonColor: "#d33",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes"
                }).then(function (e) {

                    if (e.value === true) {

                        let url = 'checkout_booking/' + id;
                        let method = 'post';
                        let msg = 'post';
                        const n = {
                            id: id,
                            className: 'bg-pink',
                        };

                        postDataApi(url, n, msg, method)

                        a.$btnCancelEvent.hide();
                        a.$btnDeleteEvent.hide();
                        a.$btnNoshowEvent.hide();
                        a.$btnCheck_inEvent.hide();
                        a.$btnCheck_outEvent.hide();
                        a.$btnEditEvent.hide();

                        window.setTimeout(function () {
                            window.location.reload()
                        }, 2000);

                    }

                })

                //ajax

            }))


            /**
             * no show
             */
            l(a.$btnNoshowEvent.on("click", async function (e) {

                let id = a.$selectedEvent._def.publicId
                let url = 'no_show/' + id;
                let method = 'post';
                let msg = 'post';


                const n = {
                    id: id,
                    className: 'bg-dark',
                };

                postDataApi(url, n, msg, method)

                a.$btnCancelEvent.hide();
                a.$btnDeleteEvent.hide();
                a.$btnNoshowEvent.hide();
                a.$btnCheck_inEvent.hide();
                a.$btnEditEvent.hide();

                // remove event from calendar on success
                a.$selectedEvent && (a.$selectedEvent.remove(),
                    a.$selectedEvent = null,
                    a.$modal.modal("hide"))


                window.setTimeout(function () {
                    window.location.reload()
                }, 2000);
            }))

            /**
             *
             * @param url
             * @param n
             * @param msg
             * @param method
             */
            function postDataApi(url, n, msg, method) {
                let csrfToken = a.$formEvent.find('input[name=_token]').val();

                fetch(url, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    method: method,
                    credentials: "same-origin",
                    body: JSON.stringify(n),
                })
                    .then((data) => {

                        if (!data.ok) {
                            // get error message from body or default to response status
                            // console.log(data)
                            console.error(data.status)
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

            }

        }, l.CalendarApp = new e, l.CalendarApp.Constructor = e
}(window.jQuery), function () {
    "use strict";
    window.jQuery.CalendarApp.init()
}();











