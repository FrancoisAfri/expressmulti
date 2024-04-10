
function hexToRGB(a, e) {
    var r = parseInt(a.slice(1, 3), 16), t = parseInt(a.slice(3, 5), 16), o = parseInt(a.slice(5, 7), 16);
    return e ? "rgba(" + r + ", " + t + ", " + o + ", " + e + ")" : "rgb(" + r + ", " + t + ", " + o + ")"
}

!function(e)
{"use strict";

    function a(){
        this.$body = i("body"), this.charts = []
    }

    async function getWaiterResponseTime() {
        let url = 'getWaiterResponseTime';

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

    a.prototype.createBarChart=function(a,t,e,o,r,i)
    {
        Morris.Bar({element:a,data:t,xkey:e,ykeys:o,labels:r,dataLabels:!1,hideHover:"auto",resize:!0,gridLineColor:"rgba(65, 80, 95, 0.07)",barSizeRatio:.2,barColors:i})
    }
        ,a.prototype.createAreaChartDotted=function(a,t,e,o,r,i,s,l,n,c)
    {
        Morris.Area({element:a,pointSize:3,lineWidth:1,data:o,xkey:r,ykeys:i,labels:s,dataLabels:!1,hideHover:"auto",pointFillColors:l,pointStrokeColors:n,resize:!0,smooth:!1,gridLineColor:"rgba(65, 80, 95, 0.07)",lineColors:c})
    }
        ,a.prototype.createDonutChart=function(a,t,e)
    {
        Morris.Donut({element:a,data:t,barSize:.2,resize:!0,colors:e,backgroundColor:"transparent"})
    }
        ,a.prototype.init=function()
    {
        let data = await getWaiterResponseTime();
        console.log(data)
        // Create an empty array to store the data
        let resultData = [];
        // Iterate over the data and format it
        for (let i = 0; i < data.length; i++) {
            resultData.push({ y: data[i].year, a: data[i].value });
        }
        var a=["#02c0ce"];
        (t=e("#statistics-chart").data("colors"))&&(a=t.split(","))
            ,this.createBarChart("statistics-chart",[{y:"2012",a:87},{y:"2013",a:75},{y:"2014",a:50},{y:"2015",a:75},{y:"2016",a:50},{y:"2017",a:38},{y:"2018",a:72}],"y",["a"],["Statistics"],a);
        a=["#4a81d4","#e3eaef"];(t=e("#income-amounts").data("colors"))&&(a=t.split(",")),
        this.createAreaChartDotted("income-amounts",0,0,[{y:"2012",a:10,b:20},{y:"2013",a:75,b:65},{y:"2014",a:50,b:40},{y:"2015",a:75,b:65},{y:"2016",a:50,b:40},{y:"2017",a:75,b:65},{y:"2018",a:90,b:60}],"y",["a","b"],["Bitcoin","Litecoin"],["#ffffff"],["#999999"],a);
        var t;a=["#4fc6e1","#6658dd","#ebeff2"];
        (t=e("#lifetime-sales").data("colors"))&&(a=t.split(",")),
            this.createDonutChart("lifetime-sales",[{label:" Total Sales ",value:12},{label:" Campaign Send",value:30},{label:" Daily Sales ",value:20}],a)
    },e.Dashboard4=new a,e.Dashboard4.Constructor=a
}
(window.jQuery),
    function(){"use strict";window.jQuery.Dashboard4.init()}();
