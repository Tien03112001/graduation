import json
from time import sleep
from typing import List

from kafka import KafkaConsumer
from threading import Thread


class KafkaSubscriber(object):
    topic: str
    consumer: KafkaConsumer
    thread: Thread
    status: bool

    def __init__(self, topic: str, group_id: str, bootstrap_servers: List[str],
                 f_process=None,
                 auto_offset_reset='earliest',
                 value_deserializer=lambda v: json.loads(v.decode('utf-8')),
                 consumer_timeout_ms=float('inf')):
        self.consumer = KafkaConsumer(topic, group_id=group_id,
                                      bootstrap_servers=bootstrap_servers,
                                      auto_offset_reset=auto_offset_reset,
                                      value_deserializer=value_deserializer,
                                      consumer_timeout_ms=consumer_timeout_ms)
        self.status = True
        self.thread = Thread(target=self.run, args=(f_process,))

    def run(self, f_process):
        while self.status:
            print('Running ...')
            for msg in self.consumer:
                KafkaSubscriber.print_message(msg)
                if f_process is not None:
                    f_process(msg)
            sleep(1)

    @staticmethod
    def print_message(message):
        print("%s:%d:%d: key=%s value=%s" % (
            message.topic, message.partition, message.offset, message.key, message.value))

    def start(self):
        print('Starting ...')
        self.status = True
        self.thread.start()

    def restart(self):
        self.stop()
        print('Sleeping 2s ..')
        sleep(2)
        self.start()

    def stop(self):
        print('Stopping ...')
        self.status = False
        self.thread.join()
        print('Stopped!!!')
