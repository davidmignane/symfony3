/* const routes = require('web/js/fos_js_routes.json');
 import Routing from 'vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

 Routing.setRoutingData(routes);
var url = Routing.generate('todo_index');
console.log(url);*/
/*
   $.get("{{ path('todo_index') }}", function(todos){
        alert("Data: " + todos);
        console.log("Data: " + todos);
    });*/
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx,{
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [{
            label: "graphique ",
            backgroundColor: 'rgb(255,99,132)',
            borderColor: 'rgb(255,99,132)',
            data: [0, 10, 5, 2, 20, 30, 45],
        }]
    },

    // Configuration options go here
    options: {
        title: {
            display: true,
            text: 'graph',
            fontStyle:'bold'
        }
    }
});