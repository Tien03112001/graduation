import {CustomerContactMeta} from '../customer-contact';

export class ConversationMeta {
  id: number;
  customer_id: number;
  contact_id: number;
  agent_id: number;
  source_type: string;
  source_id: number;
  contact: CustomerContactMeta;
  source: any;
  updated_at: string;
}
