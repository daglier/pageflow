const rootElement = document.documentElement;
const slider = document.getElementById('slider');
const preferedColorScheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';


// Function to sets the theme for the app
const setTheme = theme => {
    rootElement.setAttribute('data-theme', theme);
    slider.checked = theme === 'light' ? false : true;
    localStorage.setItem('theme', theme);
};

setTheme(localStorage.getItem('theme') || preferedColorScheme);

slider.addEventListener('click', () => {
    let switchToTheme = localStorage.getItem('theme') === 'dark' ? 'light' : 'dark';
    setTheme(switchToTheme);
});