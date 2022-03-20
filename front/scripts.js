{
  function initSpoilers() {
    document.addEventListener('DOMContentLoaded', function() {
      let elems = document.querySelectorAll('.collapsible.expandable');
      let instances = M.Collapsible.init(elems, {
        accordion: false,
        inDuration: 100
      });
    });
  }

  function init() {
    initSpoilers();
  }

  init();
}