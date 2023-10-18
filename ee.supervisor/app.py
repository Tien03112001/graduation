from dotenv import load_dotenv

from src.com.atpjsc.ee.supervisor.chatapp.subscriber import ChatAppSubscriber

load_dotenv()

if __name__ == '__main__':
    ChatAppSubscriber().subscribe()
    # TrafficSubscriber().subscribe()
    # FacebookTrackingSubscriber().subscribe()
