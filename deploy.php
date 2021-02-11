<?php
namespace Deployer;

use Symfony\Component\Console\Input\InputOption;

require 'recipe/laravel.php';

inventory('hosts.yml');

option('without-npm', null, InputOption::VALUE_OPTIONAL, 'Without npm', false);

set('bin/npm', function () {
    return run('which npm');
});

task('npm:install', function () {
    if (!input()->hasOption('without-npm')) {
        if (has('previous_release')) {
            if (test('[ -d {{previous_release}}/node_modules ]')) {
                run('cp -R {{previous_release}}/node_modules {{release_path}}');

                // If package.json is unmodified, then skip running `npm install`
                try {
                    run('diff {{previous_release}}/package.json {{release_path}}/package.json');
                } catch (\Exception $e) {
                    run("cd {{release_path}} && {{bin/npm}} install");
                }
                return;

//            if (!run('diff {{previous_release}}/package.json {{release_path}}/package.json')) {
//                return;
//            }
            }
        }
        run("cd {{release_path}} && {{bin/npm}} install");
    }
});

task('npm:build', function () {
    if (!input()->hasOption('without-npm')) {
        if (input()->hasOption('build') && input()->getOption('build') == 'dev') {
            run('cd {{release_path}} && {{bin/npm}} run dev');
        } else {
            run('cd {{release_path}} && {{bin/npm}} run prod');
        }
    } else {
        upload('public/css', '{{release_path}}/public/');
        upload('public/js', '{{release_path}}/public/');
        upload('public/fonts', '{{release_path}}/public/');
        upload('public/mix-manifest.json', '{{release_path}}/public/');
    }
});

task('uploads', function () {
    if (input()->hasArgument('stage') && input()->getArgument('stage') == 'production') {
        upload('.env.prod', '{{deploy_path}}/shared/.env');
    } elseif (input()->hasArgument('stage') && input()->getArgument('stage') == 'test') {
        upload('.env.test', '{{deploy_path}}/shared/.env');
    }
});

task('reload:php-fpm', function () {
    run('sudo systemctl restart php8.0-fpm');
});

task('reload:supervisor', function () {
    run("sudo supervisorctl reload");
});

task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'uploads',
    'deploy:shared',
    'deploy:vendors',
    'npm:install',
    'npm:build',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:clear',
//    'artisan:cache:clear',
    'artisan:config:cache',
//    'artisan:migrate',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

after('deploy', 'success');
after('deploy', 'reload:php-fpm');
//after('reload:php-fpm', 'reload:supervisor');
after('deploy:failed', 'deploy:unlock');
