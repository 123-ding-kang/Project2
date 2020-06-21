let cities = {};
function setCity(country, city) {
    let couRegCity;
    let CouRegISO = country.value;
    if(CouRegISO ==='default') {
        city.options[0] = new Option();
        city.options[0].text = '-Cities-';
        city.options[0].value = 'default';
        return;
    }
    city.length=1;

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState===4 && xmlhttp.status===200)
        {

            couRegCity = xmlhttp.response;
            let CouRegJSON = JSON.parse(couRegCity);

            for(let i = 0;i < CouRegJSON.length;i++){
                cities[i] = {};
                cities[i]['CityCode'] = CouRegJSON[i].GeoNameID;
                cities[i]['Name'] = CouRegJSON[i].AsciiName;
            }

            for(let i=0; i< CouRegJSON.length; i++) {

                let j = i+1;
                city.options[j] = new Option();
                city.options[j].text = cities[i]['Name'];
                city.options[j].value = cities[i]['CityCode'];
            }
        }
    }
    xmlhttp.open("GET",'../src/GetCityBro.php?ISO='+CouRegISO,true);
    xmlhttp.send();
}