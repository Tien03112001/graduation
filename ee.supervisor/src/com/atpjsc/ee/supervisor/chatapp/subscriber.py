import threading
import time
import redis
import os
import requests
import json


class ChatAppSubscriber(object):

    def handle_agent_status(self, token: str, status: bool, count: int):
        chat_app_url_prefix = os.getenv('CHAT_APP_URL_PREFIX', 'https://chatapp.eziweb.tech/api/modules')
        headers = {
            'Authorization': 'Bearer %s' % token
        }
        url = "%s/chat/agents/%s" % (chat_app_url_prefix, 'online' if status else 'offline')
        print(url)
        response = requests.post(url, data=json.dumps({}), headers=headers)
        print(response.content)
        if response.status_code == 200:
            return True
        else:
            if count < 10:
                time.sleep(3)
                return self.handle_agent_status(token, status, count + 1)
            else:
                return False

    def subscribe(self):
        chat_app_redis_host = os.getenv('CHAT_APP_REDIS_HOST', '127.0.0.1')
        chat_app_redis_port = os.getenv('CHAT_APP_REDIS_PORT', '6379')
        chat_app_redis_pwd = os.getenv('CHAT_APP_REDIS_PWD', None)
        chat_app_redis_prefix = os.getenv('CHAT_APP_REDIS_PREFIX', None)
        chat_app_redis_db = os.getenv('CHAT_APP_REDIS_DB', 0)

        r = redis.Redis(host=chat_app_redis_host, port=chat_app_redis_port, db=chat_app_redis_db,
                        password=chat_app_redis_pwd)
        pub_sub = r.pubsub()

        pub_sub.subscribe('%s:agent_status' % chat_app_redis_prefix)

        while True:
            data = pub_sub.get_message()
            if data:
                print("Data: %s" % data)
                message = data['data']
                if message and message != 1:
                    print("Message: %s" % message.decode('utf-8'))
                    json_message = json.loads(message.decode('utf-8'))
                    thread = threading.Thread(target=self.handle_agent_status,
                                              args=(json_message['token'], json_message['status'], 0,))
                    thread.start()
            print('Sleeping 1s ...')
            time.sleep(1)
