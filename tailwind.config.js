module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [],
  theme: {
    extend: {
      screens: {
        'print': {'raw': 'print'},
        colors: {
          "custom-yellow": {
            "500": "#EDAE0A",
          },
        }
      }
    }
  },
  variants: {},
  plugins: [],
}
