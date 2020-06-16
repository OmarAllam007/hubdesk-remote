<script>

    var statusData = {!! json_encode($data->ticketsByStatus) !!};
    var statusDataSet = [];
    var statusLabels = [];

    let colors = ['#FF6384', '#36A2EB'];

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

        index++;
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
                    render: statusDataSet.length > 1 ? 'percentage' : 'value'
                }
            }
        }
    });


</script>