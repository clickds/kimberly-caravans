const plugin = require('tailwindcss/plugin')

module.exports = {
    theme: {
        extend: {
            colors: {
                alabaster: "#f7f7f7",
                "dove-gray": "#686868",
                endeavour: "#0065a3",
                gallery: "#ebebeb",
                "silver-gray": "#8c8c8c",
                monarch: "#850624",
                "regal-blue": "#004067",
                shiraz: "#a3082d",
                tundora: "#464646",
                "web-orange": "#EBAA00",
                "benimar-mileo-yellow": "#ffcc00",
                "benimar-tessoro-green": "#b9d296",
                "benimar-primero-purple": "#70559f",
                "benimar-benivan-orange": "#ec6408",
                "mobilvetta-red": "#8d221c",
                "majestic-grey": "#575756",
                "marquis-blue": "#123274",
                "marquis-gold": "#967b4f",
                "marquis-dark-grey": "#646363",
                "service-blue": "#00749a",
                "spring-green": "#99bf0f",
                "summer-yellow": "#ffd900",
                "autumn-brown": "#9b673c",
                "winter-blue": "#7ab2cc",
                "black": "#000000",
                "january-sale-pink": "#e7216b"
            },
            fontFamily: {
                sans: ["Roboto", "Helvetica", "sans-serif"],
                heading: ['"Exo 2"', "sans-serif"]
            },
            fontSize: {
                h1: "2.625rem",
                h2: "2.25rem",
                h3: "1.875rem",
                h4: "1.25rem",
                h5: "1.25rem",
                "mobile-h1": "1.875rem",
                "mobile-h2": "1.5rem",
                "mobile-h3": "1.25rem",
                "mobile-h4": "1.125rem",
                "mobile-h5": "1.125rem",
                price: "7.625rem"
            },
            height: {
                "google-map": "400px",
                "locations-map": "600px",
                "1/2-screen": "50vh"
            },
            maxHeight: {
                "64": "16rem",
            },
            inset: {
                "1/3": "33.3333333%",
                "1/5": "20%",
                full: "100%"
            },
            opacity: {
                "90": "0.90",
                "45": "0.45"
            },
            screens: {
                print: { raw: "print" }
            },
            zIndex: {
                "-10": "-10",
                "max": "9999999"
            },
        }
    },
    variants: {},
    plugins: [
        plugin(function({ addUtilities }) {
            const newUtilities = {
              '.unset': {
                position: 'unset',
              },
              '.overflow-unset': {
                overflow: 'unset',
              },
            }
            addUtilities(newUtilities, ['responsive', 'hover'])
          })
    ],
    purge: false,
};
