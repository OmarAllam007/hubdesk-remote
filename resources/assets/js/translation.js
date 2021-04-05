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
                console.log(response.data)
              return  response.data;
            });
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
