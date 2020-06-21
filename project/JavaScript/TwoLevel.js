let cities = {};
let CouRegSelection = document.getElementById('Countries');
CouRegSelection.addEventListener("load",setCity(CouRegSelection,CouRegSelection.form.Cities));
CouRegSelection.addEventListener("change",setCity(CouRegSelection,CouRegSelection.form.Cities));

function setCity(country,city) {
    city.options[0] = new Option();
    city.options[0].text = '-Cities-';
    city.options[0].value = 'default';
    city.options[0].setAttribute("selected","selected");

    let mode = country.classList[0];
    mode = mode.substring(1);
    let couRegCity;
    let CouRegISO = country.value;


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
                if(mode !== null && city.options[j].value === mode){
                    city.options[j].setAttribute("selected","selected");
                }
            }

        }
    }
    xmlhttp.open("GET",'../src/GetCityUP.php?ISO='+CouRegISO,true);
    xmlhttp.send();
}