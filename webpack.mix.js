const {mix} = require('laravel-mix');
//
// /*
//  |--------------------------------------------------------------------------
//  | Mix Asset Management
//  |--------------------------------------------------------------------------
//  |
//  | Mix provides a clean, fluent API for defining some Webpack build steps
//  | for your Laravel application. By default, we are compiling the Sass
//  | file for the application as well as bundling up all the JS files.
//  |
//  */
//
// // mix.js('resources/assets/js/app.js', 'public/js')
// // mix.js('resources/assets/js/Reports/reports.js','public/js');
// // mix.sass('resources/assets/sass/bootstrap-rtl.scss', 'public/bootstrap-rtl.css')
// // mix.sass('resources/assets/sass/app.scss', 'public/css')
// // mix.sass('resources/assets/sass/app.scss', 'public/css')
// // mix.copyDirectory('resources/fonts', 'public/fonts');
// // mix.sass('resources/assets/sass/app.scss', 'public/css');
// //       mix.js('resources/assets/js/app.js', 'public/js')
//
// const tailwindcss = require('tailwindcss')
// // //
// mix.sass('resources/assets/sass/print.scss', 'public/css')
//     .options({
//         processCssUrls: false,
//         postCss: [tailwindcss('tailwind.config.js')],
//     })
//
// mix.sass('resources/assets/sass/app.scss', 'public/css')
// // mix.sass('resources/assets/sass/print.scss', 'public/css')
// //     .js('resources/assets/js/app.js', 'public/js')
// // //       .js('resources/assets/js/Report/index.js', 'public/js/report.js')
// mix.js('resources/assets/js/ticket-index.js', 'public/js')
// mix.js('resources/assets/js/ticket/ticket-show.js', 'public/js/ticket')
mix.js('resources/assets/js/letters/letters.js', 'public/js/letters')
// mix.js('resources/assets/js/ticket/ticket-approval-show.js', 'public/js/ticket')

// mix.js('resources/assets/js/ticket-form.js', 'public/js')
// //       .js('resources/assets/js/ticket.js', 'public/js')
// //       mix.js('resources/assets/js/criteria.js', 'public/js')
// //       mix.js('resources/assets/js/business-rules.js', 'public/js');
// //       mix.js('resources/assets/js/approval-levels.js', 'public/js');
// //         mix.js('resources/assets/js/approval-levels.js', 'public/js');
// // mix.js('resources/assets/js/ticket-requirements/ticket-requirements.js', 'public/js/ticket-requirements.js');
//
// //       .js('resources/assets/js/task.js', 'public/js')
// //       .js('resources/assets/js/escalation.js', 'public/js');
// // mix.js('resources/assets/js/ticket-note.js', 'public/js');
// // mix.js('resources/assets/js/Task.vue', 'public/js/tasks.js');
// // mix.js('resources/assets/js/kgs_notifications/main.js', 'public/js/kgs/notifications/notifications.js');
//
// mix.js('resources/assets/js/dashboard/index.js', 'public/js/dashboard')
