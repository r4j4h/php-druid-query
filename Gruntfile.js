module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        phpunit: {
            classes: {
                dir: 'tests/'
            },
            options: {
                bin: 'vendor/bin/phpunit',
                colors: true,
                followOutput: true
            }
        }
    });

    grunt.loadNpmTasks('grunt-phpunit');

    // Default task(s).
    grunt.registerTask('default', ['test']);

    // Default task(s).
    grunt.registerTask('test', ['phpunit']);

};
