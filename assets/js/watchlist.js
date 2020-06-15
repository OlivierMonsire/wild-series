//document.querySelector('.watchlist').addEventListener('click', addToWatchlist)

let list = document.querySelectorAll(".watchlist");

for (let i=0;i<list.length;i++){
    console.log(list[i].id)
    list[i].addEventListener('click', addToWatchlist)
}

function addToWatchlist(event) {
// Get the link object you click in the DOM
    let watchlistIcon = event.target;
    let link = watchlistIcon.dataset.href;
    console.log(link)
// Send an HTTP request with fetch to the URI defined in the href
    fetch(link)
        .then(function (res) {
                if (watchlistIcon.classList[2] === "fas") {
                    watchlistIcon.classList.remove('fas'); // Remove the .far (empty heart) from classes in <i> element
                    watchlistIcon.classList.add('far'); // Add the .fas (full heart) from classes in <i> element
                    console.log(watchlistIcon.classList)
                } else if (watchlistIcon.classList[0] === "fas") {
                    watchlistIcon.classList.remove('fas'); // Remove the .far (empty heart) from classes in <i> element
                    watchlistIcon.classList.add('far'); // Add the .fas (full heart) from classes in <i> element
                    console.log(watchlistIcon.classList)
                } else {
                    watchlistIcon.classList.remove('far'); // Remove the .far (empty heart) from classes in <i> element
                    watchlistIcon.classList.add('fas'); // Add the .fas (full heart) from classes in <i> element
                    console.log(watchlistIcon.classList)
                }
            }
        )

}

console.log('yo')
