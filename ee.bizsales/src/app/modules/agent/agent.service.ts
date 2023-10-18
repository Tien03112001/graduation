import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';
import {ToasterService} from 'angular2-toaster';
import {AgentMeta} from './agent.meta';
import {environment} from '../../../environments/environment';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class AgentService extends AbstractCRUDService<AgentMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Tài khoản', 'agents');
    this.setApiUrl(environment.chatapp_url);
  }

  check(item: any) {
    return this.http.post<DataResponse<AgentMeta>>(`${this.urlRestAPI}/check`, {token:item})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
