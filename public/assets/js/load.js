window.addEventListener("load", () => {
    const loader = document.body.querySelector(".loader");

    loader.classList.add("loader-hidden");

    loader.addEventListener("transitionend", () => {
        document.body.removeChild(document.body.querySelector(".loader"));
    });
    function hideLoader() {
    }

    function updateLoaderVisibility() {
        const connectionSpeed = navigator.connection
            ? navigator.connection.downlink
            : 1;

        const speedThreshold = 2; // Adjust the threshold as needed
        const isSlowConnection = connectionSpeed < speedThreshold;

        if (isSlowConnection) {
            // If the connection is slow, show the loader
            loader.classList.remove("loader-hidden");
        } else {
            // If the connection is fast, hide the loader
            hideLoader();
        }
    }

    updateLoaderVisibility();

    window.addEventListener("online", updateLoaderVisibility);
    window.addEventListener("offline", updateLoaderVisibility);
});