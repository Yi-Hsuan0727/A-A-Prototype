/**
 * World coverage map for the Home page's "bridge between global capital and
 * the American jobsite" section. Loaded only on the front page (see
 * functions.php) alongside d3 + topojson-client from a CDN.
 */
(function () {
  'use strict';
  if (typeof d3 === 'undefined' || typeof topojson === 'undefined') { return; }

  var container = document.querySelector('[data-road-map]');
  if (!container) { return; }

  function draw() {
    container.innerHTML = '';
    var width = container.clientWidth || 1000;
    var height = Math.max(220, Math.round(width * 0.34));

    var svg = d3.select(container).append('svg')
      .attr('viewBox', '0 0 ' + width + ' ' + height)
      .attr('preserveAspectRatio', 'xMidYMid meet')
      .attr('width', '100%')
      .attr('height', height);

    var projection = d3.geoNaturalEarth1().fitSize([width, height], { type: 'Sphere' });
    var path = d3.geoPath(projection);

    svg.append('path')
      .attr('d', path({ type: 'Sphere' }))
      .attr('fill', 'rgba(0,40,255,0.05)');

    d3.json('https://cdn.jsdelivr.net/npm/world-atlas@2.0.2/countries-110m.json').then(function (topo) {
      var countries = topojson.feature(topo, topo.objects.countries).features;
      svg.selectAll('path.country')
        .data(countries)
        .enter()
        .append('path')
        .attr('class', 'country')
        .attr('d', path)
        .attr('fill', '#141c33')
        .attr('stroke', 'rgba(91,133,255,0.25)')
        .attr('stroke-width', 0.6);
    }).catch(function () {
      // Offline / blocked CDN: fail quietly, leave the sphere background only.
    });
  }

  draw();
  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(draw, 200);
  });
})();
