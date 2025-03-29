import React, { useState, useEffect } from 'react';
import * as echarts from 'echarts';

const Index = () => {
    const [pieChartMinimized, setPieChartMinimized] = useState(false);
    const [barChartMinimized, setBarChartMinimized] = useState(false);
    const [lineChartMinimized, setLineChartMinimized] = useState(false);

    useEffect(() => {
        generatePieChart();
        generateBarChart();
        generateLineChart();
    }, []);

    const togglePieChart = () => {
        setPieChartMinimized(!pieChartMinimized);
    };

    const toggleBarChart = () => {
        setBarChartMinimized(!barChartMinimized);
    };

    const toggleLineChart = () => {
        setLineChartMinimized(!lineChartMinimized);
    };

    const generatePieChart = () => {
        const pieChart = echarts.init(document.getElementById('pieChart'));

        const pieOption = {
            title: {
                text: '',
                subtext: '',
                left: 'center'
            },
            tooltip: {
                trigger: 'item'
            },
            // legend: {
            //     orient: 'vertical',
            //     left: 'left'
            // },
            series: [{
                name: 'Access From',
                type: 'pie',
                radius: '50%',
                data: [
                    { value: 1048, name: 'Search Engine' },
                    { value: 735, name: 'Direct' },
                    { value: 580, name: 'Email' },
                    { value: 484, name: 'Union Ads' },
                    { value: 300, name: 'Video Ads' }
                ],
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }]
        };

        pieChart.setOption(pieOption);
    };

    const generateBarChart = () => {
        const barChart = echarts.init(document.getElementById('barChart'));

        const barOption = {
            legend: {},
            tooltip: {},
            dataset: {
                source: [
                    ['product', '2015', '2016', '2017'],
                    ['Matcha Latte', 43.3, 85.8, 93.7],
                    ['Milk Tea', 83.1, 73.4, 55.1],
                    ['Cheese Cocoa', 86.4, 65.2, 82.5],
                    ['Walnut Brownie', 72.4, 53.9, 39.1]
                ]
            },
            xAxis: { type: 'category' },
            yAxis: {},
            series: [{ type: 'bar' }, { type: 'bar' }, { type: 'bar' }]
        };

        barChart.setOption(barOption);
    };

    const generateLineChart = () => {
        const lineChart = echarts.init(document.getElementById('lineChart'));

        const option = {
            title: {
                text: ''
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['Email', 'Union Ads', 'Video Ads', 'Direct', 'Search Engine']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                // feature: {
                //     saveAsImage: {}
                // }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name: 'Email',
                    type: 'line',
                    stack: 'Total',
                    data: [120, 132, 101, 134, 90, 230, 210]
                },
                {
                    name: 'Union Ads',
                    type: 'line',
                    stack: 'Total',
                    data: [220, 182, 191, 234, 290, 330, 310]
                },
                {
                    name: 'Video Ads',
                    type: 'line',
                    stack: 'Total',
                    data: [150, 232, 201, 154, 190, 330, 410]
                },
                {
                    name: 'Direct',
                    type: 'line',
                    stack: 'Total',
                    data: [320, 332, 301, 334, 390, 330, 320]
                },
                {
                    name: 'Search Engine',
                    type: 'line',
                    stack: 'Total',
                    data: [820, 932, 901, 934, 1290, 1330, 1320]
                }
            ]
        };

        lineChart.setOption(option);
    };

    return (
        <div className="container mx-auto">
            {/* Content Header */}
            <section className="p-4">
                <div className="container mx-auto">
                    <div className="flex justify-between">
                        <h1 className="text-xl font-semibold">Dashboard</h1>
                        <nav className="breadcrumb">
                            <ol className="list-none flex">
                                <li className="text-blue-500">Home</li>
                                <li className="text-gray-500 mx-2">/</li>
                                <li className="text-gray-500">Dashboard</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </section>

            {/* Main content */}
            <section className="p-4">
                <div className="grid grid-cols-2 gap-4">
                    {/* Pie Chart */}
                    <div className={`card border border-gray-300 rounded-lg shadow-md ${pieChartMinimized ? 'h-16' : 'h-auto'}`}>
                        <div className="card-header flex justify-between p-4 cursor-pointer bg-blue-200" onClick={togglePieChart}>
                            <h2 className="text-lg font-semibold">Pie Chart</h2>
                            <p className="text-xl font-bold">{pieChartMinimized ? '+' : '-'}</p>
                        </div>
                        <div className={`${pieChartMinimized ? 'hidden' : 'block'}`}>
                            <div className="card-body">
                                <div id="pieChart" style={{ height: '300px' }}></div>
                            </div>
                        </div>
                    </div>
                    {/* Bar Chart */}
                    <div className={`card border border-gray-300 rounded-lg shadow-md transition ${barChartMinimized ? 'h-16' : 'h-auto'}`}>
                        <div className="card-header flex justify-between p-4 cursor-pointer bg-green-200" onClick={toggleBarChart}>
                            <h2 className="text-lg font-semibold">Bar Chart</h2>
                            <p className="text-xl font-bold">{barChartMinimized ? '+' : '-'}</p>
                        </div>
                        <div className={`${barChartMinimized ? 'hidden' : 'block'}`}>
                            <div className="card-body">
                                <div id="barChart" style={{ height: '300px' }}></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                    {/* Line Chart */}
                    <div className={`card border border-gray-300 rounded-lg shadow-md transition mt-2 ${lineChartMinimized ? 'h-16' : 'h-auto'}`}>
                        <div className="card-header flex justify-between p-4 cursor-pointer bg-yellow-200" onClick={toggleLineChart}>
                            <h2 className="text-lg font-semibold">Line Chart</h2>
                            <p className="text-xl font-bold">{lineChartMinimized ? '+' : '-'}</p>
                        </div>
                        <div className={`${lineChartMinimized ? 'hidden' : 'block'}`}>
                            <div className="card-body">
                                <div id="lineChart" style={{ height: '300px' }}></div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    );
};

export default Index;
