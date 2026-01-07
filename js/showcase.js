// Date Function
function formatDateToDMY(dateString) {
    // Parse the date string into a Date object
    const date = new Date(dateString);

    // Extract day, month, and year
    const day = String(date.getDate()).padStart(2, '0'); // Ensure 2 digits
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
    const year = date.getFullYear();

    // Return the formatted date as d:m:y
    return `${day}:${month}:${year}`;
}

// Fetch commited Date
fetch("php/unload.php")
.then(response => response.json())
    .then(data => {
        let outputDateFromPull = document.getElementById("outputDateFromPull");

        outputDateFromPull.innerText = "Award Gewinner vom " + new Date(data[0].betriebstag).toLocaleDateString();

    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

// Fetch unzuverlässigste Zuglinie Data
fetch("php/unload/unzuverlässigste-zuglinie-aktuell.php")
.then(response => response.json())
    .then(data => {
        // console.log(data);
        let outputUnzZuglZuglinie = document.getElementById("outputUnzZuglZuglinie");
        let outputUnzZuglVersp = document.getElementById("outputUnzZuglVersp");

        outputUnzZuglZuglinie.innerText = data[0].zuglinie;
        outputUnzZuglVersp.innerText = data[0].verspaetungen + " Verspätungen";

    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

// Time function
function secondsToHMS(sec) {
    console.log(sec);
    
    sec = Number(sec);
    var h = Math.floor(sec / 3600);
    var m = Math.floor(sec % 3600 / 60);
    var s = Math.floor(sec % 3600 % 60);
    return h.toString().padStart(2, '0') + ":" + m.toString().padStart(2, '0') + ":" + s.toString().padStart(2, '0'); 
}

// Fetch längste Verspätung Data
fetch("php/unload/längste-verspätung-aktuell.php")
.then(response => response.json())
    .then(data => {
        // console.log(data);
        let outputLaengVerspZuglinie = document.getElementById("outputLaengVerspZuglinie");
        let outputLaengVerspVersp = document.getElementById("outputLaengVerspVersp");

        outputLaengVerspZuglinie.innerText = data[0].zuglinie;
        outputLaengVerspVersp.innerText = secondsToHMS(data[0].dauer_s) + " Verspätung";

    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

// Fetch unzuverlässigstes Verkehrsmittel Data
fetch("php/unload/unzuverlässigstes-verkehrsmittel-aktuell.php")
.then(response => response.json())
    .then(data => {
        // console.log(data);
        let outputUnzVerkVerk = document.getElementById("outputUnzVerkVerk");
        let outputUnzVerkVersp = document.getElementById("outputUnzVerkVersp");

        outputUnzVerkVerk.innerText = data[0].verkehrsmittel;
        outputUnzVerkVersp.innerText = data[0].verspaetungen + " Verspätungen";

    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

// Fetch ausfalllastigste Haltestelle Data
fetch("php/unload/ausfalllastigste-haltestelle-aktuell.php")
.then(response => response.json())
    .then(data => {
        // console.log(data);
        let outputAusfHaltHalt = document.getElementById("outputAusfHaltHalt");
        let outputAusfHaltVersp = document.getElementById("outputAusfHaltVersp");

        outputAusfHaltHalt.innerText = data[0].haltestelle;
        outputAusfHaltVersp.innerText = data[0].ausfaelle + " Ausfälle";

    }) 
    .catch(error => {
        console.error("Error during unload:", error);
});


// Fetch schlimmste Stunde Data
fetch("php/unload/schlimmste-stunde-aktuell.php")
.then(response => response.json())
    .then(data => {
        // console.log(data);
        let outputSchlimmsteStundeStunde = document.getElementById("outputSchlimmsteStundeStunde");
        let outputSchlimmsteStundeVersp = document.getElementById("outputSchlimmsteStundeVersp");

        outputSchlimmsteStundeStunde.innerText = data[0].stunde + ":00";
        outputSchlimmsteStundeVersp.innerText = data[0].verspaetungen + " Verspätungen";

    }) 
    .catch(error => {
        console.error("Error during unload:", error);
});


// Fetch verspätetste Haltestelle Data
fetch("php/unload/schlimmste-haltestelle-aktuell.php")
.then(response => response.json())
    .then(data => {
        // console.log(data);
        let outputSchlHaltHalt = document.getElementById("outputSchlHaltHalt");
        let outputSchlHaltVersp = document.getElementById("outputSchlHaltVersp");

        outputSchlHaltHalt.innerText = data[0].haltestelle;
        outputSchlHaltVersp.innerText = data[0].verspaetungen + " Verspätungen";

    }) 
    .catch(error => {
        console.error("Error during unload:", error);
});