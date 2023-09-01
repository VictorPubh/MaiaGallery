jQuery(document).ready(function() {
    const gliderElement = document.querySelector('.glider');

    var glider = new Glider(gliderElement, {
        slidesToShow: 2,
        draggable: true,
        arrows: {
            prev: '.glider-prev',
            next: '.glider-next'
        }
    });

    var gliderIndexElement = document.querySelector('.glider-index');
    
    gliderElement.addEventListener('glider-slide-visible', function(event) {
        var index = event.detail.slide + 1;
        var total = glider.slides.length;
        gliderIndexElement.textContent = index + ' / ' + total;
    });
});
