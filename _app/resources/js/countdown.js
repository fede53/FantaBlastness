document.addEventListener('DOMContentLoaded', function() {
    var countdownElements = document.querySelectorAll('.countdown');
    countdownElements.forEach(function(countdownElement) {
        var data = countdownElement.getAttribute('data-countdown');
        var msg = countdownElement.getAttribute('data-msg');
        initializeCountdown(countdownElement, data, msg);
    });
});


function initializeCountdown(element, targetDate, message) {
    var countdownElement = element.querySelector('span');
    var eventDate = new Date(targetDate).getTime();

    var countdownInterval = setInterval(function() {
        var now = new Date().getTime();
        var distance = eventDate - now;

        if (distance < 0) {
            clearInterval(countdownInterval);
            countdownElement.innerHTML = message;
            window.location.reload();
        } else {
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
        }
    }, 1000);
}
