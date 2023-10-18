import { ConversationMeta } from "../conversation/conversation.meta";

export class MessageMeta {
  id: number;
  conversation_id: number;
  sender_name: string;
  type: string;
  content: string;
  conversation: ConversationMeta;
  created_at: string;
}
