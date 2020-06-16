<script>

    var labels = {!! json_encode(array_keys($data->ticketsByCoordinator)) !!};

    new Chart(document.getElementById('coordinators'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($data->ticketsByCoordinator)) !!},
            datasets: [
                {
                    label: "Total",
                    data: {!! json_encode(array_values(collect($data->ticketsByCoordinator)->pluck('total')->toArray())) !!},
                    backgroundColor: Array(labels.length).fill('#FF6384')
                },
                {
                    label: "Resolved",
                    data: {!! json_encode(array_values(collect($data->ticketsByCoordinator)->pluck('resolved')->toArray())) !!},
                    backgroundColor: Array(labels.length).fill('#36A2EB')
                },
                {
                    label: "Ontime",
                    data: {!! json_encode(array_values(collect($data->ticketsByCoordinator)->pluck('resolvedOnTime')->toArray())) !!},
                    backgroundColor: Array(labels.length).fill('#27BUED')

                }
            ]
        },
        options: {
            plugins: {
                labels: {
                    render: 'percentage'
                }
            }
        }
    });
</script>