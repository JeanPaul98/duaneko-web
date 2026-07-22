

export const initChartOne = () => {
    const chartElement = document.querySelector('#chartOne');
    if (!chartElement) return;

    let seriesData = [168, 385, 201, 298, 187, 195, 291, 110, 215, 390, 280, 112];
    if (chartElement.dataset.series) {
        try {
            const parsed = JSON.parse(chartElement.dataset.series);
            if (Array.isArray(parsed) && parsed.length) {
                seriesData = parsed;
            }
        } catch (e) {
            // keep default data
        }
    }

    const chartOneOptions = {
        series: [{
            name: "Ramassages",
            data: seriesData,
        },],
        colors: ["#465fff"],
        chart: {
            fontFamily: "Outfit, sans-serif",
            type: "bar",
            height: 180,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "39%",
                borderRadius: 5,
                borderRadiusApplication: "end",
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 4,
            colors: ["transparent"],
        },
        xaxis: {
            categories: [
                "Jan",
                "Fev",
                "Mar",
                "Avr",
                "Mai",
                "Jun",
                "Jul",
                "Aou",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        legend: {
            show: true,
            position: "top",
            horizontalAlign: "left",
            fontFamily: "Outfit",
            markers: {
                radius: 99,
            },
        },
        yaxis: {
            title: false,
        },
        grid: {
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
        fill: {
            opacity: 1,
        },

        tooltip: {
            x: {
                show: false,
            },
            y: {
                formatter: function (val) {
                    return val;
                },
            },
        },
    };

    const chart = new ApexCharts(chartElement, chartOneOptions);
    chart.render();

    return chart;
};

export default initChartOne;
