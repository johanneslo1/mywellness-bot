/** @type {import('tailwindcss').Config} */

module.exports = {
    darkMode: 'class',
    important: true,
    content: ['./**/*.html', './resources/js/**/*.{js,jsx,ts,tsx,vue}'],
    safelist: ['prose', 'prose-sm', 'm-auto'],
    plugins: [
        // require('@tailwindcss/forms'),
        // require('@tailwindcss/typography'),
    ],
}

