'use strict';

export const elementToggleFunc = function (elem) {
    elem.classList.toggle("active");
};

export const removeLoadingAnimation = function() {
    const loadingElement = document.querySelector('.loading-animation');
    if (loadingElement) {
        const intervalId = loadingElement.parentElement.getAttribute('data-interval');
        clearInterval(intervalId);
        loadingElement.parentElement.remove();
    }
};
