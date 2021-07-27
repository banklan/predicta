import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
    theme:{
         themes: {
            light: {
                // primary: '#7d068c',
                primary: '#5A189A',
                secondary: '#fbff05',
            },
        },
    }
}

export default new Vuetify(opts)
