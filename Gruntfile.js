module.exports = function(grunt) {
	
	grunt.initConfig({
		
		pkg: grunt.file.readJSON('package.json'),
		
		clean: {
			js: ['web/js/<%= pkg.name %>.js', 'web/js/<%= pkg.name %>.min.js'],
			css: ['web/css/<%= pkg.name %>.css', 'web/css/<%= pkg.name %>.min.css'],
			img: ['web/images/*.{png,jpg,gif}'],
			font: ['web/fonts/*.{eot,svg,ttf,woff,woff2}']
		},
		
		concat: {
			options: {
				separator: ';'
			},
			dist: {
				src: [
					'vendor/components/jquery/jquery.js',
					'vendor/twbs/bootstrap/dist/js/bootstrap.js',
					'assets/<%= pkg.name %>.js'
				],
				dest: 'web/js/<%= pkg.name %>.js'
			}
		},
		
		uglify: {
			dist: {
				files: {
					'web/js/<%= pkg.name %>.min.js': ['<%= concat.dist.dest %>']
				}
			}
		},
		
		cssmin: {
			dist: {
				files: {
					'web/css/<%= pkg.name %>.min.css':
					[
						'vendor/twbs/bootstrap/dist/css/bootstrap.css',
						'assets/<%= pkg.name %>.css'
					]
				}
			}
		},
		
		imagemin: {
			dynamic: {
				files: [
					{
						expand: true,
						cwd: 'assets/images/',
						src: '*.{png,jpg,gif}',
						dest: 'web/images/'
					}
				]
			}
		},
		
		copy: {
			dist: {
				files: [
					{
						expand: true,
						src: ['vendor/twbs/bootstrap/dist/fonts/*.{eot,svg,ttf,woff,woff2}'],
						dest: 'web/fonts/',
						filter: 'isFile'
					}
				]
			}
		}
	});
	
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-contrib-copy');
	
	grunt.registerTask('default', ['clean', 'concat', 'uglify', 'cssmin', 'imagemin', 'copy']);
};
