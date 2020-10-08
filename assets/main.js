import "./main.scss";

jQuery(document).ready(() => {
  if (0 < jQuery(".wjc-data-table").length) {
    jQuery(".wjc-data-table").each((key, tableElem) => {
      const nonce = jQuery(tableElem).attr("data-nonce");

      jQuery(".wjc-ajax-mask").removeClass("hide");

      jQuery.ajax({
        type: "post",
        dataType: "text",
        url: wjcWpAjax.ajax_url,
        data: {
          action: "wjc_ajax_func",
          nonce: nonce,
        },
        success: (res) => {
          jQuery(tableElem).html(res);

          jQuery(".wjc-ajax-mask").addClass("hide");
        },
      });
    });
  }
});
