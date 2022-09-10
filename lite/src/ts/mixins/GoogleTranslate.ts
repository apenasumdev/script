export default {
    mounted: function()
    {
        this.$nextTick(() => {
            this.googleTranslateInit()
        });

    },
    methods: {

        googleTranslateInit: function() {

            let checkIfGoogleLoaded = setInterval(() => {
                //@ts-ignore
                if (google.translate.TranslateElement != null) {
                    clearInterval(checkIfGoogleLoaded)

                    this.googleTranslateElement('google_translate_element')
                }

            }, 100)

        },

        googleTranslateElement: function(id) {
            //@ts-ignore
            new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, id)
        }

    },
};