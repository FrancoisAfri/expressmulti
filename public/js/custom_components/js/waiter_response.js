!function (e) {
    "use strict";

    function a() {
    }
    async function testedDoc() {
        let url = 'tester';

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

    a.prototype.createBarChart = function (a, t, e, o, r, i) {
        Morris.Bar({
            element: a,
            data: t,
            xkey: e,
            ykeys: o,
            labels: r,
            dataLabels: !1,
            hideHover: "auto",
            resize: !0,
            gridLineColor: "rgba(65, 80, 95, 0.07)",
            barSizeRatio: .2,
            barColors: i
        })
    }, a.prototype.init = async function () {
        let chartColors = ["#182ea4"];

        let test = await testedDoc();

        // let modifiedArr = data.map(function(element){
        //     return element ;
        // });
        // console.log(data)
        console.log(test)

        const dataArray = [
            {y: "Jason", a: 87},
            {y: "Ali", a: 75},
            {y: "Musa", a: 50},
            {y: "Mike", a: 75},
            {y: "Lissa", a: 50},
            {y: "John", a: 38},
            {y: "Jacques", a: 37},
            {y: "soso", a: 12}
        ];

        const chart = e("#statistics-chart");
        const customColors = chart.data("colors");
        if (customColors) {
            chartColors = customColors.split(",");
        }

        this.createBarChart("statistics-chart", dataArray, "y", ["a"], ["Statistics"], chartColors);
    }, e.Dashboard4 = new a, e.Dashboard4.Constructor = a
}(window.jQuery), function () {
    "use strict";
    window.jQuery.Dashboard4.init()
}();
