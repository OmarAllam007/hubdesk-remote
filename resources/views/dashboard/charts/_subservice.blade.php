<script>
    var labels = {!! json_encode(array_keys($data->ticketsBySubcategory)) !!};

    new Chart(document.getElementById('subserviceChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($data->ticketsBySubcategory)) !!},
            datasets: [
                {
                    label: "Total",
                    data: {!! json_encode(array_values($data->ticketsBySubcategory)) !!},
                    backgroundColor: Array(labels.length).fill('#035ca7')
                },
            ]
        },
        options: {
            plugins: {
                labels: {
                    render: 'value'
                }
            }
        }
    });
</script>
