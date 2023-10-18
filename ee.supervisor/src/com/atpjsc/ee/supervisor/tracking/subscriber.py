import os

from com.atpjsc.ee.supervisor.common.kafka_consumer import KafkaSubscriber
from com.atpjsc.ee.supervisor.common.mongodb_client import MongoDBClient


class FacebookTrackingSubscriber(object):

    def __init__(self):
        self.topic = 'fb_tracking'
        self.group_id = 'ee.supervisor'
        self.servers = os.getenv('KAFKA_HOST', 'kafka:9092').split(',')
        self.mongo_collection = MongoDBClient().get_collection('logging', 'facebook_tracking_events')

    def process(self, message):
        print("%s:%d:%d: key=%s value=%s" % (
            message.topic, message.partition, message.offset, message.key, message.value))
        self.mongo_collection.insert_one(message.value)

    def subscribe(self):
        consumer = KafkaSubscriber(self.topic, self.group_id, self.servers, f_process=self.process)
        consumer.start()
