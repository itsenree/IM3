fetch("https://im3.enrico-fusaro.ch/php/unload/schlimmste-stunde-aktuell.php")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("output-current")

        data.forEach((element, index) => {
            let row = table.insertRow();
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            cell1.innerHTML = index + 1 + ".";
            cell2.innerHTML = element.stunde;
            cell3.innerHTML = element.verspaetungen;
            cell1.className = "rank";
        });
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
            cell2.innerHTML = element.stunde;
            cell3.innerHTML = element.verspaetungen;
            cell4.innerHTML = element.datum;
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
            cell2.innerHTML = element.stunde;
            cell3.innerHTML = element.preise;
            cell1.className = "rank";
        });
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });
