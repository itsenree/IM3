fetch("https://im3.enrico-fusaro.ch/php/unload/schlimmste-stunde-aktuell.php?category=currentByHour")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("output-current")
        let labels = [];
        let values = [];

        data.forEach((element, index) => {
            let row = table.insertRow();
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            cell1.innerHTML = index + 1 + ".";
            cell2.innerHTML = element.stunde + " Uhr";
            cell3.innerHTML = element.verspaetungen;
            cell1.className = "rank";

            labels.push(element.stunde);
            values.push(Number(element.verspaetungen) || 0);
        });

        // Render bar chart using Chart.js
        try {
            const canvas = document.getElementById('chart-current');
            if (canvas && labels.length) {
                const ctx = canvas.getContext('2d');
                // Destroy previous chart instance if reused (avoid duplicates)
                if (canvas._chartInstance) canvas._chartInstance.destroy();
                canvas._chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Verspätungen',
                            data: values,
                            backgroundColor: '#D3AF3B',
                            fill: true
                        }]
                    },
                    options: {
                        indexAxis: 'x',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true},
                            x: {                    
                                ticks: {
                                    color: "#FFFFFF"
                                }}
                        },
                    }
                });
            }
        } catch (e) {
            console.error('Chart render error:', e);
        }
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

fetch("https://im3.enrico-fusaro.ch/php/unload/schlimmste-stunde-aktuell.php?category=records")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("output-records")

        data.forEach((element, index) => {
            let row = table.insertRow();
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);
            cell1.innerHTML = index + 1 + ".";
            cell2.innerHTML = element.stunde + " Uhr";
            cell3.innerHTML = element.verspaetungen;
            cell4.innerHTML = new Date(element.datum).toLocaleDateString();
            cell1.className = "rank";
        });
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

fetch("https://im3.enrico-fusaro.ch/php/unload/schlimmste-stunde-aktuell.php?category=winners")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("output-winners")

        data.forEach((element, index) => {
            let row = table.insertRow();
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            cell1.innerHTML = index + 1 + ".";
            cell2.innerHTML = element.stunde + " Uhr";
            cell3.innerHTML = element.preise;
            cell1.className = "rank";
        });
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });
