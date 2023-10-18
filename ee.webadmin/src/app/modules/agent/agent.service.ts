import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {TitleService} from '../../core/services';
import {ToasterService} from 'angular2-toaster';
import {AgentMeta} from './agent.meta';
import { environment } from '../../../environments/environment';

@Injectable()
export class AgentService extends AbstractCRUDService<AgentMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'tư vấn viên', 'agents');
    this.setApiUrl(environment.chat_api_url);
  }
}
