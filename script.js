window.onpageshow = function() {
    let tweetsElement = document.getElementsByClassName("tw-tweets");
    for (let i = 0; i < tweetsElement.length; ++i) {

        let id = tweetsElement[i].getAttribute("twitterID");

        twttr.widgets.createTweet(
            id,
            tweetsElement[i], {
                conversation: 'none',
                align: 'center',
                theme: 'light'
            }
        ).then(function(el) {
            document.getElementById("loader").classList.add("hide-loader");
        });
    }

};