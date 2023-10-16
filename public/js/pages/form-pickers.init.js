!function (i) {
    "use strict";

    function e() {
    }

    e.prototype.init = function () {
        i("#basic-datepicker").flatpickr(), i("#datetime-datepicker").flatpickr({
            enableTime: !0,
            dateFormat: "Y-m-d H:i"
        }), i(".human").flatpickr({
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        }), i("#time-datepicker").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
            }), i("#my_datepicker").flatpickr({
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        }),
            i("#humanfd-datepicker").flatpickr({
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d"
            })
            , i("#ord_datepicker").flatpickr({
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        })
            , i("#minmax-datepicker").flatpickr({
            minDate: "2020-01",
            maxDate: "2020-03"
        }), i("#disable-datepicker").flatpickr({
            onReady: function () {
                this.jumpToDate("2025-01")
            }, disable: ["2025-01-10", "2025-01-21", "2025-01-30", new Date(2025, 4, 9)], dateFormat: "Y-m-d"
        }), i("#multiple-datepicker").flatpickr({
            mode: "multiple",
            dateFormat: "Y-m-d"
        }), i("#conjunction-datepicker").flatpickr({
            mode: "multiple",
            dateFormat: "Y-m-d",
            conjunction: " :: "
        }), i("#range-datepicker").flatpickr({mode: "range"}), i("#inline-datepicker").flatpickr({inline: !0}), i("#basic-timepicker").flatpickr({
            enableTime: !0,
            noCalendar: !0,
            dateFormat: "H:i"
        })
            , i("#24hours-timepicker").flatpickr({
            enableTime: !0,
            noCalendar: !0,
            dateFormat: "H:i",
            time_24hr: !0
        }), i("#minmax-timepicker").flatpickr({
            enableTime: !0,
            noCalendar: !0,
            dateFormat: "H:i",
            minDate: "16:00",
            maxDate: "22:30"
        }), i("#preloading-timepicker").flatpickr({
            enableTime: !0,
            noCalendar: !0,
            dateFormat: "H:i",
            defaultDate: "01:45"
        }), i("#check-minutes").click(function (e) {
            e.stopPropagation(), i("#single-input").clockpicker("show").clockpicker("toggleView", "minutes")
        })
    }, i.FormPickers = new e, i.FormPickers.Constructor = e
}(window.jQuery), function () {
    "use strict";
    window.jQuery.FormPickers.init()
}();
