/*!
 * Originally from: https://mdbootstrap.com/docs/standard/content-styles/theme/
 * with modifications to support Bootstrap
 */

const themeSwitcher = document.getElementById("themingSwitcher");

// Stored preference wins; otherwise fall back to the system theme
const storedThemePreference = localStorage.getItem('themePreference');
let themePreference;

if (storedThemePreference !== null) {
  themePreference = storedThemePreference === 'true';
} else {
  themePreference = window.matchMedia("(prefers-color-scheme: dark)").matches;
  localStorage.setItem('themePreference', themePreference);
}

themeSwitcher.checked = themePreference;
applyTheme(themePreference);

themeSwitcher.addEventListener("change", (e) => {
  const isChecked = e.target.checked;
  applyTheme(isChecked);
  localStorage.setItem('themePreference', isChecked);
});

function applyTheme(isDarkTheme) {
  const theme = isDarkTheme ? "dark" : "light";
  document.documentElement.dataset.bsTheme = theme;
}

// Shift + D toggles the theme
document.addEventListener("keydown", (e) => {
  if (e.shiftKey && e.key === "D") {
    const newCheckedState = !themeSwitcher.checked;
    themeSwitcher.checked = newCheckedState;
    applyTheme(newCheckedState);
    localStorage.setItem('themePreference', newCheckedState);
  }
});
