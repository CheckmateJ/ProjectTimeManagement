/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// start the Stimulus application
import Vue from "vue";
import Timer from './Timer';
import './styles/app.css';


new Vue({
    el: '#app',
    render(h) {
        return h(Timer, {
            props: {
                projects: JSON.parse(this.$el.attributes['data-projects'].value),
                projectsTime: JSON.parse(this.$el.attributes['data-projects-time'].value)
            }
        })
    }
});