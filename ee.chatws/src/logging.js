const process = require('process');
const util = require('util');
const log_stdout = process.stdout;
const moment = require('moment/moment');
const fs = require('fs');
const log_file = fs.createWriteStream(__dirname + '/app.log', {flags: 'a'});

const toString = (args) => {
    if (args === null || args === undefined) {
        return '';
    }
    if (Array.isArray(args)) {
        return args.map(e => toString(e)).join(' ');
    } else if (typeof args === 'object') {
        let keys = Object.keys(args);
        let obj = {};
        keys.forEach(key => {
            obj[key] = toString(args[key]);
        });
        return JSON.stringify(obj);
    }

    return args.toString();
};

const clgProd = (...args) => {
    let msg = util.format(`%s: ${toString(Array.from(args))}`, moment().format('YYYY-MM-DD hh:mm:ss'));
    log_file.write(msg + '\n');
    log_stdout.write(msg + '\n');
};

const clgLocal = (...args) => {
    let msg = util.format(`%s: ${toString(Array.from(args))}`, moment().format('YYYY-MM-DD hh:mm:ss'));
    log_stdout.write(msg + '\n');
};

const config = () => {
    if (process.env.APP_ENV === 'production') {
        console.log = clgProd;
    } else {
        console.log = clgLocal;
    }
};
module.exports = {
    config: config
};

