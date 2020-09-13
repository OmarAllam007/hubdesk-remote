<script>

    var statusData = {!! json_encode($data->ticketsByStatus) !!};
    var statusDataSet = [];
    var statusLabels = [];

    let colors = ['rgba(11,76,74,0.81)', 'rgba(173,69,9,0.96)'];

    let index = 0;

    for (let [key, value] of Object.entries(statusData)) {
        statusLabels.push(value['labels'][0]);
        statusDataSet.push(
            {
                label: key,
                data: Object.values(value['values'][0]),
                backgroundColor: Array(value['labels'][0].length).fill(colors[index])
            }
        );
        ++index;
    }

    new Chart(document.getElementById('byStatus'), {
        type: 'bar',
        data: {
            labels: statusLabels[0],
            datasets: statusDataSet,
        },
        options: {
            plugins: {
                labels: {
                    render: 'value',
                    fontSize: 16,
                }
            },
            responsive: true,

            scales: {
                xAxes: [{
                    display: true,
                    fontSize:16,
                    barPercentage: 0.4

                }],
                yAxes: [{
                    display: true,
                    fontSize:16,
                    render: 'value',
                    ticks: {
                        suggestedMin: 50,
                    }
                }]
            },
            ticks:{
                beginAtZero:true
            },
        }
    });


</script>