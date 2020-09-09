<script>

    var labels = {!! json_encode(array_keys($data->yearlyPerformance)) !!};

    new Chart(document.getElementById('yearlyPerformance'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($data->yearlyPerformance)) !!},
            datasets: [
                {
                    label: "Total",
                    data: {!! json_encode(array_values(collect($data->yearlyPerformance)->pluck('total')->toArray())) !!},

                    backgroundColor: Array(labels.length).fill('rgba(173,69,9,0.96)')
                },

                {
                    label: "Resolved & Closed Ontime",
                    data: {!! json_encode(array_values(collect($data->yearlyPerformance)->pluck('ontime')->toArray())) !!},
                    backgroundColor: Array(labels.length).fill('rgba(11,76,74,0.81)')

                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            showScale: false,
            plugins: {
                labels: {
                    render: 'value',
                    fontSize: 16,
                }
            }
        }
    });
</script>
