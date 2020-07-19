import "./main.scss";

jQuery(document).ready(() => {
  if (0 < jQuery(".wjc-data-table").length) {
    jQuery(".wjc-data-table").each((key, tableElem) => {
      const nonce = jQuery(tableElem).attr("data-nonce");

      jQuery(".wjc-ajax-mask").removeClass("hide");

      jQuery.ajax({
        type: "post",
        dataType: "json",
        url: wjcWpAjax.ajax_url,
        data: {
          action: "wjc_ajax_func",
          nonce: nonce,
        },
        success: (res) => {
          renderTable(tableElem, res);

          jQuery(".wjc-ajax-mask").addClass("hide");
        },
      });
    });
  }

  jQuery("#wjc-refresh-button").click(function () {
    const nonce = jQuery(this).attr("data-nonce");
    jQuery(".wjc-ajax-mask").removeClass("hide");

    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: wjcWpAjax.ajax_url,
      data: {
        action: "wjc_refresh_ajax_func",
        nonce: nonce,
      },
      success: (res) => {
        jQuery(".wjc-data-table").each((key, tableElem) => {
          renderTable(tableElem, res);
        });

        jQuery(".wjc-ajax-mask").addClass("hide");
      },
    });
  });

  function renderTable(tableElem, res) {
    let tableHtml = "";

    tableHtml += "<table>";
    tableHtml += `<caption>${res.title}</caption>`;

    tableHtml += "<thead><tr>";

    jQuery.each(res.data.headers, (key, val) => {
      tableHtml += `<th>${val}</th>`;
    });

    tableHtml += "</tr></thead>";

    tableHtml += "<tbody>";
    jQuery.each(res.data.rows, (rowKey, row) => {
      let dateCel = new Date(row.date * 1000);
      let dateVal =
        dateCel.getMonth() +
        1 +
        "/" +
        dateCel.getDate() +
        "/" +
        dateCel.getFullYear() +
        " " +
        dateCel.getHours() +
        ":" +
        dateCel.getMinutes() +
        ":" +
        dateCel.getSeconds();

      tableHtml += `
        <tr>
          <td>${row.id}</td>
          <td>${row.fname}</td>
          <td>${row.lname}</td>
          <td>${row.email}</td>
          <td>${dateVal}</td>
        </tr>
      `;
    });
    tableHtml += "</tbody>";

    tableHtml += "</table>";

    jQuery(tableElem).html(tableHtml);
  }
});
