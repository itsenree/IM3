fetch("https://im3.enrico-fusaro.ch/php/unload/unzuverlässigste-zuglinie-aktuell.php")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("output")

        data.forEach((element, index) => {
            let delay = (new Date(element.an_prognose) - new Date(element.ankunftszeit)) /1000;
            if (delay > 0) delaySum += delay;
            let row = table.insertRow();
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            cell1.innerHTML = index + 1 + ".";
            cell2.innerHTML = element.zuglinie;
            cell3.innerHTML = element.verspaetungen;
        });
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });