document.addEventListener("DOMContentLoaded", function() {
  let navigationAndComparison = document.getElementById('js-navigation-and-comparison-container');
  let navigationAndComparisonOffset = navigationAndComparison.offsetTop;

  function updateStickyHeaderSections() {
    if (window.pageYOffset > navigationAndComparisonOffset) {
      navigationAndComparison.classList.add('sticky');
    } else {
      navigationAndComparison.classList.remove('sticky');
    }
  }

  let footerBottom = document.getElementById('js-footer-bottom-container');
  let openingTimes = document.getElementById('js-opening-times-container');
  let openingTimesHeight = null;
  if (openingTimes) {
    openingTimesHeight = openingTimes.clientHeight;
  }

  function updateStickyFooter() {
    let bounding = footerBottom.getBoundingClientRect();

    if ((bounding.top + openingTimesHeight) > (window.innerHeight || document.documentElement.clientHeight)) {
      openingTimes.classList.add('sticky');
    } else {
      openingTimes.classList.remove('sticky');
    }
  }

  updateStickyHeaderSections();

  if (footerBottom && openingTimes && openingTimesHeight) {
    updateStickyFooter();
  }

  window.addEventListener('scroll', function(e) {
    updateStickyHeaderSections();

    if (footerBottom && openingTimes && openingTimesHeight) {
      updateStickyFooter();
    }
  });
});