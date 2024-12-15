(function ($) {
  var HD = {};

  var document = $(document);

  HD.MediaUploads = () => {
    $("#btn-upload-avatarAddress").click(function (e) {
      e.preventDefault();
      var mediaUpload = wp.media({
        title: "Upload IMG Address...",
        multiple: false,
      });
      //Media Open
      mediaUpload.open();

      mediaUpload.on("select", function () {
        var attment = mediaUpload.state().get("selection").first().toJSON();
        var urlImg = attment.url;
        $('#avatarAddress').val(urlImg);
        $('#preview_avatarAddress').attr('src', urlImg);;

      });
    });
  };

  document.ready(function () {
    HD.MediaUploads();
  });
})(jQuery);
