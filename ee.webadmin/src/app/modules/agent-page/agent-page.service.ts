import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ToasterService } from 'angular2-toaster';
import { AgentPageMeta } from './agent-page.meta';
import { AbstractCRUDService } from '../../core/crud';
import { TitleService } from '../../core/services';
import { DataResponse } from '../../core/common';
import {environment} from '../../../environments/environment';

@Injectable()
export class AgentPageService extends AbstractCRUDService<AgentPageMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'tư vấn viên', 'agent_pages');
    this.setApiUrl(environment.chat_api_url);
  }

  attachAgentPage(body: any) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/attach`, body));
  }

  detachAgentPage(body: any) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/detach`, body));
  }

}
