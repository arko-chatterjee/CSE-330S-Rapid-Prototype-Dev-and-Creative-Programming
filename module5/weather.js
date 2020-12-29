function fetchWeather(){
    fetch('https://classes.engineering.wustl.edu/cse330/content/weather_json.php', {
        method: "GET"
    })
    .then(res => res.json())
    .then(response => {
        document.getElementById("weather-loc").innerHTML = `<strong>${response.location.city}</strong>, ${response.location.state}`;
        document.getElementById("weather-humidity").textContent = response.atmosphere.humidity;
        document.getElementById("weather-temp").textContent = response.current.temp;
        document.getElementById("weather-tomorrow").src = `http://us.yimg.com/i/us/nws/weather/gr/${response.tomorrow.code}ds.png`
        document.getElementById("weather-dayaftertomorrow").src = `http://us.yimg.com/i/us/nws/weather/gr/${response.dayafter.code}ds.png`
        return console.log('Success:', JSON.stringify(response));
    })
    .catch(error => console.error('Error:',error));

}

document.addEventListener("DOMContentLoaded", fetchWeather, false);
document.getElementById("refreshButton").addEventListener("click", fetchWeather, false);
