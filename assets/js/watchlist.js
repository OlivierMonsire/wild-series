document.querySelector('#watchlist').addEventListener('click', addToWatchlist);
function addToWatchlist(event) {
    let watchlistIcon = event.target;
    let link = watchlistIcon.dataset.href;

    fetch(link)
        .then(function() {
            watchlistIcon.classList.remove('far');
            watchlistIcon.classList.add('fas');
        })
}
addToWatchlist(event)
console.log("I'm here too!!")
