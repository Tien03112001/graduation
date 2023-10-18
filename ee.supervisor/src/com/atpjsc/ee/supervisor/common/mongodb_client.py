import os

import pymongo

from com.atpjsc.ee.supervisor.common.singleton import Singleton


class MongoDBClient(metaclass=Singleton):

    def __init__(self):
        uri = os.getenv('MONGODB_URI', 'mongodb://admin@103.179.173.40:17017/?authSource=admin')
        password = os.getenv('MONGODB_PASSWORD', None)
        print(uri)
        self.client = pymongo.MongoClient(uri, password=password)
        print("Connected to the MongoDB database!")

    def get_collection(self, db: str, table: str):
        return self.client[db].get_collection(table)
