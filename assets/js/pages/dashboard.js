let chart_cpu,chart_ram,chart_bttr,chart_ssd;


$(function(){
    chart_cpu = _cpu();
    chart_ram = _ram();
    chart_bttr = _battery();
    chart_ssd = _ssd();

    _updateDashboard();
});

function _cpu() {
    var element = document.getElementById("kt_mixed_widget_cpu_chart");
    var height = parseInt(KTUtil.css(element, 'height'));

    if (!element) {
        return;
    }

    var options = {
        series: [0],
        chart: {
            height: height,
            type: 'radialBar',
            offsetY: 0
        },
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,

                hollow: {
                    margin: 0,
                    size: "70%"
                },
                dataLabels: {
                    showOn: "always",
                    name: {
                        show: true,
                        fontSize: "13px",
                        fontWeight: "700",
                        offsetY: -5,
                        color: KTApp.getSettings()['colors']['gray']['gray-500']
                    },
                    value: {
                        color: KTApp.getSettings()['colors']['gray']['gray-700'],
                        fontSize: "30px",
                        fontWeight: "700",
                        offsetY: -40,
                        show: true
                    }
                },
                track: {
                    background: KTApp.getSettings()['colors']['theme']['light']['primary'],
                    strokeWidth: '100%'
                }
            }
        },
        colors: [KTApp.getSettings()['colors']['theme']['base']['primary']],
        stroke: {
            lineCap: "round",
        },
        labels: ["CPU"]
    };

    var chart = new ApexCharts(element, options);



    chart.render();

    return chart;
};

function _ram() {
    var element = document.getElementById("kt_mixed_widget_ram_chart");
    var height = parseInt(KTUtil.css(element, 'height'));

    if (!element) {
        return;
    }

    var options = {
        series: [0],
        chart: {
            height: height,
            type: 'radialBar',
            offsetY: 0
        },
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,

                hollow: {
                    margin: 0,
                    size: "70%"
                },
                dataLabels: {
                    showOn: "always",
                    name: {
                        show: true,
                        fontSize: "13px",
                        fontWeight: "700",
                        offsetY: -5,
                        color: KTApp.getSettings()['colors']['gray']['gray-500']
                    },
                    value: {
                        color: KTApp.getSettings()['colors']['gray']['gray-700'],
                        fontSize: "30px",
                        fontWeight: "700",
                        offsetY: -40,
                        show: true
                    }
                },
                track: {
                    background: KTApp.getSettings()['colors']['theme']['light']['primary'],
                    strokeWidth: '100%'
                }
            }
        },
        colors: [KTApp.getSettings()['colors']['theme']['base']['primary']],
        stroke: {
            lineCap: "round",
        },
        labels: ["RAM"]
    };

    var chart = new ApexCharts(element, options);



    chart.render();
    return chart;
};

function _battery() {
    var element = document.getElementById("kt_mixed_widget_battery_chart");
    var height = parseInt(KTUtil.css(element, 'height'));

    if (!element) {
        return;
    }

    var options = {
        series: [0],
        chart: {
            height: height,
            type: 'radialBar',
            offsetY: 0
        },
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,

                hollow: {
                    margin: 0,
                    size: "70%"
                },
                dataLabels: {
                    showOn: "always",
                    name: {
                        show: true,
                        fontSize: "13px",
                        fontWeight: "700",
                        offsetY: -5,
                        color: KTApp.getSettings()['colors']['gray']['gray-500']
                    },
                    value: {
                        color: KTApp.getSettings()['colors']['gray']['gray-700'],
                        fontSize: "30px",
                        fontWeight: "700",
                        offsetY: -40,
                        show: true
                    }
                },
                track: {
                    background: KTApp.getSettings()['colors']['theme']['light']['primary'],
                    strokeWidth: '100%'
                }
            }
        },
        colors: [KTApp.getSettings()['colors']['theme']['base']['primary']],
        stroke: {
            lineCap: "round",
        },
        labels: ["BATERIA"]
    };

    var chart = new ApexCharts(element, options);



    chart.render();

    return chart;
};

function _ssd() {
    var element = document.getElementById("kt_mixed_widget_ssd_chart");
    var height = parseInt(KTUtil.css(element, 'height'));

    if (!element) {
        return;
    }

    var options = {
        series: [0],
        chart: {
            height: height,
            type: 'radialBar',
            offsetY: 0
        },
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,

                hollow: {
                    margin: 0,
                    size: "70%"
                },
                dataLabels: {
                    showOn: "always",
                    name: {
                        show: true,
                        fontSize: "13px",
                        fontWeight: "700",
                        offsetY: -5,
                        color: KTApp.getSettings()['colors']['gray']['gray-500']
                    },
                    value: {
                        color: KTApp.getSettings()['colors']['gray']['gray-700'],
                        fontSize: "30px",
                        fontWeight: "700",
                        offsetY: -40,
                        show: true
                    }
                },
                track: {
                    background: KTApp.getSettings()['colors']['theme']['light']['primary'],
                    strokeWidth: '100%'
                }
            }
        },
        colors: [KTApp.getSettings()['colors']['theme']['base']['primary']],
        stroke: {
            lineCap: "round",
        },
        labels: ["SSD"]
    };

    var chart = new ApexCharts(element, options);



    chart.render();

    return chart;
};



function _updateDashboard(){
    //$('#kt_content > div > div > div.row.mt-0.mt-lg-3 > div.col-xl-12 > div > div.card-body').css('filter','grayscale(100%)');
    $.get('api/index.php').done(function(resp){
        let info = JSON.parse(resp);
        $('#kt_content > div > div > div.row.mt-0.mt-lg-3 > div.col-xl-12 > div > div.card-body').css('filter','grayscale(0%)');
        
        chart_cpu.updateSeries([info.cpu.usage]);
        chart_ram.updateSeries([info.ram.usage]);
        chart_bttr.updateSeries([info.battery.usage]);
        chart_ssd.updateSeries([info.disks[0].usage]);

        $('#kt_mixed_widget_ram_chart').attr('title', 'Usado: '+formatBytes(parseInt(info.ram.used))+'\nRestante: '+formatBytes(parseInt(info.ram.free))+'\nCompartilhado: '+formatBytes(parseInt(info.ram.shared))+' com '+info.gpu.model+'\nTotal: '+formatBytes(parseInt(info.ram.total)));
        $('#kt_mixed_widget_cpu_chart').attr('title', 'Modelo: '+info.cpu.model+'\nFabricante: '+info.cpu.vendor+'\nCapacidade: '+info.cpu.maxFreq);
        $('#kt_mixed_widget_battery_chart').attr('title','Status: '+(info.battery.status == 'charging' ? 'Carregando':'Carregada')+'\nUsado: '+info.battery.energy+'\nTotal: '+info.battery.energy_full+'\nIntegridade: '+info.battery.capacity+'%\nVoltagem: '+info.battery.voltage);
        $('#kt_mixed_widget_ssd_chart').attr('title', 'Total: '+formatBytes(parseInt(info.disks[0].available))+'\nUsado: '+formatBytes(parseInt(info.disks[0].used))+'\nRestante: '+formatBytes(parseInt(info.disks[0].available - info.disks[0].used)));
       
       setTimeout(function()
          {
            _updateDashboard();
          }, 2000);
    }).error(function(jqXHR, textStatus, errorThrown){
        $('#kt_content > div > div > div.row.mt-0.mt-lg-3 > div.col-xl-12 > div > div.card-body').css('filter','grayscale(100%)');
     });
}



function formatBytes(bytes)
{
    var sizes = ['Bytes', 'kB', 'MB', 'GB', 'TB'];
    if (bytes == 0) 
    {
        return 'n/a';
    }

var i = parseInt(Math.log(bytes) / Math.log(1024));
    return Math.round(bytes / Math.pow(1024, i)
) +' '+ sizes[i];
}
