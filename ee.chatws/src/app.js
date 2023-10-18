const process = require('process');
const express = require('express');
const app = express();
const http = require('http').Server(app);
const handlers = require("./handlers");
const dotenv = require('dotenv');
const logging = require('./logging');
var cors = require("cors");
const favicon = require('serve-favicon');
const {exec} = require("child_process");
const Redis = require("ioredis");
const socketIO = require('socket.io');

module.exports = {
    process: function () {
        dotenv.config();
        logging.config();
        const REDIS_PREFIX = process.env.REDIS_PREFIX;

        const io = socketIO(http, {
            cors: {
                origin: '*',
                methods: ["PUT", "GET", "POST", "DELETE", "OPTIONS"],
                credentials: false,
            }
        });
        //Forward ban tin den TVV
        const redisClient = new Redis(process.env.REDIS_URL, {
            db: process.env.REDIS_DB,
        });

        const redisSubscriber = redisClient.duplicate();
        redisSubscriber.subscribe(`${REDIS_PREFIX}:broadcast`, (err, count) => {
            if (err) console.error(err.message);
            console.log(`Subscribed to ${count} channels.`);
        });
        redisSubscriber.on("message", (channel, payload) => {
            console.log(`Received message from ${channel} channel.`);
            if (channel === `${REDIS_PREFIX}:broadcast`) {
                console.log(`broadcast:`, payload);
                payload = JSON.parse(payload);
                io.emit(payload['emitChannel'], payload['payload']);
            }
        });


        const createError = (msg, data) => {
            const err = new Error(msg);
            err.data = {content: data};
            return err;
        };

        io.use((socket, next) => {
            const token = socket.handshake.auth.token;
            if (token) {
                redisClient.get(`${REDIS_PREFIX}:agents:${token}`).then(value => {
                    if (value === null) {
                        next(createError('unauthenticated', {content: "Please retry later"}));
                    } else {
                        socket.data.token = token;
                        onAgentConnected(socket);
                        next();
                    }
                });
            } else {
                next(createError('unauthenticated', {content: "Please retry later"}));
            }
        });


        io.on('connection', (socket) => {

            socket.on("disconnecting", (reason) => {
                console.log(`disconnecting: ${reason}`);
            });

            socket.on('error', (reason) => {
                console.log(`error: ${reason}`);
                onAgentLeaved(socket);
            });

            socket.on("disconnect", (reason) => {
                console.log(`disconnect: ${reason}`);
                onAgentLeaved(socket);
            });
        });

        const onAgentLeaved = (socket) => {
            redisClient.publish(`${REDIS_PREFIX}:agent_status`, JSON.stringify({token: socket.data.token, status: 0}));
        };

        const onAgentConnected = (socket) => {
            redisClient.publish(`${REDIS_PREFIX}:agent_status`, JSON.stringify({token: socket.data.token, status: 1}));
        };


        //Express js
        app.use(favicon(__dirname + '/favicon.ico'));
        app.use(cors({
            origin: '*',
            optionsSuccessStatus: 200
        }));
        app.get('/', (req, res) => {
            res.sendFile(__dirname + '/index.html');
        });

        const port = process.env.APP_PORT || 3000;
        http.listen(port, () => {
            console.log(`Socket.IO server running at http://localhost:${port}/`);
        });
    }
};
