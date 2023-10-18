import { CustomerContactMeta } from "../customer-contact/customer-contact.meta";
import {CustomerMeta} from '../customer/customer.meta';
import {MessageMeta} from '../message/message.meta';

export class ConversationMeta {
  id: number;
  customer_id: number;
  contact: CustomerContactMeta;
  customer: CustomerMeta;
  source: any;
  updated_at: string;
  update_time: string;
  messages: MessageMeta
}
