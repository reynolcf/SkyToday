// assets/js/weather.js

import { wmoCode } from "/assets/js/iconUtil.js";

function convertTime(t) {
  const split = t.split(":");
  let hour = Number(split[0]);
  let minute = split[1];
  let unit = "AM";

  if (hour > 12) {
    unit = "PM";
    hour -= 12;
  } else if (hour === 12) {
    unit = "PM";
  } else if (hour === 0) {
    unit = "AM";
    hour = 12;
  }

  let time = `${hour}:${minute} ${unit}`;
  if (time.charAt(0) === "0") {
    time = time.slice(1);
  }

  return time;
}

$(document).ready(function () {
  $("#btnGetWeather").on("click", function () {
    getWeather();
  });

  // if they press enter instead
  $("#inputLocation").on("keypress", function (e) {
    if (e.which === 13) getWeather();
  });
});

// This will display the first history EVER looked up (we made it Tokyo), last resort so the page isnt blank
function loadFirstTime() {
  $.ajax({
    url: "/final.php/getTransactionInterface",
    method: "GET",
  })
    .done(function (data) {
      if (!data.result) {
        return;
      }

      // make the first thing tokyo
      const row = data.result[data.result.length - 1];
      buildWeather({
        query: row.request,
        weatherapi: JSON.parse(row.weather1),
        openmeteo: JSON.parse(row.weather2),
        openai: JSON.parse(row.openai1),
        locationData: JSON.parse(row.locationdata),
      });
    })

    .fail(function () {});
}

// Check if they already have previous look ups
function checkForPrevious() {
  let weatherData = sessionStorage.getItem("weatherData");

  if (weatherData === null) {
    // fill in a template
    loadFirstTime();
    return;
  }
  weatherData = JSON.parse(weatherData);

  buildWeather({
    query: weatherData.query,
    weatherapi: weatherData.weatherapi,
    openmeteo: weatherData.openmeteo,
    openai: weatherData.openai,
    locationData: weatherData.locationData,
  });

  sessionStorage.removeItem("weatherDataSQL");
}

// checking for SQL is the highest priority if they came from the History page
function checkForSQL() {
  let weatherData = sessionStorage.getItem("weatherDataSQL");

  if (weatherData === null) {
    // fill in a template
    checkForPrevious();
    return;
  }
  weatherData = JSON.parse(weatherData);
  const buildData = {
    query: weatherData.request,
    weatherapi: JSON.parse(weatherData.weather1),
    openmeteo: JSON.parse(weatherData.weather2),
    openai: JSON.parse(weatherData.openai1),
    locationData: JSON.parse(weatherData.locationdata),
  };
  buildWeather(buildData);

  sessionStorage.setItem("weatherData", JSON.stringify(buildData));
  sessionStorage.removeItem("weatherDataSQL");
}

function buildWeather(data) {
  $("#result").html("");
  $("#api-cards-row").empty();
  $("#result").empty();

  const wA = data.weatherapi;
  const wB = data.openmeteo;
  const openAI = data.openai;
  const locationData = data.locationData;

  // fill in the location tab
  renderLocation(locationData);

  // right card
  const html = buildWeatherApiCard(wA, openAI["summary1"]);
  buildOpenMeteoCard(wB, openAI["summary2"]);
  $("#result").html(html);
}
function getWeather() {
  const query = $("#inputLocation").val().trim();

  if (query === "") {
    $("#result").html("Please enter a ZIP code or place.");
    return;
  }

  $("#loadingSpinner").removeClass("d-none");
  $.ajax({
    url: "api/weather.php",
    method: "GET",
    data: { query: query }, // ZIP or address
  })
    .done(function (data) {
      if (!data.success) {
        $("#result").html("Error: " + data.error);
        return;
      }

      $("#loadingSpinner").addClass("d-none");
      const wA = data.weatherapi;
      const wB = data.openmeteo;
      const openAI = data.openai;
      const locationData = data.locationData;
      const buildData = {
        query: query,
        weatherapi: wA,
        openmeteo: wB,
        openai: openAI,
        locationData: locationData,
      };
      buildWeather(buildData);

      sessionStorage.setItem("weatherData", JSON.stringify(buildData));
      // log to sql
      $.ajax({
        url: "/final.php/addTransaction",
        method: "POST",
        data: {
          request: query,
          weather1: JSON.stringify(wA),
          weather2: JSON.stringify(wB),
          openai1: JSON.stringify(openAI),
          locationdata: JSON.stringify(locationData),
        },
        dataType: "json",
      })
        .done(function (response) {
          console.log("Success: ", response);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
          console.error("Failed:", textStatus, errorThrown);
          console.log("Response:", jqXHR.responseText);
        });
    })
    .fail(function (res, textStatus, errorThrown) {
      $("#loadingSpinner").addClass("d-none");
      $("#api-cards-row").empty();
      $("#result").html(
        "AJAX error: " + textStatus + " " + (errorThrown || "")
      );
      console.error("responseText:", res.responseText);
    });
}

// the little thing like "Feels like: 27 or humidity tag"
function createInfoTag($container, value, tooltip, img) {
  const $div = $(`
    <div class="col-auto api-info" data-bs-toggle="tooltip" data-bs-placement="top">
      <img src="${img}" width="18" />
      <span>${value}</span>
    </div>
  `);

  $div.attr("data-bs-title", tooltip);
  $container.append($div);
  new bootstrap.Tooltip($div[0]);
}

function createAllInfoTags($infoContainer, data) {
  const feelslike_f = data.feelslike_f;
  const precip_in = data.precip_in;
  const wind_mph = data.wind_mph;
  const gust_mph = data.gust_mph;
  const uv = data.uv;
  const humidity = data.humidity;
  const pressure_in = data.pressure_in;
  const snow = data.snow;

  // build the little information tabs (like feels like, humidity, etc.)

  if (feelslike_f !== undefined) {
    createInfoTag(
      $infoContainer,
      `Feels like: ${feelslike_f}°`,
      "What the temperature feels like",
      "/assets/images/icons/temp.png"
    );
  }

  if (precip_in !== undefined) {
    createInfoTag(
      $infoContainer,
      `${precip_in} in`,
      "Precipitation (inches)",
      "/assets/images/icons/rain.png"
    );
  }

  if (wind_mph !== undefined && gust_mph !== undefined) {
    createInfoTag(
      $infoContainer,
      `${wind_mph} mph / ${gust_mph} mph gusts`,
      "Wind (current / gusts)",
      "/assets/images/icons/wind.png"
    );
  }

  if (uv !== undefined) {
    createInfoTag(
      $infoContainer,
      `${uv}`,
      "UV index",
      "/assets/images/icons/uv.png"
    );
  }

  if (humidity !== undefined) {
    createInfoTag(
      $infoContainer,
      `${humidity}%`,
      "Humidity (%)",
      "/assets/images/icons/humidity.png"
    );
  }

  if (pressure_in !== undefined) {
    createInfoTag(
      $infoContainer,
      `${pressure_in} in`,
      "Pressure (in)",
      "/assets/images/icons/pressure.png"
    );
  }

  if (snow !== undefined) {
    createInfoTag(
      $infoContainer,
      `${snow} cm`,
      "Snow (cm)",
      "/assets/images/icons/snow.png"
    );
  }
}

// Today, Tuesday, Wednesday, etc.
function createForecastHeader($container, $forecastCard, data, currentDate) {
  const condition_icon = data.condition_icon || "";
  const condition_text = data.condition_text || "-";
  const high = data.high || "-";
  const low = data.low || "-";
  const precip_in = data.precip_in || "-";
  const wind_mph = data.wind_mph || "-";
  const date = data.date || "2025-12-08";

  const dateObject = new Date(date);
  let weekday = dateObject.toLocaleDateString("en-us", {
    weekday: "long",
  });

  // if (date === currentDate) {
  //   weekday = "Today";
  // }

  const $div = $(`
  <div class="card api-card card-body shadow forecast-card mb-2" style="font-size: .9rem;">
    <div
      class="d-flex justify-content-center align-items-center forecast-header"
    >
      <div
        class="d-flex justify-content-left align-items-center"
        style="flex: 0 0 30%"
      >
        <div class="fw-bold" style="flex: 0 0 60%">${weekday}</div>

        <div>
          <img
            src="${condition_icon}"
            alt="${condition_text}"
            style="width: 30px"
          />
        </div>
      </div>

      <div
        class="d-flex justify-content-around align-items-center"
        style="flex: 1"
      >
        <div class="text-nowrap">
          <img src="/assets/images/icons/swap.png" height="17" />
          H: ${Math.round(high)}° L: ${Math.round(low)}°
        </div>

        <div class="text-nowrap">
          <img src="/assets/images/icons/rain.png" height="17" />
          ${precip_in} in
        </div>

        <div class="text-nowrap" style="width: fit-content">
          <img src="/assets/images/icons/wind.png" height="17" />
          <span>${wind_mph} mph</span>
        </div>
      </div>
    </div>

    <div class="forecast-sub-container" style="">
      <!-- fill in the stuff here -->
    </div>
  </div>`);

  const $subContainer = $div.find(".forecast-sub-container");
  $subContainer.append($forecastCard);

  $div.on("click", function () {
    $subContainer.toggleClass("expanded");
  });
  $container.append($div);
}

function renderLocation(locationData) {
  const name = locationData.name;
  const lat = locationData.lat;
  const lon = locationData.lon;
  const elevation = locationData.elevation;
  const state = locationData.state;
  const country = locationData.country;
  const population = locationData.population;

  // sometimes state is nil, this checks for that
  let locationText = "";
  if (state && country) {
    locationText = `${state}, ${country}`;
  } else if (!state && country) {
    locationText = `${country}`;
  }

  if (population) {
    locationText += `. Population: ${population}.`;
  }

  const locationHtml = `
    <h2 class="location-text">${name}</h2>
     <div class="text-muted" id="location-desc">
       ${locationText}
     </div>
    <div class="text-muted" id="location-coords">
      Coordinates: ${lat.toFixed(2)}, ${lon.toFixed(
    2
  )}. Elevation: ${elevation} ft.
    </div>
  `;

  $("#location-container").html(locationHtml);
}

function buildHourTab($parent, data) {
  const time = data.time;
  const icon = data.icon;
  const text = data.text;
  const temp_f = data.temp_f;

  const html = `
  <div 
    class="scroll-hour flex-shrink-0 d-flex flex-column align-items-center justify-content-center"
    style="width: 60px;"
  >
    <div class="" style="font-size: .7rem; font-weight: 600" hour="${
      time.split(":")[0]
    }"">${convertTime(time)}</div>

    <img 
      src="${icon}"
      alt="${text}"
      style="width:24px; height:24px;"
    />

    <div>${Math.round(temp_f)}°F</div>

    <div 
      class="small text-center text-nowrap text-muted" 
      style="width: 90%; font-size: .6rem; text-overflow: ellipsis; overflow: hidden;"
    >
      ${text.trim()}
    </div>
  </div>
`;
  const $div = $(html);
  $parent.append($div);
}

// this is the data that goes in a forecast
function buildForecastCard(data) {
  const cardHtml = `

  <div class="mt-2">

      <hr/>
      <div
        class="d-flex flex-nowrap overflow-auto api-component live-scroll p-1"
        style="flex: 1"
      >
        <!-- hourly stuff will go here -->
      </div>


    <div class="container mt-4">
      <div class="row gap-2 js-current-info-container">
        <!-- info chips (createInfoTag) go here -->
      </div>
    </div>

    </div>
  `;

  return $(cardHtml);
}

function buildStandardCard(data, openAiResponse) {
  const temp_f = data.temp_f;
  const high = data.high;
  const low = data.low;
  const condition_text = data.condition_text;
  const image = data.image;

  const cardHtml = `
    
      <div class="col-12 col-xl-6 gy-5">

        <div class="hr-with-image text-center mb-5">
        <hr/>
          <img
            src=${image}
            alt="API logo"
            height="40"
          />
          <hr/>
        </div>
       
             <div class="fw-bold fs-6 d-flex text-center justify-content-center align-items-center mb-3 mt-4"
          >
            <img
              src="/assets/images/icons/clock1.png"
              height="26"
              class="me-2"
            />
            Current Conditions
          </div>

      <div class="main-card d-flex flex-column gap-2">
        <!-- CURRENT CONDITIONS CARD -->
        <div class="card card-body api-card shadow">
          

          <div class="first-section d-flex align-items-stretch">
            <div
              style="flex: 0 0 10%"
              class="d-flex flex-column justify-content-center"
            >
              <div class="api-temp">
                <span class="js-api-temp">${Math.round(temp_f)}°</span>
              </div>
              <div class="api-temp-high d-flex align-items-center gap-1">
                <img src="/assets/images/icons/swap.png" width="19" />
                <span class="js-api-temp-high">
                  H: ${Math.round(high)}° L: ${Math.round(low)}°
                </span>
              </div>
            </div>

            <div
              class="d-flex flex-nowrap overflow-auto api-component live-scroll p-1"
              style="flex: 1"
            >
              <!-- hourly stuff will go here -->
            </div>
          </div>

          <div class="ai-sect">
            <p>
              <img
                src="/assets/images/icons/ai.png"
                width="23"
              />
              <span class="ai-summary js-ai-summary">
                ${openAiResponse || condition_text}
              </span>
            </p>
          </div>

          <div class="container">
            <div class="row gap-2 js-current-info-container">
              <!-- info chips (createInfoTag) go here -->
            </div>
          </div>
        </div>

        <div
            class="fw-bold fs-6 d-flex text-center justify-content-center align-items-center mb-1 mt-4"
          >
            <img
              src="/assets/images/icons/arrow.png"
              height="26"
              class="me-2"
            />
            Forecast
          </div>

        <hr class="mt-0"/>

         
        
      </div>
    </div>
  `;

  return cardHtml;
}

function buildWeatherApiCard(data, openAiResponse) {
  const current = data.current;
  const temp_f = current.temp_f;
  const feelslike_f = current.feelslike_f;
  const precip_in = current.precip_in;
  const wind_mph = current.wind_mph;
  const gust_mph = current.gust_mph;
  const currentUv = current.uv;
  const humidity = current.humidity;
  const pressure_in = current.pressure_in;
  const condition_text = current.condition.text;
  const condition_icon = current.condition.icon;

  const forecast = data.forecast.forecastday[0];
  const high = forecast.day.maxtemp_f;
  const low = forecast.day.mintemp_f;

  // build the base card
  const cardHtml = buildStandardCard(
    {
      temp_f: temp_f,
      high: high,
      low: low,
      condition_text: condition_text,
      image: "/assets/images/icons/weatherAPI.png",
    },
    openAiResponse
  );

  // turn the html thing into a jquery element
  const $cardCol = $(cardHtml);
  const $infoContainer = $cardCol.find(".js-current-info-container");
  const $scrollThing = $cardCol.find(".live-scroll");

  // build the hour scroll thing
  $.each(data.forecast.forecastday[0].hour, function (index, hour) {
    buildHourTab($scrollThing, {
      time: hour.time.split(" ")[1],
      icon: hour.condition.icon,
      text: hour.condition.text,
      temp_f: hour.temp_f,
    });
  });

  // build the little information tabs (like feels like, humidity, etc.)
  createAllInfoTags($infoContainer, {
    feelslike_f: feelslike_f,
    precip_in: precip_in,
    wind_mph: wind_mph,
    gust_mph: gust_mph,
    uv: currentUv,
    humidity: humidity,
    pressure_in: pressure_in,
  });

  const currentDate = "2025-12-08";
  const currentHour = "16";

  const currentTime = $.each(
    data.forecast.forecastday,
    function (index, forecastData) {
      const date = forecastData.date;
      const dayData = forecastData.day;
      const condition_icon = dayData.condition.icon;
      const condition_text = dayData.condition.text;
      const high = dayData.maxtemp_f;
      const low = dayData.mintemp_f;
      const totalprecip = dayData.totalprecip_in;
      const maxwind = dayData.maxwind_mph;
      const avg_uv = dayData.uv;
      const avghumidity = dayData.avghumidity;
      const totalsnow = dayData.totalsnow_cm;

      const $forecastCard = buildForecastCard();
      const $forecastScroll = $forecastCard.find(".live-scroll");
      const $forecastInfoContainer = $forecastCard.find(
        ".js-current-info-container"
      );

      // build the hour scroll thing
      $.each(forecastData.hour, function (index, hour) {
        buildHourTab($forecastScroll, {
          time: hour.time.split(" ")[1],
          icon: hour.condition.icon,
          text: hour.condition.text,
          temp_f: hour.temp_f,
        });
      });

      createAllInfoTags($forecastInfoContainer, {
        precip_in: totalprecip,
        wind_mph: maxwind,
        uv: avg_uv,
        humidity: avghumidity,
        snow: totalsnow,
      });

      createForecastHeader(
        $cardCol,
        $forecastCard,
        {
          condition_icon: condition_icon,
          condition_text: condition_text,
          high: high,
          low: low,
          precip_in: totalprecip,
          wind_mph: maxwind,
          date: date,
        },
        currentDate
      );
    }
  );

  // add it to the main page
  $("#api-cards-row").append($cardCol);
}

// Open-Meteo version of your card builder
function buildOpenMeteoCard(data, openAiResponse) {
  // ===== CURRENT CONDITIONS =====
  // Open-Meteo: current fields live in `data.current`
  const current = data.current || {};

  const temp_f = current.temperature_2m;
  const feelslike_f = current.apparent_temperature;
  const precip_in = current.precipitation;
  const wind_mph = current.wind_speed_10m;
  const gust_mph = current.wind_gusts_10m;
  const humidity = current.relative_humidity_2m;
  const pressure_in = (current.pressure_msl * 0.02953).toFixed(2); // hPa to inHg

  const daily = data.daily || {};
  const dailyTimes = daily.time || [];

  const todayIndex = 0;
  const high = daily.temperature_2m_max[todayIndex];
  const low = daily.temperature_2m_min[todayIndex];

  const currentWmoData = wmoCode(current.weather_code);

  const cardHtml = buildStandardCard(
    {
      temp_f: temp_f,
      high: high,
      low: low,
      condition_text: currentWmoData.description,
      image: "/assets/images/icons/openMeteo.png",
    },
    openAiResponse
  );

  const $cardCol = $(cardHtml);
  const $infoContainer = $cardCol.find(".js-current-info-container");
  const $scrollThing = $cardCol.find(".live-scroll");

  const hourly = data.hourly || {};
  const hourlyTimes = hourly.time || [];
  const hourlyTemps = hourly.temperature_2m || [];

  // helper methods
  function extractDate(d) {
    return d ? d.split("T")[0] : "";
  }
  function extractHour(h) {
    return h ? h.split("T")[1] : "";
  }

  const todayDate = dailyTimes[todayIndex]; // "YYYY-MM-DD"

  $.each(hourlyTimes, function (index, time) {
    if (extractDate(time) !== todayDate) return; // only hours for "today"

    const wmoCodeHourly = wmoCode(hourly.weather_code[index]);

    buildHourTab($scrollThing, {
      time: extractHour(time),
      icon: wmoCodeHourly.icon,
      text: wmoCodeHourly.description,
      temp_f: hourlyTemps[index],
    });
  });

  createAllInfoTags($infoContainer, {
    feelslike_f: feelslike_f,
    precip_in: precip_in,
    wind_mph: wind_mph,
    gust_mph: gust_mph,
    humidity: humidity,
    pressure_in: pressure_in,
  });

  const currentIsoTime = current.time; // it does date::time
  const currentDate = currentIsoTime
    ? extractDate(currentIsoTime)
    : todayDate || "2025-12-08";
  const currentHour = currentIsoTime
    ? extractHour(currentIsoTime).split(":")[0]
    : "16";

  // make the cards
  const currentTime = $.each(dailyTimes, function (dayIndex, date) {
    const dayData = {
      maxtemp_f: daily.temperature_2m_max[dayIndex],
      mintemp_f: daily.temperature_2m_min[dayIndex],
      totalprecip_in: daily.precipitation_sum[dayIndex],
      maxwind_mph: daily.wind_speed_10m_max[dayIndex],
      uv: daily.uv_index_max[dayIndex],

      totalsnow_cm: daily.snowfall_sum ? daily.snowfall_sum[dayIndex] : null,
      weather_code: daily.weather_code ? daily.weather_code[dayIndex] : null,
    };

    const high = dayData.maxtemp_f;
    const low = dayData.mintemp_f;
    const totalprecip = dayData.totalprecip_in;
    const maxwind = dayData.maxwind_mph;
    const avg_uv = dayData.uv;
    const avghumidity = dayData.avghumidity;
    const totalsnow = dayData.totalsnow_cm;

    const $forecastCard = buildForecastCard();
    const $forecastScroll = $forecastCard.find(".live-scroll");
    const $forecastInfoContainer = $forecastCard.find(
      ".js-current-info-container"
    );

    $.each(hourlyTimes, function (hIndex, hTime) {
      if (extractDate(hTime) !== date) return;

      const wmoDataHour = wmoCode(hourly.weather_code[hIndex]);

      buildHourTab($forecastScroll, {
        time: extractHour(hTime),
        icon: wmoDataHour.icon,
        text: wmoDataHour.description,
        temp_f: hourlyTemps[hIndex],
      });
    });

    createAllInfoTags($forecastInfoContainer, {
      precip_in: totalprecip,
      wind_mph: maxwind,
      uv: avg_uv,
      humidity: avghumidity,
      snow: totalsnow,
    });

    const wmoCodeDay = wmoCode(dayData.weather_code);

    createForecastHeader(
      $cardCol,
      $forecastCard,
      {
        condition_icon: wmoCodeDay.icon,
        condition_text: wmoCodeDay.description,
        high: high,
        low: low,
        precip_in: totalprecip,
        wind_mph: maxwind,
        date: date,
      },
      currentDate
    );
  });

  $("#api-cards-row").append($cardCol);
}

checkForSQL();
