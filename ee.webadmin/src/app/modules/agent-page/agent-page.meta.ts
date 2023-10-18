import { FanpageMeta } from "../fanpage/fanpage.meta";

export class AgentPageMeta {
  agent_id: number;
  page_id: number;
  agent_page: FanpageMeta;
  existsPages: AgentPageMeta[];
}
