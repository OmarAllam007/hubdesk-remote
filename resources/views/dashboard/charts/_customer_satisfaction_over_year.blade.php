<script>
    var ctx = document.getElementById('customerSatisfactionOverYear');

    var myLineChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($data->customerSatisfactionOverYear->toArray())) !!},
            datasets: [{
                label: 'Customer Satisfaction',
                data: {!! json_encode(array_values($data->customerSatisfactionOverYear->toArray())) !!},
                backgroundColor: 'rgba(11,76,74,0.8)',
                order: 2
            }]
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
            }
        }
    });


</script>


