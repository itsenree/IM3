fetch("https://im3.enrico-fusaro.ch/php/unload.php")
    .then(response => response.json())
    .then(data => {
        let output = document.getElementById("output")

        let delaySum = 0;
        data.forEach(element => {

            let delay = (new Date(element.an_prognose) - new Date(element.ankunftszeit)) /1000;
            if (delay > 0) delaySum += delay;
            output.insertAdjacentHTML('beforeend', '<div class="delay">' + element.betriebstag + '<br>' + element.zuglinie + ' - ' + element.verkehrsmittel + '<br>' + element.haltestelle + '<br> Erwartete Ankunft: ' + element.ankunftszeit + '<br> Tatsächliche Ankunft: ' + element.an_prognose + '<br><div class="delay-time">'+ secondsToHMS(delay) + '</div>' + '<br>' + element.ausfall + '</div>');
        });
        let avgDelay = delaySum / data.length;
        
        document.getElementById("average-delay").textContent = secondsToHMS(avgDelay);
    }) 
    .catch(error => {
        console.error("Error during unload:", error);
    });

function secondsToHMS(seconds) {
    seconds = Number(seconds);
    var h = Math.floor(seconds / 3600);
    var m = Math.floor(seconds % 3600 / 60);
    var s = Math.floor(seconds % 3600 % 60);

    var hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
    var mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
    var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
    return hDisplay + mDisplay + sDisplay; 
}