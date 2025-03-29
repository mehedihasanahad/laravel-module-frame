<!--Dashboard -->
@section('custom-css')
    <!-- uPlot -->
    <link rel="stylesheet" href="{{ asset('assets/css/uPlot.min.css') }}">
@endsection
<!-- Content Header (Page header) -->
<section class="content-header p-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <!-- PIE CHART -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Pie Chart</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chartDiv">
                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <div id="chartTableData" style="display: none;">
                        <button class="btn btn-sm btn-info" id="backtoChart" style="float:right">back To chart</button>
                        <div id="chartData"></div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-6">
            <!-- BAR CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Bar Chart</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="barChart">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <div id="barChartTableData" style="display: none;">
                        <button class="btn btn-sm btn-info" id="backtoBarChart" style="float:right">back To chart</button>
                        <div id="barChartData"></div>
                    </div>


                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- LINE CHART -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Line Chart</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>
<!-- /.content -->


@section('custom-scripts-links')
    <!-- ChartJS -->
    <script src="{{ asset('assets/js/Chart.min.js') }}"></script>
    <!-- uPlot -->
    <script src="{{ asset('assets/js/uPlot.min.js') }}"></script>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function(){
            fetchDataForPieChart();

            $('#backtoChart').click(function(){
                $('#chartDiv').show();
                $('#chartTableData').hide();
                $('#chartData').html('');
            });

            $('#backtoBarChart').click(function(){
                $('#barChart').show();
                $('#barChartTableData').hide();
                $('#barChartData').html('');
            });
        });

        function fetchDataForPieChart(){
            $.ajax({
                url:"{{url('/dashboard/get-chart-data')}}",
                method:'POST',
                data:{
                    _token: "{{ csrf_token() }}",
                },
                success:function(response){
                    generatePieChart(JSON.parse(response.data.pieData));
                    generateBarChart(JSON.parse(response.data.barData));
                    generateLineChart(response.data.lineData);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function generatePieChart(data){
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
            var pieData        = data;
            var pieOptions     = {
                maintainAspectRatio : false,
                responsive : true,
                onClick: function(click){
                    const slice = myPieChart.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
                    if(slice.length){
                        const pieSlice = slice[0];
                        const selectedValue = myPieChart.data.datasets[pieSlice._datasetIndex].data[pieSlice._index];
                        if(selectedValue){
                            $('#chartDiv').hide();
                            $('#chartTableData').show();
                            $('#chartData').html(selectedValue);
                        }
                    }
                }
            }

            const myPieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
            })
        }

        function generateBarChart(areaChartData){
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            barChartData.datasets[0] = areaChartData.datasets[1]
            barChartData.datasets[1] = areaChartData.datasets[0]

            var barChartOptions = {
                responsive              : true,
                maintainAspectRatio     : false,
                datasetFill             : false,
                onClick                 : function(click){
                    const bar = myBarChart.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
                    if(bar.length){
                        const barSlice = bar[0];
                        const selectedValue = myBarChart.data.datasets[barSlice._datasetIndex].data[barSlice._index];
                        if(selectedValue){
                            $('#barChart').hide();
                            $('#barChartTableData').show();
                            $('#barChartData').html(selectedValue);
                        }
                    }
                }
            }

            const myBarChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })
        }


        function getSize(elementId) {
            return {
                width: document.getElementById(elementId).offsetWidth,
                height: document.getElementById(elementId).offsetHeight,
            }
        }


        function generateLineChart(lineChartData){
            const optsLineChart = {
                ... getSize('lineChart'),
                scales: {
                    x: {
                        time: false,
                    },
                    y: {
                        range: [0, 100],
                    },
                },
                series: [
                    {},
                    {
                        fill: 'transparent',
                        width: 5,
                        stroke: 'rgba(60,141,188,1)',
                    },
                    {
                        stroke: '#c1c7d1',
                        width: 5,
                        fill: 'transparent',
                    },
                ],
            };


            let lineChart = new uPlot(optsLineChart, lineChartData, document.getElementById('lineChart'));

            // window.addEventListener("resize", e => {
            //     lineChart.setSize(getSize('lineChart'));
            // });
        }



    </script>
@endsection
<!--Dashboard -->
