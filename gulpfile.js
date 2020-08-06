const { series, parallel, watch, src, dest } = require('gulp');
const browserSync = require('browser-sync').create();
const plumber     = require('gulp-plumber');
const sass        = require('gulp-sass');
const debug       = require('gulp-debug');

const scssPath = 'SCSS';
const scssPaths = [
    `${scssPath}/**/*.scss`,
    `!${scssPath}/_bootstrap-4.3.1/**`
];

const cssPath  = 'www/css';
const wwwPath  = 'www';

const browserSyncTarget = 'pxc-web-ui.test';
const browserSyncPort   = 8888;


function watchStaticFiles() {
    var reloadWatcher = watch(['**/*.php', wwwPath + '/**/*.js']);
    reloadWatcher.on('change', function (path, stats) {
        browserSync.reload();
    });
}

//https://browsersync.io/docs/gulp
function watchCSS() {
    var streamWatcher = watch([cssPath + '/**/*.css']);
    streamWatcher.on('change', function (path, stats) {
        return src(path)
            .pipe(plumber())
            .pipe(browserSync.stream());
    });
}

function watchSCSS() {
    var streamWatcher = watch([scssPath + '/**/*.scss']);

    streamWatcher.on('change', function (path, stats) {
        return buildCSS();
    });
}

function buildCSS() {
    return src(scssPaths)
        .pipe(sass().on('error', sass.logError))
        .pipe(plumber())
        .pipe(dest(cssPath));
}

function browserSyncSetup(done) {
    browserSync.init({
        proxy: {
            target: browserSyncTarget
        },
        port : browserSyncPort,
        host: browserSyncTarget,
        open: false
    });

    done();
}

exports.default = series(buildCSS, parallel(browserSyncSetup, watchCSS, watchSCSS, watchStaticFiles));
