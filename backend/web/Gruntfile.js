'use strict';
module.exports = function(grunt) {
    // Load all tasks
    require('load-grunt-tasks')(grunt);
    // Show elapsed time
    require('time-grunt')(grunt);

    var jsFileList = [
        'assets/vendor/bootstrap/js/transition.js',
        'assets/vendor/bootstrap/js/alert.js',
        'assets/vendor/bootstrap/js/button.js',
        'assets/vendor/bootstrap/js/carousel.js',
        'assets/vendor/bootstrap/js/collapse.js',
        'assets/vendor/bootstrap/js/dropdown.js',
        'assets/vendor/bootstrap/js/modal.js',
        'assets/vendor/bootstrap/js/tooltip.js',
        'assets/vendor/bootstrap/js/popover.js',
        'assets/vendor/bootstrap/js/scrollspy.js',
        'assets/vendor/bootstrap/js/tab.js',
        'assets/vendor/bootstrap/js/affix.js',
        'assets/js/plugins/*.js',
        'assets/js/_*.js'
    ];

    grunt.initConfig({
        less: {
            dev: {
                files: {
                    'css/style.css': ['less/style.less'],
                    'css/responsive.css': ['less/responsive.less'],
                },
                options: {
                    compress: false,
                    // LESS source map
                    // To enable, set sourceMap to true and update sourceMapRootpath based on your install
                    sourceMap: true,
                    sourceMapFilename: 'css/style.css.map',
                    sourceMapRootpath: '/workflow/backend/web/'
                }
            },
            prod: {
                files: {
                    'css/style.min.css': ['less/style.less']
                },
                options: {
                    compress: true
                }
            }
        },
        //concat: {
        //    options: {
        //        separator: ';',
        //    },
        //    dist: {
        //        src: [jsFileList],
        //        dest: 'assets/js/scripts.js',
        //    },
        //},
        //uglify: {
        //    dist: {
        //        files: {
        //            'assets/js/scripts.min.js': [jsFileList]
        //        }
        //    }
        //},
        autoprefixer: {
            options: {
                browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']
            },
            dev: {
                options: {
                    map: {
                        prev: 'css/'
                    }
                },
                src: 'css/style.css'
            },
            prod: {
                src: 'css/style.min.css'
            }
        },
        watch: {
            less: {
                files: [
                    'less/*.less',
                    'less/**/*.less',
                    'less/**/**/*.less',
                ],
                tasks: ['less:dev'/*, 'autoprefixer:dev'*/]
            },
            //js: {
            //    files: [
            //        jsFileList,
            //        '<%= jshint.all %>'
            //    ],
            //    tasks: ['jshint', 'concat']
            //},
            livereload: {
                // Browser live reloading
                // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
                options: {
                    livereload: true
                },
                files: [
                    'css/style.css',
                    '../views/**/*.php',
                    '../widgets/views/*.php',
                    '../controllers/*.php',
                    '../models/*.php',
                    '../modules/**/*.php',
                    '../modules/**/**/*.php',
                    '../modules/**/**/**/*.php',
                ]
            }
        }
    });

    // Register tasks
    grunt.registerTask('default', [
        'dev'
    ]);
    grunt.registerTask('dev', [
        'less:dev',
        //'autoprefixer:dev',
        //'concat'
    ]);
    grunt.registerTask('prod', [
        'less:prod ',
        'autoprefixer:prod',
        //'uglify',
    ]);
};