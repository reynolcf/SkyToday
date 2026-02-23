function createHeader(data, sqlData) {
  const num = data.num || "N/A";
  const request = data.request || "N/A";

  const date = new Date(data.date.replace(" ", "T"));
  const newDate = date.toLocaleString("en-US", {
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  });

  const html = ` <div
          class="card card-body d-flex flex-column flex-sm-row justify-content-between align-items-center shadow w-100 gap-sm-0 gap-2 mb-3"
        >
          <div class="history-header" style="font-size: 1.2rem">Lookup #${num}</div>
          <hr />
          <div class="history-header d-flex align-items-center gap-2" style="font-weight: 400">
            ${newDate} <span class="text-muted" style="font-size: .7rem">UTC</span>
          </div>
          <div class="history-header" style="font-weight: 400">
            ${request}
          </div>

          <button class="view-button" style="">View</button>
        </div>
      </div>`;

  $div = $(html);

  $div.find(".view-button").on("click", function () {
    // weather.js will handle the session storage
    sessionStorage.setItem("weatherDataSQL", JSON.stringify(sqlData));
    window.location.href = "/dashboard.php";
  });
  return $div;
}

function getHistory() {
  $.ajax({
    url: "/final.php/getTransactionInterface",
    method: "GET",
  })
    .done(function (data) {
      if (!data.result) {
        return;
      }
      console.log(data);

      $.each(data.result, function (index, row) {
        const $header = createHeader(
          {
            num: row.transactionsid,
            date: row.timestamp,
            request: row.request,
          },
          row
        );
        $("#history-container").append($header);
      });
    })

    .fail(function () {});
}

getHistory();
