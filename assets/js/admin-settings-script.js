jQuery(document).ready(function ($) {
  const max_length = 200;

  const lengthCheck = () => {
    let text = $('#trufi_site_description').val();
    let length = text.length;

    if (length > max_length) {
      $('#trufi_site_description').val(text.substr(0, max_length));
      $('#chars_left').text(0);
    } else {
      var remaining = max_length - length;
      $('#chars_left').text(remaining);
    }
  };

  $('.upload_image_button').click(function (e) {
    e.preventDefault();
    var button = $(this);
    var custom_uploader = wp.media({
      title: 'Select an image',
      library: {
        type: 'image'
      },
      button: {
        text: 'Use this image'
      },
      multiple: false
    }).on('select', function () {
      var attachment = custom_uploader.state().get('selection').first().toJSON();
      button.siblings('input:hidden').val(attachment.url);
      var image_preview = button.siblings('img');
      image_preview.attr('src', attachment.url);
      image_preview.show();
    }).open();
  });

  $('#trufi_site_description').on('input', lengthCheck);
  $('.line-color-field').wpColorPicker();
  lengthCheck();
});