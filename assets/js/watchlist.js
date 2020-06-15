document.querySelector("#watchlist").addEventListener('click', addToWatchlist)

function addToWatchlist(event) {
// Get the link object you click in the DOM
    let watchlistIcon = event.target;
    let link = watchlistIcon.data.href;
    console.log(link)
// Send an HTTP request with fetch to the URI defined in the href
    fetch(link)
        .then(function(res) {

            watchlistIcon.classList.remove('far'); // Remove the .far (empty heart) from classes in <i> element
            watchlistIcon.classList.add('fas'); // Add the .fas (full heart) from classes in <i> element
        });
}
