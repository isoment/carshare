module.exports = {
    // purge: [],
    purge: {
        content: [
          './resources/**/*.blade.php',
          './resources/**/*.js',
          './resources/**/*.vue',
        ],
    },
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
        fontFamily: {
            'boldsans': 'Tienne',
            'boldnosans': 'Roboto Slab',
            'primary': 'Inter',
        },
    },
    variants: {
        extend: {},
    },
    plugins: [],
}
