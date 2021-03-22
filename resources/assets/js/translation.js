import axios from "axios";
import Session from '../js/helpers/Session.js'
import _ from "lodash";

var translations = [];

export default {

    load_file(language) {

        if (language == 'en') {
            return;
        }
        let trans = '';
        axios.get(`/json/${language}.json`)
            .then(response => {
              return  response.data;
            })


        // Session.set('hubdesk_lang', language);
        //
        // if (!Session.get(`translation_${language}`)) {
        //     axios.get(`/json/${language}.json`)
        //         .then(response => {
        //             Session.set(`translation_${language}`, response.data)
        //         })
        // }

        // return Session.get(`translation_${language}`)
    },

    t(word) {
        translations = Session.get(`translation_${Session.get('hubdesk_lang')}`)

        if (Session.get('hubdesk_lang') == 'en') {
            return word;
        }

        let translation = _.find(translations, {'word': word});

        if (translation) {
            return translation.translation;
        }

        return word;
    }
}
