$("#chartNewLeagues").sparkline([2,4,4,6,8,5], {
    type: 'line',
    width: '100%',
    height: '130',
    lineColor: '#00c292',
    fillColor: 'rgba(0, 194, 146, 0.2)',
    highlightLineColor: 'rgba(0, 0, 0, 0.2)',
    highlightSpotColor: '#00c292'
});

$("#chartNewTeams").sparkline([0,2,8,6,8,5], {
    type: 'line',
    width: '100%',
    height: '130',
    lineColor: '#03a9f3',
    fillColor: 'rgba(3, 169, 243, 0.2)',
    highlightLineColor: 'rgba(0, 0, 0, 0.2)',
    highlightSpotColor: '#03a9f3'
});

$("#chartNewUsers").sparkline([2,4,4,6,8,5], {
    type: 'line',
    width: '100%',
    height: '130',
    lineColor: '#fb9678',
    fillColor: 'rgba(251, 150, 120, 0.2)',
    highlightLineColor: 'rgba(0, 0, 0, 0.2)',
    highlightSpotColor: '#fb9678'
});

Morris.Area({
    element: 'morris-area-chart2',
    data: [{
        period: '2010',
        subscriptions: 0,
    }, {
        period: '2011',
        subscriptions: 130,
    }, {
        period: '2012',
        subscriptions: 30,
    }, {
        period: '2013',
        subscriptions: 30,
    }, {
        period: '2014',
        subscriptions: 200,
    }, {
        period: '2015',
        subscriptions: 105,
    }],
    xkey: 'period',
    ykeys: ['subscriptions'],
    labels: ['Subscriptions'],
    pointSize: 0,
    fillOpacity: 0.4,
    pointStrokeColors:['#01c0c8'],
    behaveLikeLine: true,
    gridLineColor: '#e0e0e0',
    lineWidth: 0,
    smooth: true,
    hideHover: 'auto',
    lineColors: ['#01c0c8'],
    resize: true
});

// Morris donut chart
Morris.Donut({
    element: 'morris-donut-chart',
    data: [{
        label: "Active",
        value: 8500,
    },{
        label: "Deactivated",
        value: 3630,
    },{
        label: "Free Trial",
        value: 4870
    }],
    resize: true,
    colors:['#fb9678', '#01c0c8', '#4F5467']
});