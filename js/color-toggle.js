/*!
 * Originally from: https://mdbootstrap.com/docs/standard/content-styles/theme/
 * with modifications to support Bootstrap
 */

const themeStitcher = document.getElementById("themingSwitcher");

// Load theme preference from local storage or fall back to system preference
const storedThemePreference = localStorage.getItem('themePreference');
let themePreference;

if (storedThemePreference !== null) {
  // Use stored preference if available
  themePreference = storedThemePreference === 'true';
} else {
  // Use system theme if no preference is stored
  themePreference = window.matchMedia("(prefers-color-scheme: dark)").matches;
  // Save the initial preference based on system theme
  localStorage.setItem('themePreference', themePreference);
}

// Apply the theme based on the retrieved or determined preference
themeStitcher.checked = themePreference;
applyTheme(themePreference); // Ensure the theme is applied on page load

// Add listener to theme toggler
themeStitcher.addEventListener("change", (e) => {
  const isChecked = e.target.checked;
  applyTheme(isChecked);
  // Update the current theme preference in local storage whenever it is changed
  localStorage.setItem('themePreference', isChecked);
});

function applyTheme(isDarkTheme) {
  const theme = isDarkTheme ? "dark" : "light";
  document.documentElement.dataset.bsTheme = theme;
}

// Add listener to toggle theme with Shift + D
document.addEventListener("keydown", (e) => {
  if (e.shiftKey && e.key === "D") {
    const newCheckedState = !themeStitcher.checked;
    themeStitcher.checked = newCheckedState;
    applyTheme(newCheckedState);
    // Update the current theme preference in local storage whenever it is toggled via shortcut
    localStorage.setItem('themePreference', newCheckedState);
  }
});