fetch("https://im3.enrico-fusaro.ch/php/unload/längste-verspätung-aktuell.php")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("output-current")

        data.forEach((element, index) => {
            let row = table.insertRow();
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            cell1.innerHTML = index + 1 + ".";
            cell2.innerHTML = element.zuglinie;
            cell3.innerHTML = secondsToHMS(element.dauer_s);
            cell1.className = "rank";
        });
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

fetch("https://im3.enrico-fusaro.ch/php/unload/längste-verspätung-aktuell.php?category=records")
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
            cell2.innerHTML = element.zuglinie;
            cell3.innerHTML = secondsToHMS(element.dauer_s);
            cell4.innerHTML = new Date(element.datum).toLocaleDateString();
            cell1.className = "rank";
        });
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

fetch("https://im3.enrico-fusaro.ch/php/unload/längste-verspätung-aktuell.php?category=winners")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("output-winners")

        data.forEach((element, index) => {
            let row = table.insertRow();
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            cell1.innerHTML = index + 1 + ".";
            cell2.innerHTML = element.zuglinie;
            cell3.innerHTML = element.preise;
            cell1.className = "rank";
        });
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });



function secondsToHMS(sec) {
    sec = Number(sec);
    var h = Math.floor(sec / 3600);
    var m = Math.floor(sec % 3600 / 60);
    var s = Math.floor(sec % 3600 % 60);

    return h.toString().padStart(2, '0') + ":" + m.toString().padStart(2, '0') + ":" + s.toString().padStart(2, '0'); 
}