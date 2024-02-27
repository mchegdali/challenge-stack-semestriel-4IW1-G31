const appbar = document.getElementById('appbar');
const app = document.getElementById('app');


app.addEventListener('scroll', ev => {
    if (app.scrollTop > appbar.clientHeight) {
        appbar.classList.add('animate-out', 'fade-out')
        appbar.classList.remove('animate-in', 'fade-in')
    } else {
        appbar.classList.remove('animate-out', 'fade-out', 'hidden')
        appbar.classList.add('animate-in', 'fade-in')
    }
})

appbar.addEventListener('animationend', ev => {
    if (ev.animationName === 'exit') {
        appbar.classList.add('hidden')
    }

    if (ev.animationName === 'enter') {
        appbar.classList.remove('animate-in', 'fade-in')
    }
})