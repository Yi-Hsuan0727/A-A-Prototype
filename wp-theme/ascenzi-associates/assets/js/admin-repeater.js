/**
 * Generic admin repeater + media-picker behavior for the Ascenzi metabox
 * framework. Works via event delegation so dynamically-added rows need no
 * re-binding.
 */
(function () {
  'use strict';

  function nextIndex(container) {
    var rows = container.querySelectorAll(':scope > .ascenzi-repeater__row');
    return rows.length;
  }

  function cloneRow(repeater) {
    var tpl = repeater.querySelector('.ascenzi-repeater__template');
    var rowsWrap = repeater.querySelector('.ascenzi-repeater__rows');
    var max = parseInt(repeater.getAttribute('data-max'), 10) || 0;
    if (max && rowsWrap.querySelectorAll(':scope > .ascenzi-repeater__row').length >= max) {
      return;
    }
    var index = nextIndex(rowsWrap);
    var html = tpl.innerHTML.replace(/__i__/g, index);
    var wrapper = document.createElement('div');
    wrapper.innerHTML = html.trim();
    var row = wrapper.firstElementChild;
    rowsWrap.appendChild(row);
  }

  document.addEventListener('click', function (e) {
    var addBtn = e.target.closest('.ascenzi-repeater__add');
    if (addBtn) {
      e.preventDefault();
      cloneRow(addBtn.closest('.ascenzi-repeater'));
      return;
    }
    var removeBtn = e.target.closest('.ascenzi-repeater__remove');
    if (removeBtn) {
      e.preventDefault();
      var row = removeBtn.closest('.ascenzi-repeater__row');
      if (row) { row.remove(); }
      return;
    }
    var mediaBtn = e.target.closest('.ascenzi-media-btn');
    if (mediaBtn) {
      e.preventDefault();
      var field = mediaBtn.closest('.ascenzi-image-field');
      var input = field.querySelector('input[type="hidden"]');
      var preview = field.querySelector('.ascenzi-image-preview');
      if (!window.wp || !wp.media) { return; }
      var frame = wp.media({ title: 'Select image', multiple: false, library: { type: 'image' } });
      frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        input.value = attachment.id;
        var url = (attachment.sizes && attachment.sizes.medium) ? attachment.sizes.medium.url : attachment.url;
        preview.innerHTML = '<img src="' + url + '">';
      });
      frame.open();
      return;
    }
    var removeMedia = e.target.closest('.ascenzi-media-remove');
    if (removeMedia) {
      e.preventDefault();
      var f2 = removeMedia.closest('.ascenzi-image-field');
      f2.querySelector('input[type="hidden"]').value = '';
      f2.querySelector('.ascenzi-image-preview').innerHTML = '';
    }
  });
})();
