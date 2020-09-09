
<script>

    var ctx = document.getElementById('topServices');
    var myChart = new Chart(ctx, {
        type: 'pie',
        plugins: [ChartLabels],
        data: {
            labels: {!! json_encode(array_keys($data->topServices)) !!},
            datasets: [{
                data: {!! json_encode(array_values($data->topServices)) !!},
                backgroundColor: [
                    'rgba(173,69,9,0.96)',
                    'rgba(11,76,74,0.81)',
                    'rgba(171,111,19,0.87)',
                    'rgba(9,33,107,0.84)',
                    'rgba(61,15,13,0.83)',
                    'rgba(8,61,3,0.78)',
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
                    'rgba(255, 159, 64, 0.2)'
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
                        fontSize: 18,
                        textMargin: 5,
                    },
                    {
                        fontColor: 'white',
                        fontSize: 18,
                        render: 'percentage',
                        precision: 2,
                    },
                ],
            },

        }
    });

</script>