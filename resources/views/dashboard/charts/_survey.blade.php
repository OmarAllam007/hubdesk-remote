<script>

{{--    {{dd($data->customerSatisfaction['questions'])}}--}}
    var items = {!! json_encode($data->customerSatisfaction['questions'] ?? []) !!};

    for (let [key, value] of Object.entries(items)) {
        let cKey = 'customers' + key.split(' ').join('');

        var canvasDiv = document.createElement('div');
        canvasDiv.setAttribute("style", "width:400px;margin:10px;");
        var canvasElem = document.createElement('canvas');
        canvasElem.setAttribute("id", cKey);
        var question = document.createElement('p');
        var percentage = document.createElement('strong');
        question.innerText = key;
        percentage.innerText = ' ( ' +value['percentage'] + '% )';

        canvasDiv.appendChild(document.createElement('br'));
        canvasDiv.appendChild(percentage);
        canvasDiv.appendChild(question);
        canvasDiv.appendChild(canvasElem);

        document.getElementById('customerCanvases').appendChild(canvasDiv);
        // console.log(Object.keys(value), value[]);
        new Chart(document.getElementById(cKey), {
            type: 'pie',
            plugins: [ChartLabels],
            data: {
                labels: Object.keys(value['answers']),
                datasets: [{
                    data: Object.values(value['answers']),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 2
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
    }
</script>