<script>

    var ctx = document.getElementById('subCategoryChart');
    var subcategoryChart = new Chart(ctx, {
        type: 'pie',
        plugins: [ChartLabels],
        data: {
            labels: {!! json_encode(array_keys($data->ticketsBySubcategory)) !!},
            datasets: [{
                data: {!! json_encode(array_values(array_filter($data->ticketsBySubcategory))) !!},
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(52, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(63, 99, 132, 0.2)',
                    'rgba(90, 162, 235, 0.2)',
                    'rgba(80, 162, 235, 0.2)',
                    'rgba(182, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(32, 201, 255, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 180, 64, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(96, 85, 192, 0.2)',
                    'rgba(175, 185, 192, 0.2)',
                    'rgba(175, 218, 192, 0.2)',
                    'rgba(175, 218, 180, 0.2)',
                    'rgba(157, 128, 108, 0.2)',
                    'rgba(52, 159, 64, 0.2)',
                    'rgba(63, 99, 132, 0.2)',
                    'rgba(90, 162, 235, 0.2)',
                    'rgba(80, 162, 235, 0.2)',
                    'rgba(182, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(32, 201, 255, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 180, 64, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(96, 85, 192, 0.2)',
                    'rgba(96, 85, 192, 0.2)',
                    'rgba(96, 85, 192, 0.2)',

                ],


                borderWidth: 3
            }]
        },
        options: {
            plugins: {
                labels: [
                    {
                        render: 'label',
                        position: 'outside',
                        fontSize: 10,
                        textMargin: 5,
                    },
                    {
                        render: 'percentage',
                        precision: 2,

                    },
                ],
            },

        }
    });

</script>