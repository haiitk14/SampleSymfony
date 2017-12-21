function contour(chartId, coordinatesData, barData, layoutData) {
	var data = [ {
			x: JSON.parse(coordinatesData['x']),
			y: JSON.parse(coordinatesData['y']),
			z: JSON.parse(coordinatesData['z']),
		type: 'contour',
		colorscale: 'Dfmrainbow',
		colorbar:{
		    thickness: 50,
			thicknessmode: 'pixels',
			len: 1,
			lenmode: 'fraction',
			outlinewidth: 0,
			borderwidth: 1
			},
		contours: {
	      start: barData['start'],
	      end: barData['end'],
	      size: barData['size'],
	      coloring: 'heatmap'
	    }
	}];

	var layout = {
	  title: layoutData['title'],
	  height: layoutData['height'],
	}

	Plotly.newPlot(chartId, data, layout);
}

function chart(chartId, chartType, chartData, optionsData) {
	var chartData = chartData;
    var ctx = document.getElementById(chartId).getContext("2d");
    if(chartType == 'bar'){
    	new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: optionsData
        });
    }else{
    	new Chart.Line(ctx, {
            data: chartData,
            options: optionsData
        });
    }
        
 
}
