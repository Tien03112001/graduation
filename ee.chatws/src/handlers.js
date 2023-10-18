module.exports = (io, socket, event) => {
    const pushNotification = (payload) => {
        console.log(payload);
        const ignore = payload['ignore'] === true;
        let emitter = socket;
        if (payload['toGroups']) {
            emitter = io.to(payload['toGroups']);
            emitter = ignore ? emitter.broadcast : emitter;
            emitter.emit(`${event}:sub`, payload['msg']);
        } else if (payload['toUsers']) {
            let type = typeof payload['toUsers'];
            if (type === 'string') {
                payload['toUsers'] = payload['toUsers'].split(',');
            }
            emitter = ignore ? emitter.broadcast : emitter;
            payload['toUsers'].forEach(userId => emitter.emit(`${event}:sub:${userId}`, payload['msg']));
        }
    };
    socket.on(`${event}:pub`, pushNotification);
};
